<template>
    <div class="app-container" style="overflow-y: auto" :style="{height: this.$store.getters.tableHeight + 220 + `px`}">
        <el-card style="height: 100%">
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true">
                        <el-form-item label="统计时间">
                            <el-date-picker
                                    v-model="datetime"
                                    type="daterange"
                                    align="right"
                                    @change="getList('refresh')"
                                    value-format="yyyy-MM-dd"
                                    start-placeholder="开始日期"
                                    end-placeholder="结束日期">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item label="所在区/县">
                            <el-select
                                    class="fl"
                                    clearable
                                    filterable
                                    remote
                                    :disabled="!provinceList.length"
                                    popper-class="select-dropdown"
                                    @change="getCity(province_id, 'change')"
                                    v-model="province_id"
                                    placeholder="选择省份">
                                <el-option
                                        v-for="item in provinceList"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                            <el-select
                                    class="fl"
                                    clearable
                                    filterable
                                    remote
                                    :disabled="!cities.length"
                                    popper-class="select-dropdown"
                                    @change="getArea(city_id, 'change')"
                                    v-model="city_id"
                                    placeholder="选择城市">
                                <el-option
                                        v-for="item in cities"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                            <el-select
                                    class="fl"
                                    clearable
                                    filterable
                                    remote
                                    :disabled="!areas.length"
                                    popper-class="select-dropdown"
                                    @change="getList('refresh')"
                                    v-model="area_id"
                                    placeholder="选择区/县">
                                <el-option
                                        v-for="item in areas"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="供货价级别">
                            <el-select
                                    v-model="more.supply_grade"
                                    placeholder="请选择供货价级别"
                                    @change="getList('refresh')"
                                    clearable>
                                <el-option
                                        v-for="item in gradeOptions"
                                        :key="item.value"
                                        :label="item.name"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="客户编号">
                            <el-input v-model="more.mobile"
                                      placeholder="请输入客户编号"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')"
                                      clearable/>
                        </el-form-item>
                        <el-form-item label="客户名称">
                            <el-input v-model="more.name"
                                      placeholder="请输入客户名称"
                                      @keyup.enter="getList('refresh')"
                                      @change="getList('refresh')"
                                      clearable/>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" :loading="downloadLoading" @click="handleDownload" >导出Excel表格</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-table :data="list.items"
                      v-loading="list.listLoading"
                      element-loading-text="Loading"
                      fit
                      :height="this.$store.getters.tableHeight"
                      highlight-current-row>
                <el-table-column align="center" label='客户编号'>
                    <template slot-scope="scope">
                        <span>{{ scope.row.mobile }}</span>
                    </template>
                </el-table-column>

                <el-table-column align="center" label='客户名称'>
                    <template slot-scope="scope">
                        <span>{{ scope.row.store_info && scope.row.store_info.name }}</span>
                    </template>
                </el-table-column>

                <el-table-column align="center" label='所在城市'>
                    <template slot-scope="scope">
                        <span>{{ scope.row.city || ''}}</span>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="合作开始时间">
                    <template slot-scope="scope">
                        <span>{{scope.row.customerSupplier && scope.row.customerSupplier[0].cooperationApplication.accepted_at}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订单数量">
                    <template slot-scope="scope">
                        <span>{{scope.row.order_count || 0}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订货总数量">
                    <template slot-scope="scope">
                        <span>{{scope.row.order_number || 0}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订货总额（元）">
                    <template slot-scope="scope">
                        <span>{{scope.row.order_price || 0}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="实际发货数量">
                    <template slot-scope="scope">
                        <span>{{scope.row.real_order_number || 0}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="实际销售总额（元）">
                    <template slot-scope="scope">
                        <span>{{scope.row.real_order_price || 0}}</span>
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
  import { parseTime } from '@common/utils'
  import { reportCustomer } from '../../api/report'
  import { getOptions } from '../../api/common'
  import { listHelper } from '@common/utils/listHelper'
  import { areaList } from '../../api/common'
  export default {
    name: 'reportCustomer',
    data() {
      var getDefaultDate = () => {
        const end = new Date()
        const start = new Date()
        start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
        end.setTime(end.getTime() - 3600 * 1000 * 24 * 1)
        const format_start = parseTime(start).slice(0, 10)
        const format_end = parseTime(end).slice(0, 10)
        const defaultDate = [format_start, format_end]
        return defaultDate
      }
      return {
        datetime: getDefaultDate(),
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
          start_at: '',
          end_at: '',
          city_id: '367396',
          supply_grade: null,
          mobile: null,
          name: null,
          status: 'normal',
          statics: true
        },
        gradeOptions: [],
        category: 'grade', // 供货级别参数
        type: 'supply_grade',
        downloadLoading: false,
        province_id: '',
        city_id: '',
        area_id: '',
        provinceList: [],
        cities: [],
        areas: []
      }
    },
    created() {
      this.getList()
    },
    methods: {
      // 获取列表
      getList(action = 'init') {
        this.list.listLoading = true
        this.area_id ? this.more.city_id = this.area_id : ''
        if (this.datetime && this.datetime.length) {
          this.more.start_at = this.datetime[0]
          this.more.end_at = this.datetime[1]
        } else {
          this.more.start_at = ''
          this.more.end_at = ''
        }
        if (action === 'refresh') {
          this.listQuery.page = 1
        }
        reportCustomer(this.listQuery, this.more).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.customerList)
        }).finally(() => {
          if (action === 'init') {
            // 获取供货级别列表
            getOptions(this.category, this.type).then(response => {
              this.gradeOptions = response.data.options
            }).finally(() => {
              this.getProvinceList()
            })
          }
        })
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this.getList('change')
        document.querySelector('.el-table__body-wrapper').scrollTop = 0
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this.getList('change')
        document.querySelector('.el-table__body-wrapper').scrollTop = 0
      },
      // 获取区县列表
      // 获取一级数据
      getProvinceList(action = 'init') {
        areaList({ level: 0 }).then(response => {
          this.provinceList = response.data.areaList
          this.province_id = '367395'
        }).finally(() => {
          this.getCity(this.province_id)
        })
      },
      // 获取二级数据
      getCity(province_id, action = 'init') {
        if (!province_id || action !== 'init') {
          this.city_id = ''
          this.area_id = ''
          this.more.city_id = ''
          this.cities = []
          this.areas = []
          if (!province_id) {
            this.getList('refresh')
            return false
          }
        }
        areaList({ level: 1, parent_id: province_id }).then(response => {
          this.cities = response.data.areaList
        }).finally(() => {
          if (action === 'init') {
            this.city_id = '367396'
            this.getArea(this.city_id)
          }
        })
      },
      // 获取三级数据
      getArea(city_id, action = 'init') {
        if (!city_id || action !== 'init') {
          this.area_id = ''
          this.more.city_id = city_id
          this.areas = []
          if (!city_id) {
            this.getList('refresh')
            return false
          }
        }
        areaList({ level: 2, parent_id: city_id }).then(response => {
          this.areas = response.data.areaList
        }).finally(() => {
          if (action !== 'init') {
            this.getList('refresh')
          }
        })
      },
      // 导出Excel表格
      handleDownload() {
        this.downloadLoading = true
        setTimeout(() => {
          const query = {
            page: 1,
            limit: this.list.total,
            sort: '-id'
          }
          reportCustomer(query, this.more).then(response => {
            this.list = response.data.customerList
          }).finally(() => {
            import('@supplier/vendor/Export2Excel').then(excel => {
              const tHeader = ['客户编号', '客户名称', '所在城市', '合作开始时间', '订单数量', '订货总数量', '订货总额（元）', '实际发货数量', '实际销售总额（元）']
              const filterVal = ['mobile', 'name', 'city', 'accepted_at', 'order_count', 'order_number', 'order_price', 'real_order_number', 'real_order_price']
              const list = this.list.items
              const data = this.formatJson(filterVal, list)
              excel.export_json_to_excel({
                header: tHeader,
                data,
                filename: '客户销售统计报表',
                autoWidth: this.autoWidth
              })
              this.downloadLoading = false
            })
          })
        }, 500)
      },
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'accepted_at') {
            return v.customerSupplier[0] && v.customerSupplier[0].cooperationApplication.accepted_at || ''
          } else if (j === 'name') {
            return v.store_info && v.store_info.name || ''
          } else {
            return v[j]
          }
        }))
      }
    }
  }
</script>
<style lang="scss" scoped>
</style>
