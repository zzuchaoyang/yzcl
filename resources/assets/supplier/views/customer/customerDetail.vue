<template>
    <div class="app-container" :style="{height: this.$store.getters.tableHeight + 200 + 'px'}">
        <div class="customer-detail">
            <el-card>
                <el-row>
                    <div class="title">客户信息</div>
                </el-row>
                <el-row>
                    <el-col :xs="24" :sm="16">
                        <el-row class="row-space">
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">客户名称</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">
                                            {{list.currentData.store_info ? list.currentData.store_info.name : '暂无'}}
                                        </span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">客户编号</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">{{list.currentData.mobile}}</span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">客户状态</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text" v-if="list.currentData.customerSupplier[0]">{{list.currentData.customerSupplier[0].status_alias}}</span>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                        <el-row class="row-space">
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">经营面积</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">
                                            {{list.currentData.store_info ? list.currentData.store_info.usable_area : '暂无'}}
                                        </span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">年营业额</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">
                                            {{list.currentData.store_info ? list.currentData.store_info.year_turnover : '暂无'}}
                                        </span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">开业时间</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">
                                            {{list.currentData.store_info ? list.currentData.store_info.opened_at : '暂无'}}
                                        </span>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                        <el-row class="row-space">
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">联系人</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">{{list.currentData.name}}</span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">联系电话</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">{{list.currentData.mobile}}</span>
                                    </el-col>
                                </el-row>
                            </el-col>
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">所在地址</span>
                                    </el-col>
                                    <el-col :span="16">
                                        <span class="text">
                                            {{list.currentData.city ? list.currentData.city : '暂无'}}
                                        </span>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                        <el-row class="row-space">
                            <el-col :xs="24" :sm="8">
                                <el-row>
                                    <el-col :span="8">
                                        <span class="label">供货价级别</span>
                                    </el-col>
                                    <el-col :span="16" v-if="!is_edit">
                                        <span class="text"
                                              v-if="list.currentData.customerSupplier[0]">
                                            {{list.currentData.customerSupplier[0].supply_grade ? list.currentData.customerSupplier[0].supply_grade : '暂无'}}
                                        </span>
                                        <el-button type="primary" size="mini" class="edit-btn" @click="editSupplyGrade" v-if="list.currentData.customerSupplier[0]&&list.currentData.customerSupplier[0].supply_grade">修改</el-button>
                                    </el-col>
                                    <el-col :span="16" v-if="is_edit">
                                        <el-select class="select-sm"
                                                v-model="list.currentData.customerSupplier[0].supply_grade"
                                                clearable
                                                placeholder="选择客户状态">
                                            <el-option
                                                    v-for="item in priceLevel"
                                                    :key="item.value"
                                                    :label="item.name"
                                                    :value="item.value">
                                            </el-option>
                                        </el-select>
                                        <el-button type="text" size="medium" class="cancel-btn" title="点击取消修改" @click="cancelSupplyGrade">X</el-button>
                                        <el-button type="primary" size="medium" class="edit-btn" @click="updateSupplyGrade(list.currentData)">保存</el-button>
                                    </el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="8">
                        <el-row class="row-space">
                            <el-col :span="24" align="right">
                                <el-button type="primary"
                                           @click="supplierActionApplication(list.currentData,'暂停')"
                                           v-if="list.currentData.customerSupplier[0]&&list.currentData.customerSupplier[0].status === 'normal'">暂停</el-button>
                                <el-button type="primary"
                                           @click="supplierActionApplication(list.currentData,'审核')"
                                           v-if="list.currentData.customerSupplier[0]&&list.currentData.customerSupplier[0].status === 'wait'&&list.currentData.customerSupplier[0].cooperationApplication.sender_type==='customer'">审核</el-button>
                                <el-button type="primary"
                                           @click="supplierActionApplication(list.currentData,'拒绝')"
                                           v-if="list.currentData.customerSupplier[0]&&list.currentData.customerSupplier[0].status === 'wait'&&list.currentData.customerSupplier[0].cooperationApplication.sender_type==='customer'">拒绝</el-button>
                                <el-button type="primary"
                                           @click="supplierActionApplication(list.currentData,'启用')"
                                           v-if="list.currentData.customerSupplier[0]&&list.currentData.customerSupplier[0].status === 'pause'">启用</el-button>
                            </el-col>
                        </el-row>
                        <el-row class="row-space">
                            <el-col :span="24" align="right">
                                <div class="trade-con">
                                    <div class="trade-num fl">{{list.currentData.history_commission}}</div>
                                    <div class="trade-detail fl">
                                        <div class="trade-detail-tit row-space">历史成交金额（元）</div>
                                        <div class="trade-detail-desc row-space">当前客户历史成交金额总计</div>
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
            </el-card>
            <el-card>
                <el-row>
                    <div class="title">历史订单</div>
                </el-row>
                <el-row class="row-space order-form">
                    <el-col :span="24">
                        <el-form :inline="true" :model="search">
                            <el-form-item label="订货时间">
                                <el-date-picker
                                        @keyup.enter="_getOrderList"
                                        @change="_getOrderList"
                                        v-model="search.datetime"
                                        type="daterange"
                                        align="right"
                                        clearable
                                        value-format="yyyy-MM-dd"
                                        start-placeholder="开始日期"
                                        end-placeholder="结束日期">
                                </el-date-picker>
                            </el-form-item>
                            <el-form-item label="订单状态">
                                <el-select
                                        @keyup.enter="_getOrderList"
                                        @change="_getOrderList"
                                        multiple
                                        collapse-tags
                                        clearable
                                        v-model="search.status"
                                        placeholder="选择订单状态">
                                    <el-option
                                            v-for="item in statusOptions"
                                            :key="item.value"
                                            :label="item.name"
                                            :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="订单编号">
                                <el-input
                                        @keyup.enter="_getOrderList"
                                        @change="_getOrderList"
                                        clearable
                                        v-model="search.order_number"
                                        placeholder="请输入订单编号"></el-input>
                            </el-form-item>
                        </el-form>
                    </el-col>
                    <el-col :span="12">
                        <el-form :inline="true">
                            <el-form-item>
                                <span class="sort-title">排序</span>
                                <el-button-group>
                                    <el-button
                                            :type="sort_type == 'number'?'primary':'info'"
                                            @click="sortType('number')">
                                        订货数量
                                        <svg-icon icon-class="down" v-if="isSortDown.number" />
                                        <svg-icon icon-class="up" v-if="!isSortDown.number" />
                                    </el-button>
                                    <el-button
                                            :type="sort_type == 'price'?'primary':'info'"
                                            @click="sortType('price')">订货金额
                                        <svg-icon icon-class="down" v-if="isSortDown.price" />
                                        <svg-icon icon-class="up" v-if="!isSortDown.price" />
                                    </el-button>
                                </el-button-group>
                            </el-form-item>
                        </el-form>
                    </el-col>
                    <el-col :span="12" align="right">
                        <el-form :inline="true">
                            <el-form-item>
                                <el-button type="primary" @click="handleDownload" :disabled="orderLists.items&&!orderLists.items.length">导出Excel表格</el-button>
                            </el-form-item>
                        </el-form>
                    </el-col>
                </el-row>
                <el-table :data="orderLists.items"
                          fit
                          :height="this.$store.getters.tableHeight"
                          highlight-current-row>
                    <el-table-column
                            label="序号"
                            type="index"
                            width="50"
                            align="center">
                    </el-table-column>
                    <el-table-column align="center" label="订单编号" width="160">
                        <template slot-scope="scope">
                            <span class="text-warning">{{scope.row.order_number}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订货时间" width="150">
                        <template slot-scope="scope">
                            <span>{{scope.row.order_at}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="客户名称" width="170">
                        <template slot-scope="scope">
                            <span v-if="scope.row.customer">{{scope.row.customer.store_info ? scope.row.customer.store_info.name : ''}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订单金额(元)">
                        <template slot-scope="scope">
                            <span>{{scope.row.price}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订单状态">
                        <template slot-scope="scope">
                            <span class="text-red">{{scope.row.status_alias}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="联系人">
                        <template slot-scope="scope">
                            <span v-if="scope.row.customer">{{scope.row.customer.name}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="联系电话" width="100">
                        <template slot-scope="scope">
                            <span v-if="scope.row.customer">{{scope.row.customer.mobile}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="操作" width="140"
                                     class-name="small-padding">
                        <template slot-scope="scope">
                            <el-button type="primary" plain @click="showOrderDetail(scope.row)">查看</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-row type="flex" justify="end" class="pagination-container">
                    <el-pagination background
                                   @size-change="changeListSize"
                                   @current-change="changeListCurrentPage"
                                   :current-page="orderQuery.page"
                                   :page-sizes="[10, 20, 30, 50]"
                                   :page-size="orderQuery.limit"
                                   layout="total, sizes, prev, pager, next, jumper"
                                   :total="orderLists.total">
                    </el-pagination>
                </el-row>
            </el-card>
        </div>
        <el-dialog
                v-el-drag-dialog
                title="设置供货价级别"
                :visible.sync="dialogGrade"
                width="400px"
                class="order-dialog">
            <el-form ref="gradeForm" :model="gradeData" :rules="applyRules">
                <el-form-item label="设定供应商级别" prop="grade">
                    <el-select
                            v-model="gradeData.grade"
                            placeholder="选择供货价级别"
                            clearable>
                        <el-option
                                v-for="item in priceLevel"
                                :key="item.value"
                                :label="item.name"
                                :value="item.value">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item align="center" class="el-item-grade">
                    <el-button type="info" @click="dialogGrade = !dialogGrade">取消</el-button>
                    <el-button type="primary" @click="submitGrade">提交</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
  import { GetCustomerLists, SupplierActionApplication } from '../../api/customer'
  import { listHelper } from '@common/utils/listHelper'
  import { getOptions } from '@supplier/api/common'
  import { GetOrderLists } from '../../api/order'
  import { notify } from '@common/utils/helper'
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import { parseTime } from '@common/utils'

  export default {
    directives: { elDragDialog },
    data() {
      var getDefaultDate = () => {
        const end = new Date()
        const start = new Date()
        start.setTime(start.getTime() - 3600 * 1000 * 24 * 6)
        end.setTime(end.getTime())
        const format_start = parseTime(start).slice(0, 10)
        const format_end = parseTime(end).slice(0, 10)
        const defaultDate = [format_start, format_end]
        return defaultDate
      }
      return {
        search: {
          datetime: getDefaultDate(),
          status: [],
          order_number: ''
        },
        statusOptions: [],
        sort_type: 'number',
        isSortDown: {
          number: true,
          price: true
        },
        currentPage: 1,
        list: {
          items: [],
          total: null,
          currentData: {
            customerSupplier: [
              {
                status_alias: '',
                supply_grade: ''
              }
            ]
          }
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        orderLists: {
          items: [],
          total: null,
          listLoading: true
        },
        orderQuery: {
          page: 1,
          limit: 20,
          sort: '-number'
        },
        priceLevel: [],
        is_edit: false,
        supply_grade_copy: '',
        dialogGrade: false,
        gradeData: {
          grade: ''
        },
        applyRules: {
          grade: [
            { required: true, message: '请选择供货价级别' }
          ]
        },
        exportLists: []
      }
    },
    created() {
      this._getCustomerLists('created')
    },
    methods: {
      _getCustomerLists(life) {
        const params = {
          id: this.$route.params.id
        }
        GetCustomerLists(this.listQuery, params).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.customerList)
          this.$set(this.list, 'currentData', this.list.items[0])
          this._getOrderList(life)
        })
      },
      _getOrderList(life) {
        const orderParams = {
          order_at_start: this.search.datetime ? this.search.datetime[0] : '',
          order_at_end: this.search.datetime ? this.search.datetime[1] : '',
          status: this.search.status,
          order_number: this.search.order_number,
          customer_id: this.list.items[0].id
        }
        this.orderQuery.sort = (this.isSortDown[this.sort_type] ? '-' : '+') + this.sort_type
        if (life !== 'page') {
          this.orderQuery.page = 1
        }
        this.orderLists.listLoading = true
        GetOrderLists(this.orderQuery, orderParams).then(response => {
          listHelper.setList(this.orderLists, this.orderQuery, response.data.orderPaginator)
        }).finally(() => {
          this.orderLists.listLoading = false
          if (life === 'created') {
            getOptions('order', 'status').then(response => {
              this.statusOptions = response.data.options
              this._.remove(this.statusOptions, option => option.value === 'new')
            })
          }
        })
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.orderQuery.limit = value
        this._getOrderList('page')
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.orderQuery.page = value
        this._getOrderList('page')
      },
      sortType(type) {
        if (this.sort_type === type) {
          this.isSortDown[type] = !this.isSortDown[type]
        } else {
          this.isSortDown[type] = true
          this.isSortDown[this.sort_type] = true
          this.sort_type = type
        }
        this._getOrderList()
      },
      supplierActionApplication(row, action) {
        if (action === '审核') {
          getOptions('grade', 'supply_grade').then(response => {
            this.priceLevel = response.data.options
            this._.remove(this.priceLevel, option => option.value === 'new')
          }).finally(() => {
            this.dialogGrade = true
          })
        } else {
          this.$confirm('此操作将会更改客户状态, 是否继续?', action + '客户状态', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            SupplierActionApplication(action, row.customerSupplier[0].cooperation_application_id).then((response) => {
              notify.success('操作成功')
              this._getCustomerLists()
            })
          })
        }
      },
      submitGrade() {
        if (!this.gradeData.grade) {
          notify.warning('请选择供货价级别')
          return false
        }
        SupplierActionApplication('审核', parseInt(this.list.currentData.customerSupplier[0].cooperation_application_id), this.gradeData.grade).then((response) => {
          notify.success('操作成功')
          this._getCustomerLists()
          this.dialogGrade = false
        })
      },
      showOrderDetail(row) {
        this.$router.push({ name: 'order.detail', params: { id: row.id, tip: { content: row.order_number }}})
      },
      handleDownload() {
        this.downloadLoading = true
        const exportParams = {
          order_at_start: this.search.datetime ? this.search.datetime[0] : '',
          order_at_end: this.search.datetime ? this.search.datetime[1] : '',
          status: this.search.status,
          order_number: this.search.order_number,
          customer_id: this.list.items[0].id
        }
        this.orderQuery.sort = (this.isSortDown[this.sort_type] ? '-' : '+') + this.sort_type
        const exportQuery = this._.clone(this.orderQuery)
        exportQuery.limit = this.orderLists.total
        GetOrderLists(exportQuery, exportParams).then(response => {
          this.exportLists = response.data.orderPaginator.items
        }).finally(() => {
          import('@supplier/vendor/Export2Excel').then(excel => {
            const tHeader = ['订单编号', '订货时间', '客户名称', '订单金额(元)', '订单状态', '联系人', '联系电话']
            const filterVal = ['order_number', 'order_at', 'customer_name', 'price', 'status_alias', 'contact_name', 'mobile']
            const list = this.exportLists
            const data = this.formatJson(filterVal, list)
            excel.export_json_to_excel({
              header: tHeader,
              data,
              filename: '历史订单',
              autoWidth: this.autoWidth
            })
            this.downloadLoading = false
          })
        })
      },
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'customer_name') {
            return v.customer.store_info ? v.customer.store_info.name : ''
          } else if (j === 'contact_name') {
            return v.customer.name
          } else if (j === 'mobile') {
            return v.customer.mobile
          } else {
            return v[j]
          }
        }))
      },
      editSupplyGrade() {
        getOptions('grade', 'supply_grade').then(response => {
          this.priceLevel = response.data.options
          this._.remove(this.priceLevel, option => option.value === 'new')
        }).finally(() => {
          this.supply_grade_copy = this.list.currentData.customerSupplier[0].supply_grade
          this.is_edit = true
        })
      },
      updateSupplyGrade(data) {
        if (!data.customerSupplier[0].supply_grade) {
          notify.warning('请选择供货价级别')
          return false
        }
        SupplierActionApplication('修改供货价', data.customerSupplier[0].cooperation_application_id, data.customerSupplier[0].supply_grade).then((response) => {
          notify.success('操作成功')
          this._.each(this.priceLevel, (o) => {
            if (o.value === data.customerSupplier[0].supply_grade) {
              data.customerSupplier[0].supply_grade = o.name
            }
          })
        }).finally(() => {
          this.is_edit = false
        })
      },
      cancelSupplyGrade() {
        this.is_edit = false
        this.list.currentData.customerSupplier[0].supply_grade = this.supply_grade_copy
      }
    }
  }

