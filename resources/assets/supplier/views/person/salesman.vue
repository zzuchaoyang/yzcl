<template>
    <div class="app-container">
        <div class="el-card is-always-shadow product-input-content" :style="{height: this.$store.getters.tableHeight + 200 +'px'}">
            <el-row class="height100">
                <el-col :xs="24" :sm="24" :md="5" :lg="5" class="height100">
                    <div class="grid-content content-left height100 scroller-y">
                        <tree-level :treeData="brandList"
                                    @clickItem="clickItem"
                                    :addLevel="addBrandOrg"
                                    :brandTitle="brandTitle"
                                    :brandType="brandType"></tree-level>
                        <!--<tree-folding-->
                        <!--:data="brandList"-->
                        <!--:clickItem="clickItem"-->
                        <!--:addLevel="addBrandOrg"-->
                        <!--:brandTitle="brandTitle"-->
                        <!--:brandType="brandType"-->
                        <!--&gt;</tree-folding>-->
                    </div>
                </el-col>
                <el-col ::xs="24" :sm="24" :md="19" :lg="19" class="height100">
                    <div class="grid-content content-right height100 scroller-y">
                        <div v-if="brandList.length">
                            <div class="title-box bg-white flex-box">
                                <div>
                                    <span class="label">当前部门:</span>
                                    <span class="value">{{ currentData.name }}</span>
                                </div>
                                <div v-if="currentData.is_tip || currentLevel == '四级'">
                                    <el-button type="text" @click.stop="addBrandPerson(currentData)"> +新增人员</el-button>
                                </div>
                                <div v-if="(!currentData.is_tip) && currentLevel != '四级' ">
                                    <el-button type="text" @click.stop="addBrandOrg(currentData, currentLevel)"> +新增{{ currentLevel }}</el-button>
                                </div>
                            </div>

                            <div class="brand-box bg-white" v-if="currentData.children.length && !currentData.is_tip">
                                <span>下级部门:</span>
                                <div class="brand-value">
                                    <el-button type="info" v-for="item in currentData.children" :key="item.id" size="medium" @click="clickItem(item)">
                                        {{item.name}}
                                    </el-button>
                                </div>
                            </div>

                            <div class="brand-info-box bg-white">
                                <div class="box-title flex-box">
                                    <div>
                                        基本信息
                                    </div>
                                    <div>
                                        <el-button type="text" @click="updateOrg">保存</el-button>
                                    </div>
                                </div>

                                <el-form ref="form" :model="formData" :rules="orgRules" label-width="130px" class="pd15">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <div class="grid-content" prop="name">
                                                <el-form-item label="部门名称" prop="name">
                                                    <el-input v-model="formData.name"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="部门级别">
                                                    <el-input v-model="formData.rank" disabled></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="上级部门">
                                                    <el-input v-model="formData.parent_name" disabled></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="部门性质" prop="type">
                                                    <el-select v-model="formData.type" clearable placeholder="请选择">
                                                        <el-option label="部门" value="部门"></el-option>
                                                        <el-option label="人员" value="人员"></el-option>
                                                    </el-select>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="是否末级" prop="is_tip">
                                                    <el-switch
                                                            v-model="formData.is_tip">
                                                    </el-switch>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="是否启用" prop="status">
                                                    <el-switch
                                                            v-model="formData.status">
                                                    </el-switch>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                    </el-row>
                                </el-form>
                            </div>

                            <div class="product-list bg-white">
                                <div class="box-title">
                                    司机列表
                                </div>
                                <div class="pd15">
                                    <el-form ref="form" :model="sortData" label-width="90px" class="pdt15 text-right" :inline="true">
                                        <el-form-item label="状态">
                                            <el-select v-model="sortData.status"
                                                       clearable
                                                       placeholder="请选择"
                                                       @change='_getDriverList'>
                                                <el-option label="已开启" value="已开启"></el-option>
                                                <el-option label="已暂停" value="已暂停"></el-option>
                                            </el-select>
                                        </el-form-item>
                                        <el-form-item prop="name">
                                            <el-input v-model="sortData.name"
                                                      placeholder="请输入客户名称"
                                                      @keyup.enter="_getDriverList"
                                                      @change="_getDriverList"
                                                      clearable></el-input>
                                        </el-form-item>
                                    </el-form>
                                    <template v-if="is_driver_list">
                                        <el-table
                                                :data="list.items"
                                                v-loading="list.listLoading"
                                                element-loading-text="加载中"
                                                fit
                                                :height="this.$store.getters.tableHeight"
                                                highlight-current-row>
                                            <el-table-column
                                                    type="index"
                                                    label="序号"
                                                    width="50"
                                                    align="center">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="name"
                                                    align="center"
                                                    label="司机姓名">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="id_card"
                                                    align="center"
                                                    label="身份证号">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="name"
                                                    align="center"
                                                    label="联系电话">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="hiredate_on"
                                                    align="center"
                                                    label="入职时间">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="license"
                                                    align="center"
                                                    label="常用车牌">
                                            </el-table-column>
                                            <el-table-column align="center" label="操作" width="220">
                                                <template slot-scope="scope">
                                                    <el-button type="primary" plain @click="viewDetail(scope.row)">查看配送记录</el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </template>
                                    <template v-if="!is_driver_list">
                                        <el-table
                                                :data="list.items"
                                                v-loading="list.listLoading"
                                                element-loading-text="加载中"
                                                fit
                                                highlight-current-row>
                                            <el-table-column
                                                    type="index"
                                                    label="序号"
                                                    width="50"
                                                    align="center">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="name"
                                                    align="center"
                                                    label="司机姓名">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="id_card"
                                                    align="center"
                                                    label="身份证号">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="name"
                                                    align="center"
                                                    label="联系电话">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="hiredate_on"
                                                    align="center"
                                                    label="入职时间">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="license"
                                                    align="center"
                                                    label="常用车牌">
                                            </el-table-column>
                                            <el-table-column align="center" label="操作" width="220">
                                                <template slot-scope="scope">
                                                    <el-button type="primary" plain @click="viewOrder(scope.row)">查看订单</el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </template>
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
                                </div>

                            </div>
                        </div>
                        <div v-if="!brandList.length">
                            <div class="title-box bg-white flex-box">
                                <div>
                                    <span class="label">还未添加组织架构</span>
                                </div>
                            </div>
                            <div class="brand-box">
                                <div class="brand-value">
                                    <el-button type="primary" @click.stop="addBrandOrg(null, '一级')">新增一级</el-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-col>
            </el-row>

            <el-dialog class="order-dialog" title="新建部门" :visible.sync="brandOrgDialog" width="40%">
                <el-form ref="orgForm" :model="brandOrgData" label-width="100px" :rules="orgRules">
                    <el-form-item label="部门级别" prop="rank">
                        <el-input type="text" v-model="brandOrgData.rank" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="上级部门">
                        <el-input v-model="brandOrgData.parent_name" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="部门性质" prop="type">
                        <el-select v-model="brandOrgData.type" clearable placeholder="请选择员工类型">
                            <el-option label="部门" value="部门"></el-option>
                            <el-option label="人员" value="人员"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="部门名称" prop="name">
                        <el-input v-model="brandOrgData.name" placeholder="请输入部门名称"></el-input>
                    </el-form-item>
                    <el-form-item label="是否末级" prop="is_tip">
                        <el-switch
                                v-model="brandOrgData.is_tip">
                        </el-switch>
                    </el-form-item>
                    <el-form-item label="是否启用" prop="status">
                        <el-switch
                                v-model="brandOrgData.status">
                        </el-switch>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="cancelBrandOrg">取 消</el-button>
                    <el-button type="primary" @click="confirmBrandOrg">确 定</el-button>
                </div>
            </el-dialog>


            <el-dialog class="order-dialog" title="新增员工" :visible.sync="brandPersonDialog" width="40%">
                <el-form ref="personForm" :rules="personRules" :model="brandPersonData" label-width="100px">
                    <el-form-item label="所属部门">
                        <el-input type="text" v-model="brandPersonData.parent_name" disabled></el-input>
                        <!--<el-row>-->
                        <!--<el-col :span="8">-->
                        <!--<el-input type="text" v-model="brandOrgData.rank"></el-input>-->
                        <!--</el-col>-->
                        <!--<el-col :span="8">-->
                        <!--<el-select v-model="addBrandData.brand.super2" clearable placeholder="请选择">-->
                        <!--<el-option-->
                        <!--v-for="item in brandLevelOptions"-->
                        <!--:key="item.value"-->
                        <!--:label="item.label"-->
                        <!--:value="item.value">-->
                        <!--</el-option>-->
                        <!--</el-select>-->
                        <!--</el-col>-->
                        <!--<el-col :span="8">-->
                        <!--<el-select v-model="addBrandData.brand.super2" clearable placeholder="请选择">-->
                        <!--<el-option-->
                        <!--v-for="item in brandLevelOptions"-->
                        <!--:key="item.value"-->
                        <!--:label="item.label"-->
                        <!--:value="item.value">-->
                        <!--</el-option>-->
                        <!--</el-select>-->
                        <!--</el-col>-->
                        <!--</el-row>-->
                    </el-form-item>
                    <el-form-item label="员工姓名" prop="name">
                        <el-input v-model="brandPersonData.name" placeholder="员工姓名"></el-input>                    </el-form-item>
                    <el-form-item label="身份证号码">
                        <el-input v-model="brandPersonData.id_card" placeholder="请输入身份证号码"></el-input>                    </el-form-item>
                    <el-form-item label="联系电话" prop="mobile">
                        <el-input v-model="brandPersonData.mobile" placeholder="请输入联系电话"></el-input>                    </el-form-item>
                    <el-form-item label="入职时间" prop="hiredate_on">
                        <el-date-picker
                                v-model="brandPersonData.hiredate_on"
                                type="date"
                                placeholder="年-月-日">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="常用车牌号" prop="license">
                        <el-input v-model="brandPersonData.license" placeholder="请输入常用车牌号"></el-input>
                    </el-form-item>
                    <el-form-item label="初始密码" prop="password">
                        <el-input v-model="brandPersonData.password" placeholder="请输入初始密码"></el-input>
                    </el-form-item>
                    <el-form-item label="是否启用" prop="status">
                        <el-switch
                                v-model="brandPersonData.status">
                        </el-switch>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="cancelBrandPerson">取 消</el-button>
                    <el-button type="primary" @click="confirmBrandPerson">确 定</el-button>
                </div>
            </el-dialog>


        </div>
    </div>
