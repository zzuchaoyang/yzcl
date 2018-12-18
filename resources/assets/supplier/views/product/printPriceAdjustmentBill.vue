<template>
    <div class="app-container out-container" :style="{height: viewHeight}">
        <div class="height100 scroller-y inner-container" v-loading="isLoading">
            <div class="el-card is-always-shadow" >
                <div class="bill-info-box bg-white">
                    <div class="box-title">
                        <div>
                            调价单信息
                        </div>
                        <div class="noprint">
                            <el-button type="primary" @click="toPrintBill">打印调价单</el-button>
                        </div>
                    </div>

                    <el-form ref="billInfoForm" :model="adjustmentBillInfo" label-width="130px" class="info-rule-form pd15">
                        <el-row :gutter="24">
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="调价单编码" prop="code">
                                        {{adjustmentBillInfo.code}}
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="制单日期" prop="created_at">
                                        {{adjustmentBillInfo.created_at | parseTime('{y}-{m}-{d} {h}:{i}')}}
                                    </el-form-item>
                                </div>
                            </el-col>
                        </el-row>
                        <el-row :gutter="24">
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="生效日期" prop="effective_at">
                                        <div>
                                            {{adjustmentBillInfo.effective_at | parseTime('{y}-{m}-{d} {h}:{i}')}}
                                        </div>
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="调价商品数量" prop="number">
                                        <div>
                                            {{adjustmentBillInfo.number}}
                                        </div>
                                    </el-form-item>
                                </div>
                            </el-col>
                        </el-row>
                        <el-row :gutter="24">
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="制单人账号" prop="author_name">
                                        {{adjustmentBillInfo.author_name}}
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="12">
                                <div class="grid-content">
                                    <el-form-item label="调价单状态" prop="status">
                                        {{adjustmentBillInfo.status}}
                                    </el-form-item>
                                </div>
                            </el-col>
                        </el-row>
                    </el-form>
                </div>
            </div>

            <div class="el-card is-always-shadow mgt10">
                <div class="bill-list-box bg-white">
                    <div class="box-title">
                        调价商品列表
                    </div>
                    <div class="pd15">
                        <template>
                            <el-table
                                    :data="productListData"
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
                                        prop="pictures"
                                        label="商品图片"
                                        align="center"
                                        width="100">
                                    <template slot-scope="scope">
                                        <div class="images" v-viewer>
                                            <img class="img" :src="item" v-for="(item, index) in scope.row.pictures" v-show="index === 0" />
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="bar_code"
                                        label="商品条码"
                                        align="center"
                                        width="100">
                                </el-table-column>
                                <el-table-column
                                        prop="name"
                                        align="center"
                                        width="100"
                                        label="商品名称">
                                </el-table-column>
                                <el-table-column
                                        prop="brand.name"
                                        align="center"
                                        width="100"
                                        label="商品品牌">
                                </el-table-column>
                                <el-table-column
                                        prop="order_unit"
                                        align="center"
                                        width="100"
                                        label="订货单位">
                                </el-table-column>
                                <el-table-column
                                        prop="spec_unit"
                                        align="center"
                                        width="100"
                                        label="订货规格">
                                </el-table-column>
                                <el-table-column
                                        prop="trade_price"
                                        align="center"
                                        width="120"
                                        label="订货单位含税进价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.trade_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="one_price"
                                        align="center"
                                        width="100"
                                        label="一级供货价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.one_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="two_price"
                                        align="center"
                                        width="100"
                                        label="二级供货价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.two_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="three_price"
                                        align="center"
                                        width="100"
                                        label="三级供货价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.three_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="four_price"
                                        align="center"
                                        width="100"
                                        label="四级供货价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.four_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="five_price"
                                        align="center"
                                        width="100"
                                        label="五级供货价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.five_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                                <el-table-column
                                        prop="retail_price"
                                        align="center"
                                        width="100"
                                        label="建议零售价">
                                    <!--<template slot-scope="scope">-->
                                        <!--<el-input v-model="scope.row.retail_price" type="number" clearable readonly></el-input>-->
                                    <!--</template>-->
                                </el-table-column>
                            </el-table>
                        </template>
                    </div>

                </div>
            </div>
        </div>


    </div>
</template>

<script>
  import { productPriceAdjustmentPaginator } from '@supplier/api/product'
  export default {
    data() {
      return {
        viewHeight: '100%',
        adjustmentBillInfo: {
          code: '', // 调价单编码
          created_at: new Date(), // 制单日期
          effective_at: '', // 生效时间
          number: null, // 调价商品数量
          author_name: this.$store.state.user.user.name, // 制单人账号
          status: '' // 调价单状态
        },
        productListData: [],
        isLoading: false,
        billId: null
      }
    },
    mounted() {
      this.billId = parseInt(this.$route.params.id)
      if (this.$route.params.id) {
        this.getBillInfo(this.billId)
      }
    },
    methods: {
      getBillInfo(id) {
        const paginator = {
          page: null,
          limit: null,
          sort: null
        }
        const more = {
          id: id
        }
        this.isLoading = true
        productPriceAdjustmentPaginator(paginator, more).then(response => {
          this.isLoading = false
          const result = response.data.productPriceAdjustmentPaginator.items[0]
          this.adjustmentBillInfo = {
            code: result.code, // 调价单编码
            created_at: result.created_at, // 制单日期
            effective_at: result.effective_at, // 生效时间
            number: result.number, // 调价商品数量
            author_name: this.$store.state.user.user.name, // 制单人账号
            status: result.status // 调价单状态
          }
          this.productListData = result.products
        }).finally(() => {
          this.isLoading = false
        })
      },
      toPrintBill() {
        window.print()
      }
    }
  }

</script>
<style lang="scss" scoped>
    .pd15 {
        padding: 15px;
    }
    .pdr15 {
        padding-right: 15px;
    }
    .pdr20 {
        padding-right: 20px;
    }
    .pdl0{
        padding-left: 0;
    }
    .mgt10 {
        margin-top: 10px;
    }
    .bill-info-box, .bill-list-box{
        padding: 15px;
        .box-title{
            margin: 0 -15px;
            padding: 0 15px 15px;
            border-bottom: 1px solid #eeeeee;
        }
    }
    .action-buttons{
        text-align: center;
    }
    .box-title{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
    .bill-list-box{
        .img{
            width: 40px;
            height: 40px;
            vertical-align: middle;
        }
    }

    .height100{
        height: 100%;
    }
    .scroller-y{
        overflow-y: auto;
    }
    .out-container{
        position: relative;
        overflow: hidden;
    }
    .inner-container{
        position: absolute;
        left: 0;
        top: 0;
        right: -17px;
        bottom: 0;
        overflow-x: hidden;
        overflow-y: scroll;
    }

    @media print {
        .noprint{
            display: none;
        }
    }
</style>
