<template>
    <div class="app-container">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true" :model="more">
                        <el-form-item>
                            <el-button type="primary" @click.stop="creatPriceAdjustmentBill">创建调价单</el-button>
                        </el-form-item>
                        <el-form-item label="制单日期">
                            <el-date-picker
                                    v-model="created_date"
                                    type="daterange"
                                    align="right"
                                    value-format="yyyy-MM-dd"
                                    start-placeholder="选择开始时间"
                                    end-placeholder="选择结束时间"
                                    @change="getList()">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item label="调价单编码">
                            <el-input
                                    clearable
                                    v-model="more.code"
                                    @keyup.enter="getList()"
                                    @change="getList()"
                                    placeholder="请输入调价单编码"></el-input>
                        </el-form-item>
                        <el-form-item label="商品条码">
                            <el-input
                                    clearable
                                    v-model="more.bar_code"
                                    @keyup.enter="getList()"
                                    @change="getList()"
                                    placeholder="请输入商品条码"></el-input>
                        </el-form-item>
                        <el-form-item label="商品名称">
                            <el-input
                                    clearable
                                    v-model="more.product_name"
                                    @keyup.enter="getList()"
                                    @change="getList()"
                                    placeholder="请输入商品名称"></el-input>
                        </el-form-item>
                        <el-form-item label="调价单状态">
                            <el-select
                                    clearable
                                    v-model="more.status"
                                    @change="getList()"
                                    placeholder="选择调价单状态">
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
            </el-row>
            <el-table :data="lists.items"
                      fit
                      :height="this.$store.getters.tableHeight"
                      v-loading="lists.listLoading"
                      highlight-current-row>
                <el-table-column
                        label="序号"
                        type="index"
                        width="50"
                        align="center">
                </el-table-column>
                <el-table-column align="center" label="调价单编码">
                    <template slot-scope="scope">
                        <span>{{scope.row.code}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="制单日期">
                    <template slot-scope="scope">
                        <span >{{dateFormat(scope.row.created_at)}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="生效日期">
                    <template slot-scope="scope">
                        <span>{{dateFormat(scope.row.effective_at)}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="调价商品数量（SKU）" width="150">
                    <template slot-scope="scope">
                        <span>{{scope.row.number}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="调价单状态">
                    <template slot-scope="scope">
                        <span>{{scope.row.status}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="操作" width="300"
                                 class-name="small-padding">
                    <template slot-scope="scope">
                        <el-button type="primary" plain @click.stop="toView(scope.row)">查看</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-row type="flex" justify="end" class="pagination-container">
                <el-pagination background
                               @size-change="handleSizeChange"
                               @current-change="handleCurrentChange"
                               :current-page="paginator.page"
                               :page-sizes="[10, 20, 30, 50]"
                               :page-size="paginator.limit"
                               layout="total, sizes, prev, pager, next, jumper"
                               :total="lists.total">
                </el-pagination>

            </el-row>
        </el-card>
    </div>
</template>

<script>
  import { productPriceAdjustmentPaginator } from '@supplier/api/product'
  import { listHelper } from '@common/utils/listHelper'
  import { parseTime } from '@common/utils'

  export default {
    name: 'productPriceAdjustment',
    data() {
      const getDefaultDate = () => {
        const end = new Date()
        const start = new Date()
        start.setTime(start.getTime() - 3600 * 1000 * 24 * 6)
        const format_start = parseTime(start).slice(0, 10)
        const format_end = parseTime(end).slice(0, 10)
        const defaultDate = [format_start, format_end]
        return defaultDate
      }
      return {
        created_date: getDefaultDate(),
        statusOptions: [
          { name: '全部状态', value: '' },
          { name: '未审核', value: '未审核' },
          { name: '已审核', value: '已审核' },
          { name: '已作废', value: '已作废' }
        ],
        lists: {
          items: [],
          total: null,
          listLoading: false
        },
        paginator: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        more: {
          start_at: '', // 制单日期
          end_at: '', // 生效时间
          code: '', // 调价单编码
          bar_code: '', // 调价商品条码
          product_name: '', // 调价商品名称
          status: '' // 调价单状态
        }
      }
    },
    mounted() {
      this.getList()
    },
    activated() {
      if (this.$store.getters.priceAdjustment) {
        this.getList()
        this.$store.dispatch('priceAdjustment', null)
      }
    },
    methods: {
      dateFormat(time) {
        let date = null
        if (_.isDate(time)) {
          date = time
        } else {
          const str = time.replace(/-/g, '/')
          date = new Date(str)
        }

        const year = date.getFullYear()
        const month = date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1
        const day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate()
        const hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours()
        const minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()
        const seconds = date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds()
        // 拼接
        return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes
      },
      getList(tag) {
        if (tag !== 'noResetPage') {
          this.paginator.page = 1
        }
        this.more.start_at = (this.created_date && this.created_date[0]) || ''
        this.more.end_at = (this.created_date && this.created_date[1]) || ''
        this.lists.listLoading = true
        productPriceAdjustmentPaginator(this.paginator, this.more).then(response => {
          listHelper.setList(this.lists, this.paginator, response.data.productPriceAdjustmentPaginator)
        }).finally(() => {
          this.lists.listLoading = false
        })
      },
      handleSizeChange(val) {
        this.paginator.limit = val
        this.getList()
      },
      handleCurrentChange(val) {
        this.paginator.page = val
        this.getList('noResetPage')
      },
      creatPriceAdjustmentBill() {
        // 创建调价单
        this.$router.push({
          name: 'productCreatBill'
        })
      },
      toView(row) {
        // 查看
        this.$router.push({
          name: 'productViewBill',
          params: {
            id: row.id,
            tip: { content: row.code }
          }
        })
      }
    }
  }

</script>
<style lang="scss" scoped>
</style>
