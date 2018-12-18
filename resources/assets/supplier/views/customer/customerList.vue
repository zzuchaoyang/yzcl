<template>
    <div class="app-container">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true" :model="search">
                        <el-form-item label="所在城市">
                            <el-select
                                    class="fl"
                                    clearable
                                    filterable
                                    remote
                                    :loading="loadingProvince"
                                    @clear="clearProvince"
                                    popper-class="select-dropdown"
                                    @change="getCityList(province_id)"
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
                                    :loading="loadingCity"
                                    :disabled="!cities.length"
                                    popper-class="select-dropdown"
                                    @change="_getCustomerList"
                                    v-model="search.city_id"
                                    placeholder="选择城市">
                                <el-option
                                        v-for="item in cities"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item.id">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="客户状态">
                            <el-select
                                    @change="_getCustomerList('change')"
                                    v-model="search.status"
                                    clearable
                                    placeholder="选择客户状态">
                                <el-option
                                        v-for="item in customerStatus"
                                        :key="item.value"
                                        :label="item.name"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="供货价级别">
                            <el-select
                                    v-model="search.supply_grade"
                                    @change="_getCustomerList"
                                    clearable
                                    placeholder="供货价级别">
                                <el-option
                                        v-for="item in priceLevel"
                                        :key="item.value"
                                        :label="item.name"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="客户编号">
                            <el-input
                                    v-model="search.mobile"
                                    @change="_getCustomerList"
                                    @keyup.enter="_getCustomerList"
                                    clearable
                                    placeholder="请输入客户编号"></el-input>
                        </el-form-item>
                        <el-form-item label="客户名称">
                            <el-input
                                    v-model="search.name"
                                    @change="_getCustomerList"
                                    @keyup.enter="_getCustomerList"
                                    clearable
                                    placeholder="请输入客户名称"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="searchCustomer">添加客户</el-button>
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
                <el-table-column align="center" label="客户编号">
                    <template slot-scope="scope">
                        <span class="text-warning">{{scope.row.mobile}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="客户名称">
                    <template slot-scope="scope">
                        <span v-if="scope.row.store_info">{{scope.row.store_info.name}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="所在城市">
                    <template slot-scope="scope">
                        <span>{{scope.row.city}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="合作开始时间" width="140">
                    <template slot-scope="scope">
                        <span v-if="scope.row.customerSupplier[0]">{{scope.row.customerSupplier[0].cooperationApplication.accepted_at}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="供货价级别">
                    <template slot-scope="scope">
                        <span v-if="scope.row.customerSupplier[0]">{{scope.row.customerSupplier[0].supply_grade}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="客户状态">
                    <template slot-scope="scope">
                        <span class="text-primary" v-if="scope.row.customerSupplier[0]">{{scope.row.customerSupplier[0].status_alias}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="操作" width="220" fixed="right">
                    <template slot-scope="scope">
                        <el-button type="primary" plain
                                   @click="showCustomerDetail(scope.row)">查看</el-button>
                        <el-button type="danger" plain
                                   @click="supplierActionApplication(scope.row,'暂停')"
                                   v-if="scope.row.customerSupplier[0].status === 'normal'">暂停</el-button>
                        <el-button type="primary" plain
                                   @click="supplierActionApplication(scope.row,'审核')"
                                   v-if="scope.row.customerSupplier[0].status === 'wait'&&scope.row.customerSupplier[0].cooperationApplication.sender_type==='customer'">审核</el-button>
                        <el-button type="danger" plain
                                   @click="supplierActionApplication(scope.row,'拒绝')"
                                   v-if="scope.row.customerSupplier[0].status === 'wait'&&scope.row.customerSupplier[0].cooperationApplication.sender_type==='customer'">拒绝</el-button>
                        <el-button type="primary" plain
                                   @click="supplierActionApplication(scope.row,'启用')"
                                   v-if="scope.row.customerSupplier[0].status === 'pause'">启用</el-button>
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
        <el-dialog
                v-el-drag-dialog
                title="查找客户"
                :visible.sync="dialogSearch"
                width="500px"
                class="order-dialog">
            <el-row class="order-dialog-search">
                <el-col :span="24" class="text-center">
                    <el-input v-model="search_customer_mobile" placeholder="请输入客户手机号码"></el-input>
                    <el-button type="primary" @click="findCustomer">查找</el-button>
                </el-col>
                <el-col :span="24">
                    <div class="order-dialog-nodata" v-show="nodata">
                        <span>{{ nodata_msg }}</span>
                    </div>
                    <div class="order-dialog-result" v-show="!nodata">
                        <div class="order-dialog-result-con">
                            <div class="result-con">
                                <div class="icon-con">
                                    <img class="avatar" :src="findData.currentData.avatar" alt="">
                                </div>
                                <div class="link-con text-right">
                                    <el-button type="text" @click="showCustomerDetail(findData.currentData, 'store')">查看详情<i class="el-icon-arrow-right"></i></el-button>
                                </div>
                                <div class="detail-con">
                                    <div class="shop">{{findData.currentData.store_info ? findData.currentData.store_info.name : '暂无'}}</div>
                                    <div class="address">
                                        <el-row>
                                            <el-col :span="5">联系人</el-col>
                                            <el-col :span="7">{{findData.currentData.name}}</el-col>
                                            <el-col :span="5">联系电话</el-col>
                                            <el-col :span="7">{{findData.currentData.mobile}}</el-col>
                                        </el-row>
                                        <el-row>
                                            <el-col :span="5">开业时间</el-col>
                                            <el-col :span="19">{{findData.currentData.store_info ? findData.currentData.store_info.opened_at : '暂无'}}</el-col>
                                        </el-row>
                                        <el-row>
                                            <el-col :span="5">所在地址</el-col>
                                            <el-col :span="19">{{findData.currentData.city ? findData.currentData.city : '暂无'}}</el-col>
                                        </el-row>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-con">
                                <el-row type="flex" justify="space-between">
                                    <el-col :span="24">
                                        <el-form ref="applyForm" :model="applyData" :rules="applyRules">
                                            <el-form-item label="设定供应商级别" prop="grade"
                                                          v-if="!findData.currentData.customerSupplier[0]">
                                                <el-select
                                                        v-model="applyData.grade"
                                                        placeholder="选择客户状态"
                                                        clearable>
                                                    <el-option
                                                            v-for="item in priceLevel"
                                                            :key="item.value"
                                                            :label="item.name"
                                                            :value="item.value">
                                                    </el-option>
                                                </el-select>
                                            </el-form-item>

                                            <el-form-item>
                                                <el-button type="primary" @click="apply"
                                                           v-if="!findData.currentData.customerSupplier[0]">申请合作</el-button>
                                                <el-button type="info" v-if="findData.currentData.customerSupplier[0] && findData.currentData.customerSupplier[0].status === 'wait'" disabled>等待合作中</el-button>
                                                <el-button type="info" v-if="findData.currentData.customerSupplier[0] && findData.currentData.customerSupplier[0].status === 'normal'" disabled>正常合作中</el-button>
                                                <el-button type="info" v-if="findData.currentData.customerSupplier[0] && findData.currentData.customerSupplier[0].status === 'reject'" disabled>已拒绝</el-button>
                                                <el-button type="info" v-if="findData.currentData.customerSupplier[0] && findData.currentData.customerSupplier[0].status === 'pause'" disabled>已暂停合作</el-button>
                                            </el-form-item>
                                        </el-form>
                                    </el-col>
                                </el-row>
                            </div>
                        </div>

                    </div>
                </el-col>
            </el-row>
        </el-dialog>
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
  import { GetCustomerLists, SupplierCooperation, SupplierActionApplication } from '../../api/customer'
  import { listHelper } from '@common/utils/listHelper'
  import { notify } from '@common/utils/helper'
  import { getOptions, areaList } from '@supplier/api/common'
  import elDragDialog from '@supplier/directive/el-dragDialog'

  export default {
    name: 'customerList',
    directives: { elDragDialog },
    data() {
      return {
        province_id: '',
        search: {
          status: '',
          supply_grade: '',
          name: '',
          mobile: '',
          city_id: '',
          from_home: ''
        },
        applyRules: {
          grade: [
            { required: true, message: '请设定供应商级别' }
          ]
        },
        cities: [],
        provinceList: [],
        customerStatus: [],
        priceLevel: [],
        dialogSearch: false,
        search_customer_mobile: '',
        nodata: true,
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
        findQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        findData: {
          items: null,
          total: null,
          listLoading: true,
          currentData: {
            customerSupplier: [
              {
                status_alias: '',
                supply_grade: ''
              }
            ]
          }
        },
        applyData: {
          grade: ''
        },
        userId: this.$store.state.user.user.id,
        is_apply: false,
        dialogGrade: false,
        gradeData: {
          grade: ''
        },
        approveData: {},
        nodata_msg: '查找客户结果',
        loadingProvince: false,
        loadingCity: false
      }
    },
    created() {
      if (this.$store.getters.customerList) {
        return false
      }
      if (this.$route.params.status) {
        this.search.status = this.$route.params.status
        this.search.from_home = true
        localStorage.setItem('status', this.$route.params.status)
      } else {
        if (localStorage.getItem('status')) {
          this.search.status = localStorage.getItem('status')
          this.search.from_home = true
        }
      }
      this._getCustomerList('init', 'created')
    },
    activated() {
      if (this.$store.getters.customerList) {
        if (this.$route.params.status) {
          this.search.status = this.$route.params.status
          this.search.from_home = true
          localStorage.setItem('status', this.$route.params.status)
          this.search.city_id = ''
          this.search.supply_grade = ''
          this.search.name = ''
          this.search.mobile = ''
        } else {
          if (localStorage.getItem('status')) {
            this.search.status = localStorage.getItem('status')
            this.search.from_home = true
          }
        }
        this.$store.dispatch('customerList', null)
        this._getCustomerList('init', 'created')
      } else {
        if (this.$store.getters.viewCustomerDetail) {
          this.dialogSearch = true
          this.$store.dispatch('viewCustomerDetail', null)
        }
      }
    },
    methods: {
      showCustomerDetail(row, action) {
        if (action) {
          this.dialogSearch = false
          this.$store.dispatch('viewCustomerDetail', 'close')
        }
        this.$router.push({ name: 'customer.detail', params: { id: row.id, tip: { content: row.mobile }}})
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this._getCustomerList('page')
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this._getCustomerList('page')
      },
      searchCustomer() {
        this.search_customer_mobile = ''
        this.applyData = {
          grade: ''
        }
        this.findData = {
          items: null,
          total: null,
          listLoading: true,
          currentData: {
            customerSupplier: [
              {
                status_alias: '',
                supply_grade: ''
              }
            ]
          }
        }
        this.nodata_msg = '查找客户结果'
        this.nodata = true
        this.dialogSearch = true
      },
      findCustomer() {
        const params = {
          mobile: this.search_customer_mobile,
          is_add: true
        }
        GetCustomerLists(this.findQuery, params).then(response => {
          listHelper.setList(this.findData, this.findQuery, response.data.customerList)
          if (this.findData.items.length) {
            this.$set(this.findData, 'currentData', this.findData.items[0])
            this.nodata = false
          } else {
            this.nodata = true
            this.nodata_msg = '暂未找到结果'
          }
        })
      },
      supplierActionApplication(row, action) {
        if (action === '审核') {
          this.approveData = this._.cloneDeep(row)
          this.dialogGrade = true
        } else {
          this.$confirm('此操作将会更改客户状态, 是否继续?', action + '客户状态', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            SupplierActionApplication(action, parseInt(row.customerSupplier[0].cooperation_application_id)).then((response) => {
              notify.success('操作成功')
              this._getCustomerList()
              if (action === '审核') {
                this.dialogGrade = true
              }
            })
          })
        }
      },
      submitGrade() {
        if (!this.gradeData.grade) {
          notify.warning('请选择供货价级别')
          return false
        }
        SupplierActionApplication('审核', parseInt(this.approveData.customerSupplier[0].cooperation_application_id), this.gradeData.grade).then((response) => {
          notify.success('操作成功')
          this._getCustomerList()
          this.dialogGrade = false
        })
      },
      _getCustomerList(action = 'init', life) {
        if (localStorage.getItem('status') && action === 'change') {
          localStorage.removeItem('status')
        }
        if (action !== 'page') {
          this.listQuery.page = 1
        }
        this.list.listLoading = true
        GetCustomerLists(this.listQuery, this.search).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.customerList)
        }).finally(() => {
          this.list.listLoading = false
          if (life === 'created') {
            getOptions('cooperation', 'status').then(response => {
              this.customerStatus = response.data.options
              this._.remove(this.customerStatus, option => option.value === 'new')
            }).finally(() => {
              getOptions('grade', 'supply_grade').then(response => {
                this.priceLevel = response.data.options
                this._.remove(this.priceLevel, option => option.value === 'new')
              }).finally(() => {
                this.getProvinceList()
              })
            })
          }
        })
      },
      getProvinceList() {
        areaList({ level: 0 }).then(response => {
          this.provinceList = response.data.areaList
        })
      },
      clearProvince() {
        this.cities = []
        this.search.city_id = ''
      },
      getCityList(province_id) {
        this.loadingCity = true
        this.cities = []
        this.search.city_id = ''
        if (province_id) {
          areaList({ level: 1, parent_id: province_id }).then(response => {
            this.cities = response.data.areaList
          }).finally(() => {
            this.loadingCity = false
          })
        }
      },
      apply() {
        this.$refs.applyForm.validate((valid) => {
          if (valid) {
            SupplierCooperation(this.findData.currentData.id, parseInt(this.userId), this.applyData.grade).then((response) => {
              if (response.data.supplierCooperationApplication) {
                if (response.data.supplierCooperationApplication.status === 'wait') {
                  this.findData.currentData.customerSupplier.push({
                    status: response.data.supplierCooperationApplication.status
                  })
                  notify.success('操作成功')
                  this._getCustomerList()
                }
              }
            })
          } else {
            notify.warning('请选择供应商级别')
          }
        })
      }
    },
    destroyed() {
      if (localStorage.getItem('status')) {
        localStorage.removeItem('status')
      }
    }
  }

</script>
<style scoped>
    .text-primary{
        color: #67C23A;
    }
    .text-warning{
        color: #F39800;
    }
    .el-item-grade{
        margin-top: 35px;
        margin-bottom: 0;
    }
</style>
