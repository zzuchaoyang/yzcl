<template>
    <div class="app-container">
        <el-card>
        <el-row type="flex" justify="space-between">
            <el-col :span="22">
                <el-form :inline="true">
                    <el-form-item>
                        <el-select size="small" v-model="listMore.product_category" @change="getList('refresh')" clearable
                                   placeholder="产品类型">
                            <el-option
                                    v-for="item in productType"
                                    :key="item"
                                    :label="item"
                                    :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-select size="small" v-model="listMore.jr_product_id" @change="getList('refresh')" clearable
                                   placeholder="产品名称">
                            <el-option
                                    v-for="item in productList"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-select size="small" v-model="listMore.status" @change="getList('refresh')" clearable
                                   placeholder=" 状态">
                            <el-option
                                    v-for="item in statusOptions"
                                    :key="item.value"
                                    :label="item.value"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-input size="small" v-model="listMore.debtor" placeholder="债务人姓名/证件号/手机 ↵"
                                  @keyup.enter="getList('refresh')"
                                  @change="getList('refresh')" clearable></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-input size="small" v-model="listMore.report_keyword" placeholder="报单人姓名/手机 ↵"
                                  @keyup.enter="getList('refresh')"
                                  @change="getList('refresh')" clearable></el-input>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="2" class="fn-tr">
                <el-button style='margin:0 0 20px 20px; display: inline-block' size="small" @click="getList('refresh')"
                           icon="el-icon-refresh" class="el-button--success">
                    刷新
                </el-button>
            </el-col>
        </el-row>
        <el-table :data="list.items"
                  v-loading="listLoading"
                  element-loading-text="加载中"
                  border
                  fit
                  @sort-change="sortList"
                  :height="this.$store.getters.tableHeight"
                  highlight-current-row>
            <el-table-column align="center" fixed label='报单编号' width="130">
                <template slot-scope="scope">
                    <router-link class="link-type"
                                 :to="{ name: 'finance.declaration.detail',
                                 params:{
                                    id: scope.row.id,
                                    tip: { title: getCompanyInfo(scope.row).companyName, content: getCompanyInfo(scope.row).name + ' ' + scope.row.uuid } }}">
                        <span>{{ scope.row.uuid }}</span>
                    </router-link>
                </template>
            </el-table-column>
            <el-table-column
                    label="状态"
                    fixed
                    prop="status"
                    width="110"
                    align="center">
                <template slot-scope="scope">
                    {{scope.row.status}}
                </template>
            </el-table-column>

            <el-table-column label="报单人信息" align="center">
                <el-table-column label="来源" width="80" align="center">
                    <template slot-scope="scope">
                        <span>{{ getCompanyInfo(scope.row).source }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="公司名称" width="260" align="center">
                    <template slot-scope="scope">
                        <span>{{ getCompanyInfo(scope.row).companyName }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="报单人" align="center">
                    <template slot-scope="scope">
                        <span>{{ getCompanyInfo(scope.row).name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="报单人电话" width="110" align="center">
                    <template slot-scope="scope">
                        <span>{{ getCompanyInfo(scope.row).mobile }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="报单时间" prop="created_at" sortable="custom" width="170" align="center">
                    <template slot-scope="scope">
                        {{scope.row.created_at}}
                    </template>
                </el-table-column>
            </el-table-column>

            <el-table-column label="债务人信息" align="center">
                <el-table-column label="债务人姓名" width="110" align="center">
                    <template slot-scope="scope">
                        {{scope.row.debtor_name}}
                    </template>
                </el-table-column>
                <el-table-column label="证件号码" width="210" align="center">
                    <template slot-scope="scope">
                        {{scope.row.debtor_id_card}}
                    </template>
                </el-table-column>
                <el-table-column label="手机号码" width="110" align="center">
                    <template slot-scope="scope">
                        {{scope.row.debtor_mobile}}
                    </template>
                </el-table-column>
            </el-table-column>

            <el-table-column label="申请信息" align="center">
                <el-table-column
                        label="产品类型"
                        width="110"
                        align="center">
                    <template slot-scope="scope" v-if="scope.row.jrProduct">
                        {{scope.row.jrProduct.category}}
                    </template>
                </el-table-column>
                <el-table-column
                        label="产品名称"
                        width="110"
                        align="center">
                    <template slot-scope="scope" v-if="scope.row.jrProduct">
                        {{scope.row.jrProduct.name}}
                    </template>
                </el-table-column>
                <el-table-column label="期限" width="80" align="center">
                    <template slot-scope="scope">
                        {{scope.row.term}}
                    </template>
                </el-table-column>
                <el-table-column label="申请金额(万元)" width="130" align="center">
                    <template slot-scope="scope">
                        {{scope.row.loan_amount}}
                    </template>
                </el-table-column>
                <el-table-column label="期望放款时间" width="110" align="center">
                    <template slot-scope="scope">
                        {{scope.row.expect_loan_time}}
                    </template>
                </el-table-column>
            </el-table-column>

            <el-table-column align="center" label="抵押物" width="70">
                <template slot-scope="scope">
                    <span v-if="scope.row.mortgage_information.length"
                          class="link-type"
                          @click='showMortgageInformationDialog(scope.row.mortgage_information)'>{{scope.row.mortgage_information.length}}</span>
                    <span v-else>0</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="共借人" width="70">
                <template slot-scope="scope">
                    <span v-if="scope.row.borrowers.length"
                          class="link-type"
                          @click='showBorrowersDialog(scope.row.borrowers)'>{{scope.row.borrowers.length}}</span>
                    <span v-else>0</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="图片" width="50">
                <template slot-scope="scope">
                    <div :class="'images'+ scope.$index" v-viewer="{movable: false}">
                        <img v-for="src in scope.row.picture_urls" :src="src" :key="src" style="display: none">
                    </div>
                    <span v-if="scope.row.picture_urls.length"
                          class="link-type"
                          @click='showPicturesDialog(scope.$index)'>{{scope.row.picture_urls.length}}</span>
                    <span v-else>0</span>
                </template>
            </el-table-column>

            <el-table-column align="center" fixed="right" label="操作" width="80" class-name="small-padding fixed-width">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="mini" @click="showUpdateDialog(scope.row)">编辑</el-button>
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

        <el-dialog v-el-drag-dialog title="编辑" :visible.sync="editDialog.visible" width="550px">
            <el-form :rules="rules" ref="dataForm" :model="editDialog.temp" label-position="right" label-width="170px">
                <el-steps :active="_.findIndex(statusOptions, {value: editDialog.temp.status}) + 1" align-center class="steps-box">
                    <el-step title="新增" icon="slot" :class="{ 'is-active_no': editDialog.temp.status === '否决' }">
                        <svg-icon icon-class="xinzeng" slot="icon" class="svg-font"/>
                    </el-step>
                    <el-step title="审批中" icon="slot" :class="{ 'is-active_no': editDialog.temp.status === '否决' }">
                        <svg-icon icon-class="shenpi" slot="icon" class="svg-font"/>
                    </el-step>
                    <el-step title="还款中" icon="slot" :class="{ 'is-active_no': editDialog.temp.status === '否决' }">
                        <svg-icon icon-class="huankuan" slot="icon" class="svg-font"/>
                    </el-step>
                    <el-step title="完成" icon="slot" :class="{ 'is-active_no': editDialog.temp.status === '否决' }">
                        <svg-icon icon-class="wancheng" slot="icon" class="svg-font"/>
                    </el-step>
                    <el-step title="否决" icon="slot" :class="{ 'is-active': editDialog.temp.status === '否决' }">
                        <svg-icon icon-class="foujue" slot="icon" class="svg-font"/>
                    </el-step>
                </el-steps>
                <el-form-item label="状态" prop="type">
                    <el-select class="filter-item"
                               v-model="editDialog.temp.status"
                               placeholder="请选择">
                        <el-option v-for="item in statusOptions"
                                   :key="item.value"
                                   :label="item.value"
                                   :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>
                <!--<el-form-item label="备注">-->
                <!--<el-input type="textarea" :autosize="{ minRows: 4, maxRows: 12}" placeholder="备注信息将同步到提报人处" v-model="temp.remark">-->
                <!--</el-input>-->
                <!--</el-form-item>-->
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="editDialog.visible = false">取消</el-button>
                <el-button type="primary" @click="updateData">确定</el-button>
            </div>
        </el-dialog>

        <el-dialog title="抵押物信息" :visible.sync="mortgageInformationDialogVisible">
            <el-table :data="mortgageInformationData" border fit highlight-current-row style="width: 100%">
                <el-table-column prop="mortgagor_name" label="抵押人姓名"></el-table-column>
                <el-table-column prop="house_certificate_no" label="房产证号"></el-table-column>
                <el-table-column prop="house_address" label="房屋地址"></el-table-column>
                <el-table-column prop="house_area" label="房屋面积（平米）"></el-table-column>
                <el-table-column prop="ownership_type" label="房屋性质"></el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="mortgageInformationDialogVisible = false">确认</el-button>
            </span>
        </el-dialog>

        <el-dialog title="共借人信息" :visible.sync="borrowersDialogVisible">
            <el-table :data="borrowersDialogData" border fit highlight-current-row style="width: 100%">
                <el-table-column prop="borrower_name" label="共借人姓名"></el-table-column>
                <el-table-column prop="id_card" label="证件号码"></el-table-column>
                <el-table-column prop="mobile" label="手机号码"></el-table-column>
            </el-table>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="borrowersDialogVisible = false">确认</el-button>
            </span>
        </el-dialog>
        </el-card>
    </div>
</template>

<script>
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import { getFinanceDeclarationPaginator, approve, getJrProductPaginator, getJrProductType } from '../../api/finance'
  import { parseTime } from '@common/utils'
  import { listHelper } from '@common/utils/listHelper'
  import { notify } from '@common/utils/helper'
  import 'viewerjs/dist/viewer.css'
  import Viewer from 'v-viewer'
  import Vue from 'vue'
  Vue.use(Viewer)

  const statusOptions = [
    { value: '新增' },
    { value: '审批中' },
    { value: '还款中' },
    { value: '完成' },
    { value: '否决' }
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
          product_category: null,
          jr_product_id: null,
          report_keyword: null,
          debtor: null,
          status: null,
          date: null
        },
        downloadLoading: false,
        // 状态下拉选项
        statusOptions,
        // 产品类别
        productType: [],
        // 产品列表
        productList: [],
        // 弹窗
        editDialog: {
          visible: false,
          temp: {},
          statusActived: null
        },
        rules: {
          status: [{ required: true, message: '状态必填', trigger: 'change' }],
          remark: [{ type: 'date', required: true, message: 'timestamp is required', trigger: 'change' }]
        },
        // 抵押物弹窗
        mortgageInformationDialogVisible: false,
        mortgageInformationData: [],
        // 共借人信息弹窗
        borrowersDialogVisible: false,
        borrowersDialogData: []
      }
    },
    created() {
      this.getList()
      this.getProductType()
    },
    methods: {
      getCompanyInfo(data) {
        // console.log(data.uuid)
        return {
          source: data.server ? 'ERP' : '好房多',
          companyName: data.server ? data.server.name : data.fxsCompany.organization_name,
          name: data.agent ? data.agent.name : (data.fxsUser? data.fxsUser.name : ''),
          mobile: data.agent ? data.agent.mobile : data.fxsUser.phone_number
        }
      },
      // 获取产品类型
      getProductType() {
        getJrProductType().then(response => {
          this.productType = response.data.jrProductCategories
        })
      },
      // 获取产品名称列表
      getProductList() {
        const productQuery = {
          page: 1,
          limit: 100,
          sort: '-id'
        }
        const productMore = {
          keywords: '',
          category: this.listMore.product_category || '',
          status: '启用'
        }
        getJrProductPaginator(productQuery, productMore).then(response => {
          this.productList = response.data.jrProductPaginator.items
        })
      },
      // 获取列表
      getList(action) {
        this.listLoading = true
        if (this.listMore.product_category) {
          this.getProductList()
        } else {
          this.productList = []
        }
        getFinanceDeclarationPaginator(this.listQuery, this.listMore).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.financeDeclarationPaginator)
          if (action === 'refresh') {
            notify.success()
          }
          this.listLoading = false
        })
      },
      // 列表排序
      sortList(sort) {
        listHelper.setSort(this.listQuery, sort)
        this.getList()
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
      // 显示更新弹窗
      showUpdateDialog(row) {
        this.editDialog.temp = Object.assign({}, row) // copy obj
        this.editDialog.visible = true
        this.$nextTick(() => {
          this.$refs['dataForm'].clearValidate()
        })
      },
      // 更新数据
      updateData() {
        this.$refs['dataForm'].validate((valid) => {
          if (valid) {
            const tempData = Object.assign({}, this.editDialog.temp)
            approve(tempData.id, tempData.status).then(() => {
              const items = this.list.items
              for (const v of items) {
                if (v.id === this.editDialog.temp.id) {
                  const index = items.indexOf(v)
                  items.splice(index, 1, this.editDialog.temp)
                  break
                }
              }
              this.editDialog.visible = false
              this.$notify({
                title: '成功',
                message: '更新成功',
                type: 'success',
                duration: 2000
              })
            })
          }
        })
      },
      // 显示抵押物弹窗
      showMortgageInformationDialog(mortgageInformation) {
        this.mortgageInformationData = mortgageInformation
        this.mortgageInformationDialogVisible = true
      },
      // 显示共借人弹窗
      showBorrowersDialog(borrowers) {
        this.borrowersDialogData = borrowers
        this.borrowersDialogVisible = true
      },
      showPicturesDialog(num) {
        const viewer = this.$el.querySelector('.images'+ num).$viewer
        viewer.show()
      },
      // 导出 excel
      exportExcel() {
        this.downloadLoading = true
        import('@supplier/vendor/Export2Excel').then(excel => {
          const tHeader = ['报单编号', '状态']
          const filterVal = ['uuid', 'status']
          const list = this.list
          const data = this.formatJson(filterVal, list)
          excel.export_json_to_excel({
            header: tHeader,
            data,
            filename: this.filename,
            autoWidth: this.autoWidth
          })
          this.downloadLoading = false
        })
      },
      // 格式化 JSON ，内部方法
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        }))
      }
    }
  }
</script>
<style lang="scss" scoped>
    .steps-box {
        margin-bottom: 30px;
        .svg-font {
            font-size: 26px;
        }
        /deep/ .el-step__head.is-finish {
            color: #67c23a;
            border-color: #67c23a;
        }
        /deep/ .el-step__title.is-finish {
            color: #67c23a;
        }
        /deep/ .el-step:nth-child(4) {
            .el-step__head {
                .el-step__line {
                    display: none;
                }
            }
        }
        .is-active {
            /deep/ .el-step__head.is-finish {
                color: red;
                border-color: red;
            }
            /deep/ .el-step__title.is-finish {
                color: red;
            }
        }
        .is-active_no {
            /deep/ .el-step__head.is-finish {
                color: #c0c4cc;
                border-color: #c0c4cc;
            }
            /deep/ .el-step__title.is-finish {
                color: #c0c4cc;
            }
        }
    }
</style>
