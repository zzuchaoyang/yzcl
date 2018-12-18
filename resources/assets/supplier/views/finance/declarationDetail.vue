<template>
    <div class="app-container">
        <el-row>
            <el-card class="box-card">
                <div slot="header" class="clearfix text-al_c">
                    <span>报单详情（{{ item.uuid }}）</span>
                </div>
                <div class="table-con">
                    <el-row>
                        <el-col :span="24">
                            <el-row class="header-box">
                                <el-col :span="24" class="el-col_box">报单人信息</el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="24">
                                    <el-row>
                                        <el-col :span="3" class="el-col_box text-al_r">
                                            报单人姓名:
                                        </el-col>
                                        <el-col :span="3" class="el-col_box text-pd_l">
                                            {{ getCompanyInfo(item).name }}
                                        </el-col>
                                        <el-col :span="3" class="el-col_box text-al_r">
                                            所在公司:
                                        </el-col>
                                        <el-col :span="7" class="el-col_box text-pd_l">
                                            {{ getCompanyInfo(item).companyName }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="24">
                                    <el-col :span="3" class="el-col_box text-al_r">
                                        手机号码:
                                    </el-col>
                                    <el-col :span="3" class="el-col_box text-pd_l">
                                        {{ getCompanyInfo(item).mobile }}
                                    </el-col>
                                    <el-col :span="3" class="el-col_box text-al_r">
                                        报单时间:
                                    </el-col>
                                    <el-col :span="7" class="el-col_box text-pd_l">
                                        {{ item.created_at }}
                                    </el-col>
                                </el-col>
                            </el-row>
                        </el-col>
                    </el-row>
                </div>
                <div class="table-con">
                    <el-row>
                        <el-col :span="8">
                            <el-row class="header-box">
                                <el-col :span="24" class="el-col_box">债务人信息</el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="24">
                                    <el-row>
                                        <el-col :span="7" class="el-col_box text-al_r">
                                            债务人姓名:
                                        </el-col>
                                        <el-col :span="17" class="el-col_box text-pd_l">
                                            {{ item.debtor_name }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="24">
                                    <el-row>
                                        <el-col :span="7" class="el-col_box text-al_r">
                                            证件号码:
                                        </el-col>
                                        <el-col :span="17" class="el-col_box text-pd_l">
                                            {{ item.debtor_id_card }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="24">
                                    <el-row>
                                        <el-col :span="7" class="el-col_box text-al_r">
                                            手机号码:
                                        </el-col>
                                        <el-col :span="17" class="el-col_box text-pd_l">
                                            {{ item.debtor_mobile }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                        </el-col>
                        <el-col :span="16" class="el-col_box-l">
                            <el-row class="header-box">
                                <el-col :span="24" class="el-col_box">申请信息</el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="10" class="el-col_box text-al_r">
                                            产品类型:
                                        </el-col>
                                        <el-col :span="14" class="el-col_box text-pd_l" v-if="item.jrProduct">
                                            {{ item.jrProduct.category }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="8" class="el-col_box text-al_r">
                                            产品名称:
                                        </el-col>
                                        <el-col :span="16" class="el-col_box text-pd_l" v-if="item.jrProduct">
                                            {{ item.jrProduct.name }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="10" class="el-col_box text-al_r">
                                            期限:
                                        </el-col>
                                        <el-col :span="14" class="el-col_box text-pd_l">
                                            {{ item.term }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="8" class="el-col_box text-al_r">
                                            申请金额(万元):
                                        </el-col>
                                        <el-col :span="16" class="el-col_box text-pd_l">
                                            {{ item.loan_amount }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="10" class="el-col_box text-al_r">
                                            业务员姓名:
                                        </el-col>
                                        <el-col :span="14" class="el-col_box text-pd_l">
                                            {{ item.salesman_name }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="8" class="el-col_box text-al_r">
                                            业务员电话:
                                        </el-col>
                                        <el-col :span="16" class="el-col_box text-pd_l">
                                            {{ item.salesman_mobile }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                            <el-row>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="10" class="el-col_box text-al_r">
                                            期望放款时间:
                                        </el-col>
                                        <el-col :span="14" class="el-col_box text-pd_l">
                                            {{ item.expect_loan_time }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                                <el-col :span="12">
                                    <el-row>
                                        <el-col :span="8" class="el-col_box text-al_r">
                                            成交编号:
                                        </el-col>
                                        <el-col :span="16" class="el-col_box text-pd_l">
                                            {{ item.e_bargain_id }}
                                        </el-col>
                                    </el-row>
                                </el-col>
                            </el-row>
                        </el-col>
                    </el-row>
                </div>
                <el-form label-width="180px" class="table-box">
                    <h4>抵押物信息</h4>
                    <el-table :data="item.mortgage_information" border fit highlight-current-row style="width: 100%">
                        <el-table-column type="index" label="序列" width="60"></el-table-column>
                        <el-table-column prop="mortgagor_name" label="抵押人姓名" width="180"></el-table-column>
                        <el-table-column prop="house_certificate_no" label="房产证号" width="180"></el-table-column>
                        <el-table-column prop="house_area" label="房屋面积（平米）" width="200"></el-table-column>
                        <el-table-column prop="ownership_type" label="房屋性质" width="180"></el-table-column>
                        <el-table-column prop="house_address" label="房屋地址"></el-table-column>
                    </el-table>

                    <h4>共借人信息</h4>
                    <el-table :data="item.borrowers" border fit highlight-current-row style="width: 100%">
                        <el-table-column type="index" label="序列" width="60"></el-table-column>
                        <el-table-column prop="borrower_name" label="共借人姓名"></el-table-column>
                        <el-table-column prop="id_card" label="证件号码"></el-table-column>
                        <el-table-column prop="mobile" label="手机号码"></el-table-column>
                    </el-table>

                    <h4>资料图片</h4>
                    <viewer :images="item.picture_urls">
                        <img class="image" v-for="src in item.picture_urls" :src="src" :key="src">
                    </viewer>

                </el-form>
            </el-card>
        </el-row>
    </div>
</template>

<script>
  import 'viewerjs/dist/viewer.css'
  import Viewer from 'v-viewer'
  import Vue from 'vue'
  import { getFinanceDeclaration } from '../../api/finance'

  Vue.use(Viewer)

  export default {
    data() {
      return {
        item: {
          uuid: null,
          agent: {},
          server: {},
          mortgage_information: [],
          borrowers: [],
          picture_urls: []
        },
        options: {
          url: 'data-source'
        }
      }
    },
    created() {
      const id = this.$route.params && this.$route.params.id
      this.getItem(id)
    },
    methods: {
      getCompanyInfo(data) {
        // console.log(data.uuid)
        return {
          source: data.server ? 'ERP' : '好房多',
          companyName: data.server ? data.server.name : data.fxsCompany.organization_name,
          name: data.agent ? data.agent.name : data.fxsUser.name,
          mobile: data.agent ? data.agent.mobile : data.fxsUser.phone_number
        }
      },
      getItem(id) {
        getFinanceDeclaration(id).then(response => {
          this.item = response.data.financeDeclaration
        })
      }
    }
  }
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
    .image {
        width: calc(20% - 10px);
        cursor: pointer;
        margin: 5px;
        display: inline-block;
    }
    .text-al_c{
        text-align: center;
    }

    .table-con {
        margin-bottom: 30px;
        border: 1px solid #ebeef5;
        overflow: hidden;
        .text-al_r {
            padding-right: 12px;
            text-align: right;
            color: #8d8f92;
        }
        .el-col_box {
            height: 30px;
            line-height: 30px;
            font-size: 14px;
        }
        .el-col_box-l{
            border-left: 1px solid #ebeef5;
        }
        .header-box {
            padding-left: 30px;
            background: #f5f7fa;
            .el-col_box {
                height: 44px;
                line-height: 44px;
            }
        }
    }
    .table-box{
        /deep/ .has-gutter{
            tr{
                th{
                    background: #f5f7fa;
                    text-align: center;
                }
            }
        }
        /deep/ .el-table__body{
            .el-table__row{
                .cell{
                    text-align: center;
                }
            }
        }
    }

</style>
