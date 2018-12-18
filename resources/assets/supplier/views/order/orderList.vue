<template>
    <div class="app-container">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true" :model="search" class="order-form">
                        <el-form-item label="订货日期">
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
                                    @change="_getOrderList('change')"
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
                        <el-form-item label="联系电话">
                            <el-input
                                    @keyup.enter="_getOrderList"
                                    @change="_getOrderList"
                                    clearable
                                    v-model="search.mobile"
                                    placeholder="请输入联系电话"></el-input>
                        </el-form-item>
                        <el-form-item label="客户名称">
                            <el-input
                                    @keyup.enter="_getOrderList"
                                    @change="_getOrderList"
                                    clearable
                                    v-model="search.customer_name"
                                    placeholder="请输入客户名称"></el-input>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-table :data="list.items"
                      v-loading="list.listLoading"
                      element-loading-text="加载中"
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
                        <span v-if="scope.row.customer && scope.row.customer.store_info">{{scope.row.customer.store_info.name}}</span>
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

                <el-table-column align="center" label="操作" width="120"
                                 class-name="small-padding">
                    <template slot-scope="scope">
                        <el-button type="primary" plain @click="showOrderDetail(scope.row)">订单处理</el-button>
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
  import {getOptions} from '@supplier/api/common'
  import {GetOrderLists} from '../../api/order'
  import {listHelper} from '@common/utils/listHelper'
  import { parseTime } from '@common/utils'

  export default {
    name: 'orderList',
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
        getDefaultDate,
        search: {
          datetime: getDefaultDate(),
          status: [],
          order_number: '',
          mobile: '',
          salesman_name: '',
          customer_name: ''
        },
        statusOptions: [],
        lists: [],
        page: 1,
        pageSizes: [10, 30, 50, 100],
        pageSize: 10,
        total: 0,
        list: {
          items: [0],
          total: null,
          listLoading: true
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-order_at'
        }
      }
    },
    created() {
      if (this.$store.getters.orderList) {
        return false
      }
      if (this.$route.params.status) {
        this.search.status.push(this.$route.params.status)
        localStorage.setItem('orderStatus', this.$route.params.status)
      } else {
        if (localStorage.getItem('orderStatus')) {
          this.search.status.push(localStorage.getItem('orderStatus'))
        }
      }
      getOptions('order', 'status').then(response => {
        this.statusOptions = response.data.options
        this._.remove(this.statusOptions, option => option.value === 'new')
      }).finally(() => {
        this._getOrderList()
      })
    },
    activated() {
      if (this.$store.getters.orderList) {
        if (this.$route.params.status) {
          this.search.status = []
          this.search.datetime = this.getDefaultDate()
          this.search.order_number = ''
          this.search.mobile = ''
          this.search.salesman_name = ''
          this.search.customer_name = ''
          this.search.status.push(this.$route.params.status)
          localStorage.setItem('orderStatus', this.$route.params.status)
        } else {
          if (localStorage.getItem('orderStatus')) {
            this.search.status.push(localStorage.getItem('orderStatus'))
          }
        }
        getOptions('order', 'status').then(response => {
          this.statusOptions = response.data.options
          this._.remove(this.statusOptions, option => option.value === 'new')
        }).finally(() => {
          this._getOrderList()
        })
        this.$store.dispatch('orderList', null)
      }
    },
    methods: {
      showOrderDetail(row) {
        this.$router.push({ name: 'order.detail', params: { id: row.id, tip: { content: row.order_number }}})
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this._getOrderList('page')
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this._getOrderList('page')
      },
      _getOrderList(action = 'init') {
        if (localStorage.getItem('status') && action === 'change') {
          localStorage.removeItem('status')
        }
        const more = {
          order_at_start: this.search.datetime ? this.search.datetime[0] : '',
          order_at_end: this.search.datetime ? this.search.datetime[1] : '',
          status: this.search.status,
          order_number: this.search.order_number,
          mobile: this.search.mobile,
          salesman_name: this.search.salesman_name,
          customer_name: this.search.customer_name
        }
        if (action !== 'page') {
          this.listQuery.page = 1
        }
        this.list.listLoading = true
        GetOrderLists(this.listQuery, more).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.orderPaginator)
        }).finally(() => {
          this.list.listLoading = false
        })
      }
    },
    destroyed() {
      if (localStorage.getItem('orderStatus')) {
        localStorage.removeItem('orderStatus')
      }
    }
  }

</script>
<style lang="scss" scoped>
    .title {
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }

    .el-button--warning {
        background-color: #F39800;
        border-color: #F39800;
    }

    .text-red {
        color: #F56C6C;
    }

    .text-warning {
        color: #F39800;
    }
</style>
