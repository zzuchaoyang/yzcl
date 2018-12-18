<template>
    <div class="app-container">
        <el-card class="box-card">
            <el-row>
                <el-col :xs="24" :sm="24" :md="24" :lg="13" :xl="13" class="card-panel-col">
                    <div class='card-panel'>
                        <div class="card-panel-icon-wrapper icon-people">
                            <svg-icon icon-class="edu" class-name="card-panel-icon"/>
                        </div>
                        <div class="card-panel-description">
                            <div class="card-panel-text">授信额度</div>
                            <span class="card-panel-num">恭喜您，获得我司的贷款额度</span>
                        </div>
                        <div class="card-panel-description card-panel-right">
                            <div class="card-panel-text">
                                <el-row>
                                    <el-col :span="11"><span class="num">{{ loan_info.min }}</span></el-col>
                                    <el-col :span="2"><span class="num">—</span></el-col>
                                    <el-col :span="11"><span class="num">{{ loan_info.max }}</span>元</el-col>
                                </el-row>
                            </div>
                            <span class="card-panel-num">
                                <el-row>
                                    <el-col :span="12">最低</el-col>
                                    <el-col :span="12">最高</el-col>
                                </el-row>
                            </span>
                        </div>
                    </div>
                </el-col>
                <!--<el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="12" class="card-panel-col">-->
                <!--<div class='card-panel'>-->
                <!--<div class="card-panel-icon-wrapper icon-people">-->
                <!--<svg-icon icon-class="yiyongedu" class-name="card-panel-icon"/>-->
                <!--</div>-->
                <!--<div class="card-panel-description">-->
                <!--<div class="card-panel-text">已用额度</div>-->
                <!--<span class="card-panel-num">已审批通过的贷款额度</span>-->
                <!--</div>-->
                <!--<div class="card-panel-description card-panel-right card-panel-right2">-->
                <!--<div class="card-panel-text"><span class="num">100,000</span>元</div>-->
                <!--</div>-->
                <!--</div>-->
                <!--</el-col>-->
            </el-row>
        </el-card>
        <el-card class="box-card">
            <el-tabs v-model="activeName" @tab-click="handleClick">
                <el-tab-pane label="贷款申请" name="first">
                    <el-form :model="finance" ref="dataForm" label-width="100px" class="demo-ruleForm">
                        <el-row>
                            <el-col :span="24">
                                <el-row :gutter="10">
                                    <el-col :span="6">
                                        <el-form-item label="贷款金额" prop="amount"
                                                      :rules="[{ required: true, message: '请输入贷款金额', trigger: 'blur' },amountValid]">
                                            <el-input type="number" auto-complete="on" v-model="finance.amount" clearable
                                                      placeholder="请输入贷款金额(单位:元)" @input="handleInput" @change="countInterest()" @keyup.enter="countInterest()">
                                                <template slot="append">元</template>
                                            </el-input>

                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="6">
                                        <el-form-item label="贷款周期" prop="period"
                                                      :rules="[{ required: true, message: '请输入贷款周期', trigger: 'blur' }]">
                                            <el-input type="number" auto-complete="on" v-model="finance.period" clearable
                                                      placeholder="请输入贷款周期(单位:月)" @input="handleInput" @change="countInterest()" @keyup.enter="countInterest()">
                                                <template slot="append">月</template>
                                            </el-input>
                                            <!--<el-select placeholder="请输入贷款周期(单位:月)" clearable style="width: 100%">-->
                                            <!--<el-option label="3个月" value="3"></el-option>-->
                                            <!--<el-option label="6个月" value="6"></el-option>-->
                                            <!--<el-option label="9个月" value="9"></el-option>-->
                                            <!--</el-select>-->
                                        </el-form-item>
                                    </el-col>
                                    <el-col :span="6">
                                        <el-form-item label="贷款利息" prop="name">
                                            <el-input auto-complete="on" readonly v-model="interest" placeholder="由系统按照借款金额及周期自动计算(单位:元)">
                                                <template slot="append">元</template>
                                            </el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>

                            </el-col>
                            <el-col :span="24">
                                <el-form-item label="证件列表" prop="credential_info"
                                              :rules="[{ required: true, message: '请上传证件照', trigger: 'blur' }]">
                                    <el-table :data="newCertificateList"
                                              element-loading-text="加载中"
                                              border
                                              fit
                                              :height="certificateHeight"
                                              highlight-current-row>

                                        <el-table-column
                                                label="序号"
                                                width="50"
                                                type="index"
                                                align="center">
                                        </el-table-column>

                                        <el-table-column align="center" label='资料名称' width="200">
                                            <template slot-scope="scope">
                                                <span>{{ scope.row.name }}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column align="center" label='说明'>
                                            <template slot-scope="scope">
                                                <span>{{ scope.row.explain }}</span>
                                            </template>
                                        </el-table-column>

                                        <el-table-column align="center" label="备注" width="70">
                                            <template slot-scope="scope">
                                                <span v-if="scope.row.remark === '必填'" class="required-tit">{{scope.row.remark}}</span>
                                                <span v-else>{{scope.row.remark}}</span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column align="center" label='上传证件' width="300">
                                            <template slot-scope="scope">
                                                <span v-for="(x,index) in scope.row.url.data" :key="index" class="filesImg">
                                                    <a target="_blank" :href="x.url">
                                                        <img v-if="x.type === 'jpg' || x.type === 'jpeg' || x.type === 'png'" :src="x.url" style="width: 41px;max-height: 41px" alt="图片">
                                                         <svg-icon v-if="x.type === 'doc' || x.type === 'docx'" icon-class="word" class-name="card-panel-icon"/>
                                                         <svg-icon v-if="x.type === 'pdf'" icon-class="pdf" class-name="card-panel-icon"/>
                                                        <svg-icon v-if="x.type === 'xls' || x.type === 'xlsx'" icon-class="excel2" class-name="card-panel-icon"/>
                                                    </a>
                                                </span>
                                            </template>
                                        </el-table-column>
                                        <el-table-column align="center" fixed="right" label="操作" width="100">
                                            <template slot-scope="scope">
                                                <div>
                                                    <el-upload
                                                            class="upload-demo"
                                                            action="/graphql-file"
                                                            accept="image/jpeg,image/png,.xls,.xlsx,.pdf,.doc,.docx"
                                                            @click.native="selectRow(scope.row)"
                                                            :data='{_token:token}'
                                                            :on-success="fileUploadSuccess"
                                                            :limit="5"
                                                            :show-file-list="false">
                                                        <el-button size="mini" plain type="primary">点击上传</el-button>
                                                    </el-upload>
                                                    <!--<el-button type="primary"  size="mini" plain @click="showAddDialog(scope.row)">上传</el-button>-->
                                                    <!--<el-button type="danger" plain v-show="" class="el-button&#45;&#45;danger" size="mini" v-if="scope.row.imgUrl" @click="deleteUser(scope.row.id)">浏览</el-button>-->
                                                </div>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="primary" @click="storeFinance">提交申请</el-button>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-form>
                </el-tab-pane>
                <el-tab-pane label="贷款申请列表" name="second">
                    <el-table :data="list.items"
                              v-loading="list.listLoading"
                              element-loading-text="加载中"
                              border
                              fit
                              :height="tableHeight"
                              highlight-current-row>

                        <el-table-column
                                label="序号"
                                width="50"
                                type="index"
                                align="center">
                        </el-table-column>
                        <el-table-column align="center" label='供应商'>
                            <template slot-scope="scope">
                                <span>{{ scope.row.supplier.name }}</span>
                            </template>
                        </el-table-column>
                        <el-table-column align="center" label='申请时间'>
                            <template slot-scope="scope">
                                <span>{{ scope.row.created_at }}</span>
                            </template>
                        </el-table-column>

                        <el-table-column align="center" label='申请金额(元)'>
                            <template slot-scope="scope">
                                <span>{{ scope.row.amount }}</span>
                            </template>
                        </el-table-column>

                        <el-table-column align="center" label="贷款周期">
                            <template slot-scope="scope">
                                <span>{{scope.row.period }}</span>
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
                </el-tab-pane>
            </el-tabs>
        </el-card>
    </div>