</template>

<script>
  import TreeLevel from "@supplier/components/treeLevel"
  import { AddOrg, GetOrgList, AddDriver, GetDriverList } from '../../api/driver'
  import { listHelper } from '@common/utils/listHelper'
  export default {
    components: { TreeLevel },
    data() {
      var validateTel = (rule, value, callback) => {
        if (!value) {
          // 验证空手机号
          callback(new Error('请输入手机号'))
        } else if (!value && rule.field === 'emergency_phone') {
          // 不验证紧空急联系人电话
          callback()
        } else {
          if (!(/^1[34578]\d{9}$/.test(value))) {
            callback(new Error('请输入正确的联系电话'))
          }
          callback()
        }
      }
      return {
        validateTel,
        orgRules: {
          name: [
            { required: true, message: '请输入部门名称', trigger: 'blur' }
          ],
          type: [
            { required: true, message: '请输入部门性质', trigger: 'blur' }
          ],
          is_tip: [
            { required: true }
          ],
          status: [
            { required: true }
          ]
        },
        personRules: {
          name: [
            { required: true, message: '请输入员工姓名', trigger: 'blur' }
          ],
          mobile: [
            { required: true, message: '请输入联系电话', trigger: 'blur' },
            { validator: validateTel, trigger: ['blur'] }
          ],
          hiredate_on: [
            { required: true, message: '请选择入职时间', trigger: 'blur' }
          ],
          license: [
            { required: true, message: '请选择入车牌号', trigger: 'blur' }
          ],
          password: [
            { required: true, message: '请选择入初始密码', trigger: 'blur' }
          ],
          status: [
            { required: true }
          ]
        },
        formData: {},
        sortData: {
          status: '已开启',
          name: ''
        },
        formLabelWidth: '120px',
        brandOrgDialog: false,
        brandPersonDialog: false,
        brandList: [],
        brandTitle: '组织架构',
        brandType: '部门',
        is_driver_list: true,
        brandOrgData: {
          rank: '',
          type: '',
          name: '',
          is_tip: 0,
          status: 0,
          parent_id: 0
        },
        brandPersonData: {
          name: '',
          id_card: '',
          mobile: '',
          license: '',
          status: null,
          supplier_org_id: '',
          hiredate_on: ''
        },
        currentLevel: '一级',
        currentData: {
          id: '',
          name: '',
          rank: '',
          children: [],
          parent_id: '',
          is_tip: null,
          status: null,
          type: ''
        },
        brand_level: ['一级', '二级', '三级', '四级'],
        list: {
          items: null,
          total: null,
          listLoading: true
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        }
      }
    },
    component: {
      TreeLevel
    },
    created() {
      GetOrgList(true).then((result) => {
        this.brandList = result.data.supplierOrgList
        if (this.brandList.length) {
          this.currentData = this.brandList[0]
          this.$set(this.currentData, 'parent_name', '无')
          this.formData = this._.clone(this.currentData)
          this._getDriverList()
        }
      })
    },
    methods: {
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`)
      },
      handleCurrentChange(val) {
        console.log(`当前页: ${val}`)
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this._getDriverList()
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this._getDriverList()
      },
      viewDetail(data) {
        this.is_driver_list = false
      },
      viewOrder(data) {
        console.log(data)
      },
      deliverRecord(data) {
        console.log(data)
      },
      addBrandPerson(item) {
        this.brandPersonDialog = true
        this.brandPersonData.parent_name = item.name
        this.brandPersonData.supplier_org_id = item.id
      },
      cancelBrandPerson() {
        this.brandPersonData = {
          name: '',
          id_card: '',
          mobile: '',
          license: '',
          status: null,
          supplier_org_id: '',
          hiredate_on: ''
        }
        this.brandPersonDialog = false
        console.log(this.currentLevel)
      },
      confirmBrandPerson() {
        this.$refs.personForm.validate((valid) => {
          if (valid) {
            AddDriver(this.brandPersonData).then((result) => {
              if (this.currentLevel === '二级') {
                this.findCurrentBrant(1, this.brandPersonData.supplier_org_id, result.data.storeDriver)
              } else if (this.currentLevel === '三级') {
                this.findCurrentBrant(2, this.brandPersonData.supplier_org_id, result.data.storeDriver)
              } else if (this.currentLevel === '四级') {
                this.findCurrentBrant(3, this.brandPersonData.supplier_org_id, result.data.storeDriver)
              }
              this.brandPersonData = {
                name: '',
                id_card: '',
                mobile: '',
                license: '',
                status: null,
                supplier_org_id: '',
                hiredate_on: ''
              }
              this.brandPersonDialog = false
              this._getDriverList()
            })
          } else {
            return false
          }
        })
      },
      addBrandOrg(item, level) {
        this.brandOrgDialog = true
        this.brandOrgData.rank = level
        this.currentLevel = level
        if (item) {
          this.brandOrgData.parent_name = item.name
          this.brandOrgData.parent_id = item.id
        } else {
          this.currentData = {
            id: '',
            name: '',
            rank: '',
            children: [],
            parent_id: '',
            is_tip: null,
            status: null
          }
          this.brandOrgData.rank = '一级'
          this.brandOrgData.parent_name = '无'
          this.brandOrgData.parent_id = 0
        }
      },
      cancelBrandOrg() {
        this.brandOrgData = {
          rank: '',
          type: '',
          name: '',
          is_tip: 0,
          status: 0,
          parent_id: 0
        }
        this.brandOrgDialog = false
      },
      findCurrentBrant(level, id, data, action) {
        let curIdx
        const that = this
        if (level === 1) {
          curIdx = that._.findIndex(that.brandList, function(o) { return o.id === id })
          if (curIdx !== -1) {
            if (action === 'update') {
              that._.forIn(data, (val, key) => {
                if (key !== 'children') {
                  that.brandList[curIdx][key] = val
                }
              })
            } else {
              that.brandList[curIdx].children.push(data)
            }
          }
        } else if (level === 2) {
          that._.each(that.brandList, function(item, index) {
            curIdx = that._.findIndex(item.children, function(o) { return o.id === id })
            if (curIdx !== -1) {
              if (action === 'update') {
                that._.forIn(data, (val, key) => {
                  if (key !== 'children') {
                    item.children[curIdx][key] = val
                  }
                })
              } else {
                item.children[curIdx].children.push(data)
              }
            }
          })
        } else if (level === 3) {
          that._.each(that.brandList, function(item, index) {
            if (item.children && item.children.length) {
              that._.each(item.children, function(obj, i) {
                curIdx = that._.findIndex(obj.children, function(o) { return o.id === id })
                if (curIdx !== -1) {
                  if (action === 'update') {
                    that._.forIn(data, (val, key) => {
                      if (key !== 'children') {
                        obj.children[curIdx][key] = val
                      }
                    })
                  } else {
                    obj.children[curIdx].children.push(data)
                  }
                }
              })
            }
          })
        }
      },
      confirmBrandOrg(item) {
        if (this.brandOrgData.is_tip && this.brandOrgData.type !== '人员') {
          this.$message({
            message: '部门设置为末级时，请将部门性质设置为人员',
            type: 'warning'
          })
          return false
        }
        this.$refs.orgForm.validate((valid) => {
          if (valid) {
            AddOrg(this.brandOrgData).then((result) => {
              if (this.currentLevel === '一级') {
                this.brandList.push(result.data.storeSupplierOrg)
              } else if (this.currentLevel === '二级') {
                this.findCurrentBrant(1, this.currentData.id, result.data.storeSupplierOrg)
              } else if (this.currentLevel === '三级') {
                this.findCurrentBrant(2, this.currentData.id, result.data.storeSupplierOrg)
              }
              this.brandOrgData = {
                rank: '',
                type: '',
                name: '',
                is_tip: 0,
                status: 0,
                parent_id: 0
              }
              this.brandOrgDialog = false
            })
          } else {
            return false
          }
        })
      },
      clickItem(item) {
        if (!item) {
          this.currentLevel = '一级'
          this.$set(this.currentData, 'parent_name', '无')
          this.formData = this._.clone(this.currentData)
        } else {
          this.currentData = item
          if (item.rank === '一级') {
            this.$set(this.currentData, 'parent_name', '无')
          } else {
            this.$set(this.currentData, 'parent_name', item.parent.name)
          }
          this.formData = this._.clone(this.currentData)
          this.currentLevel = this.brand_level[item._level]
          this._getDriverList()
        }
      },
      _getDriverList() {
        const more = {
          supplier_org_id: this.currentData.id,
          name: this.sortData.name,
          status: this.sortData.status ? this.sortData.status : '不限'
        }
        this.list.listLoading = true
        GetDriverList(this.listQuery, more).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.driverPaginator)
          this.list.listLoading = false
        })
      },
      updateOrg() {
        if (this.formData.is_tip && this.formData.type !== '人员') {
          this.$message({
            message: '部门设置为末级时，请将部门性质设置为人员',
            type: 'warning'
          })
          return false
        }
        this.$refs.form.validate((valid) => {
          if (valid) {
            AddOrg(this.formData).then((result) => {
              if (this.formData.rank === '一级') {
                this.findCurrentBrant(1, this.formData.id, result.data.storeSupplierOrg, 'update')
              } else if (this.formData.rank === '二级') {
                this.findCurrentBrant(2, this.formData.id, result.data.storeSupplierOrg, 'update')
              } else if (this.formData.rank === '三级') {
                this.findCurrentBrant(3, this.formData.id, result.data.storeSupplierOrg, 'update')
              }
            })
          } else {
            return false
          }
        })
      }
    }
  }
</script>
<style lang="scss" scoped>
    .height100{
        height: 100%;
    }
    .scroller-y{
        overflow-y: auto;
    }
    .product-input-content {
        .pd15 {
            padding: 15px;
        }
        .pdr15 {
            padding-right: 15px;
        }
        .pdt15 {
            padding-top: 15px;
        }
        .pdr20 {
            padding-right: 20px;
        }
        .text-right{
            text-align: right;
        }
        .content-left {
            padding: 10px 15px 15px 15px;
        }
        .content-right {
            background-color: #fcfcfc;
            padding: 0 0 0 10px;
        }
        .level-top-title {
            padding: 14px 0;
            .level-title-left {

            }
            .level-title-right {
            }
        }
        .level-title {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-left: -15px;
            margin-right: -15px;
            padding-left: 15px;
            padding-right: 15px;
        }
        .brand-value {
            display: inline-block;
            .el-tag{
                margin-left: 5px;
                margin-bottom: 10px;
            }
        }
        .box-title,.title-box {
            padding: 10px 15px;
            border-bottom: 1px solid #eeeeee;
        }
        .bg-white {
            background-color: #fff;
        }

        .brand-box {
            padding: 10px 15px;
            margin-bottom: 10px;
            min-height: 60px;
        }
        .brand-info-box{
            margin-bottom: 10px;
        }
        .brand-info-box.title-box{
            padding: 0;
            margin-bottom:0;
        }
        .el-select {
            width: 100%;
        }
        .next-level-box{
            .el-tag--mini{
                margin: 3px;
            }
        }
        .level-title-one{
            background-color: rgba(59, 52, 44,.7);
            color: #fff;
        }
        .level-title-two{
            color: #F39800;
        }
        .children-brand{
            padding-bottom: 15px;
        }
        .flex-box{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        .el-button{
            cursor: pointer;
        }
    }
</style>