</script>
<style scoped>
    .customer-detail{
        height: 100%;
        overflow-y: auto;
    }
    .title {
        font-size: 14px;
        color: #F39800;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .label{
        font-size: 12px;
        color: #999;
    }
    .text{
        word-break: break-all;
        font-size: 12px;
        color: #333;
    }
    .text-red {
        color: #F56C6C;
    }

    .text-warning {
        color: #F39800;
    }
    .sort-title{
        font-size: 12px;
    }
    .row-space{
        margin-top: 15px;
    }
    .trade-con{
        position: relative;
        padding: 15px;
        overflow: hidden;
        background: #F39800;
        display: inline-block;
        min-width: 313px;
    }
    .trade-num{
        min-width: 65px;
        height: 65px;
        color: #F39800;
        display: flex;
        font-size: 24px;
        background: rgba(255,255,255,.8);
        font-weight: bold;
        align-items: center;
        justify-content: center;
    }
    .trade-detail{
        color: #fff;
        text-align: left;
        margin-left: 5px;
    }
    .trade-detail-tit{
        margin-top: 6px;
        font-size: 14px;
    }
    .trade-detail-desc{
        margin-bottom: 15px;
        font-size: 12px;
    }
    .el-date-editor.el-input{
        width: 180px;
    }
    .select-sm{
        max-width: 90px;
    }
    .cancel-btn{
        color: #f56c6c;
    }
    .el-item-grade{
        margin-top: 35px;
        margin-bottom: 0;
    }
    .edit-btn{
        padding: 5px 7px;
        margin-left: 5px;
    }
</style>