</template>
<script>
  import { LoanPaginator, StoreLoan } from '../../api/finance'
  import { listHelper } from '@common/utils/listHelper'
  import { notify } from '@common/utils/helper'

  const certificateList = [
    {
      name: '营业执照',
      explain: '上传营业执照正本/副本的原件/复印件盖公章，请拍照上传',
      remark: '必填',
      url: {
        txt: 'yyzz',
        name: '营业执照',
        remark: '必填',
        data: []
      }
    },
    {
      name: '法人代表/负责人身份证',
      explain: '提供正反面同页的复印件盖公章，拍照上传',
      remark: '必填',
      url: {
        txt: 'sfz',
        name: '法人代表/负责人身份证',
        remark: '必填',
        data: []
      }
    },
    {
      name: '法人代表/负责人结婚证',
      explain: '如有，提供结婚证原件/复印件盖公章，拍照上传',
      remark: '选填',
      url: {
        txt: 'jhz',
        name: '法人代表/负责人结婚证',
        remark: '选填',
        data: []
      }
    },
    {
      name: '银行开户许可证',
      explain: '提供基本户银行开户许可证原件/复印件盖公章，拍照上传；如为个体工商户，无须提供；非个体工商户',
      remark: '必填',
      url: {
        txt: 'yhkhxkz',
        name: '银行开户许可证',
        remark: '必填',
        data: []
      }
    },
    {
      name: '公司章程',
      explain: '提供复印件首页盖公章+骑缝章，拍照上传；如为个体工商户，无须提供；非个体工商户',
      remark: '必填',
      url: {
        txt: 'gszc',
        name: '公司章程',
        remark: '必填',
        data: []
      }
    },
    {
      name: '办公场地租赁/买卖证明',
      explain: '如为企业购买，请提供房产证复印件加盖公章；如为企业租赁，请提供房产租赁合同复印件，首页盖公章+骑缝章，拍照上传',
      remark: '必填',
      url: {
        txt: 'mmzm',
        name: '办公场地租赁/买卖证明',
        remark: '必填',
        data: []
      }
    },
    {
      name: '个人征信报告',
      explain: '提供法人代表个人征信报告，拍照上传',
      remark: '必填',
      url: {
        txt: 'grzxbg',
        name: '个人征信报告',
        remark: '必填',
        data: []
      }
    },
    {
      name: '企业征信报告',
      explain: '提供企业的征信报告，拍照上传；如为个体工商户，无须提供；非个体工商户',
      remark: '必填',
      url: {
        txt: 'qyzxbg',
        name: '企业征信报告',
        remark: '必填',
        data: []
      }
    },
    {
      name: '厂商经销/代理合同',
      explain: '提供合同复印件首页盖公章+骑缝章，拍照上传；如有多份，请上传多份；有助于提升贷款额度',
      remark: '选填',
      url: {
        txt: 'dlht',
        name: '厂商经销/代理合同',
        remark: '选填',
        data: []
      }
    },
    {
      name: '零售商采购合同',
      explain: '提供合同复印件首页盖公章+骑缝章，拍照上传；如有多份，请上传多份；有助于提升贷款额度',
      remark: '选填',
      url: {
        txt: 'lsscght',
        name: '零售商采购合同',
        remark: '选填',
        data: []
      }
    }
  ]
  export default {
    data() {
      const validateAmount = (rule, value, callback) => {
        if (value && value > this.loan_info.max) {
          callback(new Error('输入金额应小于最大授信额度'))
        } else {
          callback()
        }
      }
      return {
        validateAmount,
        amountValid: { validator: validateAmount },
        certificateList,
        newCertificateList: null,
        token: null,
        activeName: 'first',
        tableHeight: null,
        certificateHeight: null,
        loan_info: null,
        storageUrl: null,
        interest: null,
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
        finance: {
          period: null,
          amount: null,
          credential_info: {}
        }
      }
    },
    watch: {
      finance: {
        deep: true,
        handler(value) {
          if (value) {
            localStorage.setItem('finance', JSON.stringify(this.finance))
          }
        }
      }
    },
    created() {
      this.$nextTick(() => {
        this.tableHeight = this.$store.getters.tableHeight - 70
        this.certificateHeight = this.$store.getters.tableHeight - 120
      })
      this.loan_info = this.$store.state.user.user.loan_info
      if (localStorage.getItem('finance')) {
        this.finance = this._.cloneDeep(JSON.parse(localStorage.getItem('finance')))
        this.countInterest()
      }
      if (localStorage.getItem('newCertificateList')) {
        this.newCertificateList = this._.cloneDeep(JSON.parse(localStorage.getItem('newCertificateList')))
      } else {
        this.newCertificateList = this._.cloneDeep(this.certificateList)
      }
    },
    methods: {
      handleClick(tab, event) {
        if (tab.name === 'second') {
          this.getList()
        }
      },
      handleInput(value) {
        this.val = value.replace(/[^\d]/g, '')
      },
      // 计算利息
      countInterest() {
        if (this.finance.period && this.finance.amount) {
          this.interest = _.multiply(_.divide(_.multiply(this.finance.amount, 0.13), 12), this.finance.period)
        }
      },
      // 金融
      getList() {
        this.list.listLoading = true
        LoanPaginator(this.listQuery).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.loanPaginator)
          this.list.listLoading = false
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
      selectRow(row) {
        this.storageUrl = row
      },
      fileUploadSuccess(file) {
        const fileData = {
          type: file.key.split('.')[file.key.split('.').length - 1],
          url: file.url
        }
        this.storageUrl.url.data.push(fileData)
        localStorage.setItem('finance', JSON.stringify(this.finance))
        localStorage.setItem('newCertificateList', JSON.stringify(this.newCertificateList))
      },
      // 保存申请
      storeFinance() {
        this.$refs['dataForm'].validate((valid) => {
          if (!valid) {
            return false
          }
          const mustFill = this._.find(this._.map(this.newCertificateList, 'url'), function(o) { if (o.data.length === 0 && o.remark === '必填') { return o } })
          if (mustFill && mustFill.name) {
            notify.warning(`请上传` + mustFill.name)
          } else {
            this._.map(this.newCertificateList, 'url').forEach((data, index) => {
              const ImgList = []
              switch (data.txt) {
                case 'yyzz':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.yyzz = this._.clone(ImgList)
                  break
                case 'sfz':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.sfz = this._.clone(ImgList)
                  break
                case 'jhz':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.jhz = this._.clone(ImgList)
                  break
                case 'yhkhxkz':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.yhkhxkz = this._.clone(ImgList)
                  break
                case 'gszc':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.gszc = this._.clone(ImgList)
                  break
                case 'mmzm':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.mmzm = this._.clone(ImgList)
                  break
                case 'grzxbg':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.grzxbg = this._.clone(ImgList)
                  break
                case 'qyzxbg':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.qyzxbg = this._.clone(ImgList)
                  break
                case 'dlht':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.dlht = this._.clone(ImgList)
                  break
                case 'lsscght':
                  data.data.forEach((data) => {
                    ImgList.push(data.url)
                  })
                  this.finance.credential_info.lsscght = this._.clone(ImgList)
                  break
              }
            })
            const tempData = this._.cloneDeep(this.finance)
            StoreLoan(tempData).then(() => {
              this.finance.period = null
              this.finance.amount = null
              this.finance.credential_info = {}
              this.newCertificateList = this._.cloneDeep(this.certificateList)
              this.$forceUpdate()
              localStorage.removeItem('finance')
              localStorage.removeItem('newCertificateList')
              notify.success()
            })
          }
        })
      }
    }
  }
