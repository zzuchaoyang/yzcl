<template>
    <div class="app-container product-query" style="overflow-y: auto" :style="{height: this.$store.getters.tableHeight + 220 + `px`}">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true">
                        <el-form-item label="商品条码">
                            <el-input v-model="more.bar_code"
                                      placeholder="请输入商品条形码编号"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')"
                                      clearable/>
                        </el-form-item>
                        <el-form-item label="商品名称">
                            <el-input v-model="more.name"
                                      placeholder="请输入商品名称"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')"
                                      clearable/>
                        </el-form-item>
                        <el-form-item label="品牌名称">
                            <el-input v-model="more.brand_name"
                                      placeholder="请输入品牌名称"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')"
                                      clearable/>
                        </el-form-item>
                        <el-form-item label="商品状态">
                            <el-select v-model="more.status" @change="getList('refresh')" placeholder="请选择商品状态" clearable>
                                <el-option label="正常" value="正常"></el-option>
                                <el-option label="停用" value="停用"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-table :data="list.items"
                      v-loading="list.listLoading"
                      element-loading-text="Loading"
                      fit
                      :height="tableHeight"
                      highlight-current-row>
                <el-table-column align="center" label='序号' width="60" type="index">
                </el-table-column>
                <el-table-column align="center" label='商品图片' width="80">
                    <template slot-scope="scope">
                        <div v-viewer v-if="scope.row.pictures && scope.row.pictures.length">
                            <img class="el-table-viewer-img"
                                 v-for="(item, index) in scope.row.pictures"
                                 :src="item"
                                 :key="index"
                                 v-show="index === 0"/>
                        </div>
                    </template>
                </el-table-column>

                <el-table-column align="center" label='商品条码'>
                    <template slot-scope="scope">
                        <span>{{ scope.row.bar_code }}</span>
                    </template>
                </el-table-column>

                <el-table-column align="center" label='商品名称'>
                    <template slot-scope="scope">
                        <span>{{ scope.row.name }}</span>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="商品品牌">
                    <template slot-scope="scope">
                        <span>{{scope.row.brand && scope.row.brand.name || ''}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="生产厂商">
                    <template slot-scope="scope">
                        <span :title="scope.row.manufacturer">{{scope.row.manufacturer || ''}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订货单位">
                    <template slot-scope="scope">
                        <span>{{scope.row.order_unit}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订货规格">
                    <template slot-scope="scope">
                        <span>{{scope.row.spec_number+scope.row.spec_unit + '*' + scope.row.single_num}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="含税进价（元）">
                    <template slot-scope="scope">
                        <span>{{scope.row.trade_price}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="一级供货价（元）">
                    <template slot-scope="scope">
                        <span>{{scope.row.one_price}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="状态" width="80">
                    <template slot-scope="scope">
                        <span>{{scope.row.status}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" fixed="right" label="操作" width="200">
                    <template slot-scope="scope">
                        <div>
                            <el-button type="primary"  size="mini" plain @click="goDetail(scope.row)">查看</el-button>
                            <el-button type="primary"  size="mini" plain @click="goEdit(scope.row)">修改</el-button>
                        </div>
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
  import { productPaginatorList } from '../../api/product'
  import { listHelper } from '@common/utils/listHelper'
  export default {
    data() {
      return {
        list: {
          items: [0],
          total: null,
          listLoading: false
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        more: {
          bar_code: null,
          name: null,
          brand_name: null,
          status: null,
          supplier_id: null
        },
        tableHeight: window.innerHeight - 283
      }
    },
    computed: {
      isFollow() {
        return this.$store.getters.tableHeight
      }
    },
    watch: {
      isFollow(newVal) {
        if (newVal) {
          this.tableHeight = newVal + 47
        }
      }
    },
    created() {
      this.more.supplier_id = this.$store.state.user.user.id
      this.getList()
    },
    methods: {
      // 获取商品列表
      getList(action = 'init') {
        this.list.listLoading = true
        if (action === 'refresh') {
          this.listQuery.page = 1
        }
        productPaginatorList(this.listQuery, this.more).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.productPaginator)
        }).finally(() => {
          // this.list.listLoading = false
        })
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this.getList()
        document.querySelector('.el-table__body-wrapper').scrollTop = 0
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this.getList()
        document.querySelector('.el-table__body-wrapper').scrollTop = 0
      },
      // 查看详情
      goDetail(row) {
        this.$router.push({
          name: 'product.detail',
          params: {
            id: row.id,
            tip: { content: row.name }
          }
        })
      },
      // 修改
      goEdit(row) {
        this.$router.push({
          name: 'productEdit',
          params: {
            id: row.id,
            tip: { content: row.name }
          }
        })
      }
    }
  }
</script>
<style lang="scss" scoped>

</style>
