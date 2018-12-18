<template>
    <div class="app-container">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="17">
                    <el-form :inline="true">
                        <el-form-item>
                            <el-select size="small" v-model="listMore.category" @change="getList('refresh')" clearable
                                       placeholder="产品类型">
                                <el-option
                                        v-for="item in typeOptions"
                                        :key="item"
                                        :label="item"
                                        :value="item">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item>
                            <el-input size="small" v-model="listMore.keywords" placeholder="产品名称↵"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')" clearable></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-select size="small" v-model="listMore.jr_status" @change="getList('refresh')" clearable>
                                <el-option
                                        v-for="item in statusOptions"
                                        :key="item.value"
                                        :label="item.name"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-form>
                </el-col>
                <el-col :span="5" class="fn-tr">
                    <el-button style='margin:0 0 20px 20px; display: inline-block' size="small" @click="getList('refresh')"
                               icon="el-icon-refresh" class="el-button--success">
                        刷新
                    </el-button>

                    <el-button style='margin:0 0 20px 20px;display: inline-block' type="primary" @click="showAddDialog()"
                               icon="el-icon-edit" size="small"
                               :loading="downloadLoading">新增
                    </el-button>
                </el-col>
            </el-row>
            <el-table :data="list.items"
                      v-loading="listLoading"
                      element-loading-text="Loading"
                      border
                      fit
                      :height="this.$store.getters.tableHeight"
                      highlight-current-row>
                <el-table-column
                        label="产品类型"
                        fixed
                        prop="status"
                        width="150"
                        align="center">
                    <template slot-scope="scope">
                        {{scope.row.category}}
                    </template>
                </el-table-column>
                <el-table-column align="center" label="名称" width="130">
                    <template slot-scope="scope">
                        <span>{{scope.row.name}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="额度" width="130">
                    <template slot-scope="scope">
                        <span v-if="scope.row.info">{{scope.row.info.limit}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="贷款期限" width="150">
                    <template slot-scope="scope">
                        <span v-if="scope.row.info">{{scope.row.info.loan_period}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="利率" width="130">
                    <template slot-scope="scope">
                        <span v-if="scope.row.info">{{scope.row.info.interest_rate}}%</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="还款方式" width="150">
                    <template slot-scope="scope">
                        <span v-if="scope.row.info">{{scope.row.info.payment_method}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="收费标准">
                    <template slot-scope="scope">
                        <span v-if="scope.row.info">{{scope.row.info.fee_scale}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="产品特点">
                    <template slot-scope="scope">
                        <span v-if="scope.row.good_tags">{{formatTags(scope.row.good_tags)}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="服务城市" width="230">
                    <template slot-scope="scope">
                        <span>{{getCitysName(scope.row.cities)}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="描述">
                    <template slot-scope="scope">
                        <span>{{scope.row.comment}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" fixed="right" label="状态" width="80">
                    <template slot-scope="scope">
                        <el-switch
                                @change="changeStatus(scope.row)"
                                active-value="有效"
                                inactive-value="无效"
                                v-model="scope.row.jr_status">
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="180" class-name="small-padding fixed-width">
                    <template slot-scope="scope">
                        <el-button type="primary" plain size="mini" @click="showAddDialog(scope.row)">编辑</el-button>
                        <!--<el-button type="danger" plain size="mini" @click="deleteProduct(scope.row)">删除</el-button>-->
                    </template>
                </el-table-column>
            </el-table>
            <el-row type="flex" justify="end" class="pagination-container">
                <el-pagination background
                               @size-change="changeListSize"
                               @current-change="changeListCurrentPage"
                               :current-page="listQuery.page"
                               :page-sizes="[10, 20, 30, 50]"
                               :page-size="listQuery.limit"
                               layout="total, sizes, prev, pager, next, jumper"
                               :total="list.total">
                </el-pagination>
            </el-row>
        </el-card>
    </div>
</template>

<script>
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import { getJrProductPaginator, storeJrProduct, deleteJrProduct, getOptionsCity, getJrProductType } from '../../api/finance'
  import { listHelper } from '@common/utils/listHelper'
  import { messageBox, notify } from '@common/utils/helper'
  // 类型
  const typeOptions = []
  // 状态
  const statusOptions = [
    { name: '有效', value: '有效' },
    { name: '无效', value: '无效' }
  ]
  export default {
    directives: { elDragDialog },
    data() {
      return {
        list: {
          items: null,
          total: null,
          listLoading: true
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        listMore: {
          category: null,
          keywords: null,
          jr_status: '有效'
        },
        downloadLoading: false,
        typeOptions,
        statusOptions,
        // 弹窗
        editDialog: {
          visible: false,
          area: null,
          city: {},
          selectedCity: []
        },
        temp: {},
        CitysSelect: {
          loading: false,
          options: [],
          city: []
        }
      }
    },
    created() {
      this.getList()
      this.getProductType()
    },
    methods: {
      // 选择城市
      changeArea() {
        this.editDialog.city = null
      },
      // 获取产品类型
      getProductType() {
        getJrProductType().then(response => {
          this.typeOptions = response.data.jrProductCategories
        })
      },
      // 获取列表
      getList(action = 'init') {
        this.listLoading = true
        getJrProductPaginator(this.listQuery, this.listMore).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.jrProductPaginator)
          if (action === 'refresh') {
            notify.success()
          }
          this.listLoading = false
        })
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this.getList()
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this.getList()
      },
      getCitysName(citys) {
        return this._.map(citys, 'name').join(',')
      },
      formatTags(tags) {
        return tags.join(',')
      },
      // 显示产品窗口
      showAddDialog(row) {
        if (row) {
          console.log(row);
          this.$router.push({name: 'product.detail', params: {id: row.id, tip:{ title: row.name, content: row.category }}})
        } else {
          this.$router.push({name: 'product.add.product'})
        }

        // if (row) {
        //   // this.newCommunitiesSelect.options = row.newCommunity ? [row.newCommunity] : []
        //   this.temp = JSON.parse(JSON.stringify(row))
        // } else {
        //   this.temp = {
        //     category: null,
        //     name: null,
        //     city_ids: [],
        //     comment: null,
        //     cities: []
        //   }
        // }
        // this.getDialogCity()
        // this.editDialog.visible = true
      },
      // 添加城市
      getDialogCity() {
        getOptionsCity().then(response => {
          this.CitysSelect.options = response.data.optionsCity
        }).finally(() => {
          this.CitysSelect.loading = false
        })
      },
      dialogAddCity() {
        if (this.editDialog.city && this.editDialog.city.id) {
          if (this._.indexOf(this.temp.city_ids, Number(this.editDialog.city.id)) === -1) {
            this.temp.cities.push(this.editDialog.city)
            this.temp.city_ids.push(Number(this.editDialog.city.id))
          } else {
            notify.error('当前城市已添加')
          }
        }
      },
      handleClose(val) {
        this.temp.cities.splice(this.temp.cities.indexOf(val), 1)
        this.temp.city_ids.splice(this.temp.city_ids.indexOf(Number(val.id)), 1)
      },
      // 产品保存
      storeProduct() {
        this.$refs['dataForm'].validate((valid) => {
          if (!valid) {
            return false
          }
          if (this.temp.city_ids.length === 0) {
            notify.error('请选择服务城市')
            return false
          }
          const tempData = Object.assign({}, this.temp)
          storeJrProduct(tempData).then(() => {
            this.getList()
            this.editDialog.visible = false
            notify.success()
          })
        })
      },
      // 更改状态
      changeStatus(row) {
        storeJrProduct(row).then(() => {
          notify.success()
        })
      },
      // 删除产品
      deleteProduct(row) {
        messageBox.confirmDelete('删除后不可恢复，确定要删除吗？', () => {
          deleteJrProduct(row.id).then(() => notify.success(), () => notify.error).finally(() => this.getList())
        })
      }
    }
  }
</script>
<style lang="scss" scoped>
    .dialogProduct {
        .filter-item {
            width: 145px;
        }
        .el-tag--small {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .fw-city{
            /deep/ .el-form-item__label:before {
                content: '*';
                color: #f56c6c;
                margin-right: 4px;
            }
        }
    }
</style>