</script>
<style lang="scss" scoped>
    .app-container {
        .required-tit{
            color: #f59900;
        }
        .pagination-container {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        /deep/ .el-input-group__append{
            padding: 0 10px;
        }
        .box-card {
            margin-bottom: 10px;
            /deep/ .el-card__body {
                padding: 20px 10px 0;
            }
        }
        .filesImg{
            .svg-icon{
                font-size: 26px;
                margin-right: 10px;
            }
        }
        .card-panel-col {
        }
        .card-panel {
            height: 60px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 12px;
            position: relative;
            overflow: hidden;
            color: #666;
            background: #fff;
            .icon-people {
                color: #40c9c6;
            }
            .icon-message {
                color: #36a3f7;
            }
            .icon-money {
                color: #f4516c;
            }
            .icon-shoppingCard {
                color: #34bfa3
            }
            .card-panel-icon-wrapper {
                float: left;
                width: 54px;
                height: 54px;
                margin: 3px 22px 0 8px;
                transition: all 0.38s ease-out;
                border-radius: 54px;
                color: #fff;
                font-size: 24px;
                text-align: center;
                line-height: 54px;
            }
            .card-panel-icon {
                float: left;
                font-size: 54px;
            }
            .card-panel-description {
                float: left;
                font-weight: bold;
                margin: 8px 0;
                .card-panel-text {
                    line-height: 18px;
                    color: #333;
                    font-size: 16px;
                    margin-bottom: 10px;
                    .num {
                        font-size: 22px;
                        color: #CC0000;
                    }
                }
                .card-panel-num {
                    font-size: 14px;
                    color: #999;
                }
            }
            .card-panel-right {
                width: 288px;
                padding-left: 40px;
                text-align: center;
                .card-panel-text {
                    font-size: 14px;
                }
            }
            .card-panel-right2 {
                padding-top: 6px;
                .card-panel-icon {
                    font-size: 18px;
                    color: #96918A;
                }
                .num {
                    font-size: 22px;
                    color: #CC0000;
                }
            }
        }
    }
</style>
