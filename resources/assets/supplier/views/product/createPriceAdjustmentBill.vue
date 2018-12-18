<template>
    <div class="app-container" :style="{height: this.$store.getters.tableHeight + 218 + `px`}">
        <div class="height100 scroller-y" v-loading="isLoading">
            <div class="el-card is-always-shadow" >
                <div class="bill-info-box bg-white">
                    <div class="box-title">
                        <div class="line-h-28">
                            调价单信息
                        </div>
                        <div v-if="!disabledPrint">
                            <el-button type="primary" @click="toPrintBill">导出调价单</el-button>
                        </div>
                    </div>

                    <el-form ref="billInfoForm" :model="adjustmentBillInfo" :rules="billInfoRules" label-width="130px" class="info-rule-form pd15">
                        <el-row :gutter="20">
                            <el-col :span="8">
                                <div class="grid-content">
                                    <el-form-item label="调价单编码" prop="code">
                                        {{adjustmentBillInfo.code || '保存后系统自动生成编码'}}
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="8">
                                <div class="grid-content">
                                    <el-form-item label="制单日期" prop="created_at">
                                        {{dateFormat(adjustmentBillInfo.created_at).ymdhm}}
                                    </el-form-item>
                                </div>
                            </el-col>
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="8">
                                <div class="grid-content">
                                    <el-form-item label="生效日期" prop="effective_at">
                                        <div v-if="statusType !== 'view'">
                                            <el-date-picker
                                                    v-model="adjustmentBillInfo.effective_at"
                                                    type="date"
                                                    :picker-options="pickerOptions1"
                                                    value-format="yyyy-MM-dd"
                                                    format="yyyy-MM-dd">
                                            </el-date-picker>
                                        </div>
                                        <div v-else>
                                            {{dateFormat(adjustmentBillInfo.effective_at).ymd}}
                                        </div>
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="8">
                                <div class="grid-content">
                                    <el-form-item label="调价商品数量" prop="number">
                                        <div v-if="statusType !== 'view'">
                                            <el-input v-model="adjustmentBillInfo.number" readonly placeholder="系统自动获取商品数量"></el-input>
                                        </div>
                                        <div v-else>
                                            {{adjustmentBillInfo.number}}
                                        </div>
                                    </el-form-item>
                                </div>
                            </el-col>
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="8">
                                <div class="grid-content">
                                    <el-form-item label="制单人账号" prop="author_name">
                                        {{adjustmentBillInfo.author_name}}
                                    </el-form-item>
                                </div>
                            </el-col>
                            <el-col :span="8">
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
                        <div class="line-h-28">
                            调价商品列表
                        </div>

                    </div>
                    <div class="pd15">
                        <el-row :gutter="20" v-show="adjustmentBillInfo.status === null || adjustmentBillInfo.status === '未审核' ">
                            <el-col :span="12">
                                <el-form label-width="90px" class="pd15 pdl0" :inline="true" @submit.native.prevent>
                                    <el-form-item label="按条码查询">
                                        <el-input
                                                clearable
                                                v-model.trim="more.bar_code"
                                                type="number"
                                                placeholder="按条码查询"
                                        ></el-input>
                                    </el-form-item>
                                    <el-form-item>
                                        <el-button type="primary" @click="addProduct">添加</el-button>
                                    </el-form-item>
                                </el-form>
                            </el-col>
                        </el-row>
                        <template>
                            <el-table
                                    class="price-adjustment-product"
                                    :data="productListData"
                                    fit
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
                                        <div class="images">
                                            <viewer>
                                                <img class="img" :src="item" v-for="(item, index) in scope.row.pictures" v-show="index === 0" />
                                            </viewer>
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
                                        label="商品名称">
                                </el-table-column>
                                <el-table-column
                                        align="center"
                                        label="商品品牌">
                                    <template slot-scope="scope">
                                        <span >{{ scope.row.brand && scope.row.brand.name || '无'}}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="order_unit"
                                        align="center"
                                        width="100"
                                        label="订货单位">
                                </el-table-column>
                                <el-table-column
                                        align="center"
                                        label="订货规格">
                                    <template slot-scope="scope">
                                        <span>{{scope.row.spec_number + scope.row.spec_unit + '*' + scope.row.single_num}}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        label-class-name="is-required-class"
                                        prop="trade_price"
                                        align="center"
                                        width="160"
                                        label="订货单位含税进价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.trade_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'trade_price')"
                                                @change="toFormatFloat(scope, 'trade_price')"
                                                @input.native="decimal(scope, 'trade_price')"
                                                :disabled="isReadOnly">
                                            <span slot="suffix" v-if="isNull(scope, 'trade_price')" class="price-required">必填项</span>
                                        </el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        label-class-name="is-required-class"
                                        prop="one_price"
                                        align="center"
                                        width="125"
                                        label="一级供货价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.one_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'one_price')"
                                                @change="toFormatFloat(scope, 'one_price')"
                                                @input.native="decimal(scope, 'one_price');"
                                                :disabled="isReadOnly">
                                            <span slot="suffix" v-if="isNull(scope, 'one_price')" class="price-required">必填项</span>
                                        </el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="two_price"
                                        align="center"
                                        width="125"
                                        label="二级供货价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.two_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'two_price')"
                                                @change="toFormatFloat(scope, 'two_price')"
                                                @input.native="decimal(scope, 'two_price')"
                                                :disabled="isReadOnly"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="three_price"
                                        align="center"
                                        width="125"
                                        label="三级供货价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.three_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'three_price')"
                                                @change="toFormatFloat(scope, 'three_price')"
                                                @input.native="decimal(scope, 'three_price')"
                                                :disabled="isReadOnly"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="four_price"
                                        align="center"
                                        width="125"
                                        label="四级供货价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.four_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'four_price')"
                                                @change="toFormatFloat(scope, 'four_price')"
                                                @input.native="decimal(scope, 'four_price')"
                                                :disabled="isReadOnly"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        prop="five_price"
                                        align="center"
                                        width="125"
                                        label="五级供货价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.five_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'five_price')"
                                                @change="toFormatFloat(scope, 'five_price')"
                                                @input.native="decimal(scope, 'five_price')"
                                                :disabled="isReadOnly"></el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column
                                        label-class-name="is-required-class"
                                        prop="retail_price"
                                        align="center"
                                        width="125"
                                        label="建议零售价（元）">
                                    <template slot-scope="scope">
                                        <el-input
                                                v-model.trim="scope.row.retail_price"
                                                clearable
                                                @keyup.enter="toFormatFloat(scope, 'retail_price')"
                                                @change="toFormatFloat(scope, 'retail_price')"
                                                @input.native="decimal(scope, 'retail_price')"
                                                :disabled="isReadOnly">
                                            <span slot="suffix" v-if="isNull(scope, 'retail_price')" class="price-required">必填项</span>
                                        </el-input>
                                    </template>
                                </el-table-column>
                                <el-table-column align="center" fixed="right" label="操作" width="100" v-if="adjustmentBillInfo.status === null || adjustmentBillInfo.status === '未审核' ">
                                    <template slot-scope="scope">
                                        <el-button type="primary" plain @click="deleteProduct(scope)">删除</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </template>
                    </div>
                    <div class="action-buttons">
                        <el-button type="danger" v-show="adjustmentBillInfo.status === '未审核'" @click="check">审核</el-button>
                        <el-button type="primary" v-show="adjustmentBillInfo.status === '未审核' || adjustmentBillInfo.status === null" @click="save" :loading="isSaveLoading">保存</el-button>
                        <el-button type="success" v-show="adjustmentBillInfo.status === '未审核'" @click="edit">修改</el-button>
                        <el-button type="warning" v-show="adjustmentBillInfo.status === '未审核'" @click="cancel">作废</el-button>
                    </div>

                </div>
            </div>
        </div>

        <el-dialog
                v-el-drag-dialog
                title="订单审核"
                :visible.sync="dialogCheck"
                width="350px"
                class="order-dialog">
            <div class="text-center">
                <span>是否确认商品调价无误？</span>
            </div>
            <div class="row-space text-center">
                <span class="label">如需修改，请取消审核</span>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="toConfirm" :loading="isCheckLoading">确定</el-button>
                <el-button type="info" @click="dialogCheck = false">取消</el-button>
            </span>
        </el-dialog>

        <el-dialog
                v-el-drag-dialog
                title="调价单作废"
                :visible.sync="dialogCancel"
                width="350px"
                class="order-dialog">
            <div class="text-center">
                <span>是否确认作废？</span>
            </div>
            <div class="row-space text-center">
                <span class="label">调价单作废后，调价将不再生效</span>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="toCancel" :loading="isCancelLoading">确定</el-button>
                <el-button type="info" @click="dialogCancel = false">取消</el-button>
            </span>
        </el-dialog>

        <el-dialog
                v-el-drag-dialog
                title="调价单审核"
                :visible.sync="dialogJudgeCheck"
                width="350px"
                class="order-dialog">
            <div class="text-center">
                <span>您的调价单有变更</span>
            </div>
            <div class="row-space text-center">
                <span class="label">请先保存后再审核！</span>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="checkToSave()" :loading="isCheckToSaveLoading">保存</el-button>
                <el-button type="info" @click="dialogJudgeCheck = false">取消</el-button>
            </span>
        </el-dialog>


    </div>
</template>

<script>
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import { MessageBox } from 'element-ui'
  import { productPaginatorDetail, storeProductPriceAdjustment, productPriceAdjustmentPaginator } from '@supplier/api/product'
  export default {
    name: 'productCreatBill', // 创建调价单加缓存, 查看不缓存
    directives: { elDragDialog },
    data() {
      const tomorrowDate = new Date((new Date()).getTime() + 24 * 60 * 60 * 1000)
      return {
        pickerOptions1: {
          disabledDate(time) {
            return time.getTime() < Date.now()
          }
        },
        dialogCheck: false,
        dialogCancel: false,
        billInfoRules: {
          effective_at: [
            { required: true, message: '请设置生效日期', trigger: 'blur' }
          ]
        },
        adjustmentBillInfo: {
          code: '', // 调价单编码
          created_at: this.dateFormat(new Date()).ymdhm, // 制单日期
          effective_at: this.dateFormat(tomorrowDate).ymd, // 生效时间
          number: null, // 调价商品数量
          author_name: this.$store.state.user.user.name, // 制单人账号
          status: null // 调价单状态
        },
        statusType: null,
        productListData: [],
        paginator: {
          page: null,
          limit: null,
          sort: null
        },
        more: {
          bar_code: null,
          status: '',
          supplier_id: this.$store.state.user.user.id
        },
        isLoading: false,
        billId: null,
        disabledPrint: false,
        isReadOnly: true,
        isCheckLoading: false,
        isCancelLoading: false,
        isSaveLoading: false,
        isEditProductListTag: 0,
        isEditEffectAtTag: 0,
        dialogJudgeCheck: false,
        isCheckToSaveLoading: false,
        routePath: null
      }
    },
    watch: {
      productListData: {
        deep: true,
        handler(value) {
          this.adjustmentBillInfo.number = value.length
          this.isEditProductListTag = this.isEditProductListTag + 1
        }
      },
      'adjustmentBillInfo.effective_at': {
        deep: true,
        handler() {
          this.isEditEffectAtTag = this.isEditEffectAtTag + 1
        }
      },
      'adjustmentBillInfo.status': {
        deep: true,
        handler(value) {
          if (value === '未审核') {
            this.statusType = 'edit'
          } else {
            this.statusType = 'view'
          }
        }
      },

    },
    mounted() {
      this.routePath = _.cloneDeep(this.$route.path)
      this.billId = parseInt(this.$route.params.id)
      // 修改
      if (this.$route.params.id) {
        this.disabledPrint = false
        this.isReadOnly = true
        this.getBillInfo(this.billId)
      } else { // 新增
        this.disabledPrint = true
        this.isReadOnly = false
      }
    },
    methods: {
      isNull(scope, key) {
        const value = scope.row[key]
        return (value == null || value === '') ? true : false
      },
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
        return {
          'ymd': year + '-' + month + '-' + day,
          'ymdhm': year + '-' + month + '-' + day + ' ' + hours + ':' + minutes
        }
      },
      decimal(scope, key) {
        let transformedInput = scope.row[key].toString()
        transformedInput = transformedInput.replace(/[^\d.]/g, '')
        transformedInput = transformedInput.replace(/^\./g, '')
        transformedInput = transformedInput.replace(/\.{2}/g, '.')
        transformedInput = transformedInput.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.')
        transformedInput = transformedInput.replace(/^(\-)*(\d+)\.(\d{2}).*$/, '$1$2.$3')

        this.$nextTick(() => {
          this.$set(this.productListData[scope.$index], key, transformedInput)
        })
      },
      toFormatFloat(scope, key) {
        let value = parseFloat(scope.row[key])
        if (isNaN(value)) {
          value = null
        }
        this.$set(this.productListData[scope.$index], key, value)
      },
      getBillInfo(id) {
        const paginator = {
          page: null,
          limit: null,
          sort: null
        }
        const more = {
          id: id
        }
        productPriceAdjustmentPaginator(paginator, more).then(response => {
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
        })
      },
      addProduct() {
        if (!this.more.bar_code) {
          MessageBox.confirm('请输入商品条码', '提示', {
            confirmButtonText: '确定',
            type: 'warning',
            showCancelButton: false
          })
          return
        }
        productPaginatorDetail(this.paginator, this.more).then(response => {
          const result = response.data.productPaginator.items
          if (!result.length) {
            MessageBox.confirm('没有此商品', '提示', {
              confirmButtonText: '确定',
              type: 'warning',
              showCancelButton: false
            })
          } else {
            for (const item of this.productListData) {
              if (item.bar_code === result[0].bar_code) {
                MessageBox.confirm('已经添加了该商品', '提示', {
                  confirmButtonText: '确定',
                  type: 'warning',
                  showCancelButton: false
                })
                return
              }
            }
            this.productListData.push(result[0])
          }
        })
      },
      check() {
        // 审核
        // 调价单有更改 在未保存的情况下去审核 应该先保存再审核
        if (this.isEditEffectAtTag > 1 || this.isEditProductListTag > 1) {
          this.dialogJudgeCheck = true
        } else {
          this.dialogCheck = true
        }

      },
      toConfirm() {
        const data = {
          'id': this.billId,
          'status': '已审核'
        }
        // this.isLoading = true
        this.isCheckLoading = true
        storeProductPriceAdjustment(data).then(response => {
          this.dialogCheck = false
          this.$notify({
            title: '成功',
            message: '操作成功',
            type: 'success'
          })
          this.$store.dispatch('priceAdjustment', 'fromCreatPriceAdjustment')
          this.$router.push({
            name: 'productPriceAdjustment'
          })
          this.$store.dispatch('delVisitedViews', { path: this.routePath })
        }).catch((error) => {
          this.dialogCheck = false
        }).finally(() => {
          // this.isLoading = false
          this.isCheckLoading = false
        })
      },
      checkToSave() {
        this.save('fromCheckTag')
      },
      save(tag) {
        // 保存

        for (const item of this.productListData) {
          if (item.trade_price === '' || item.trade_price === null) {
            MessageBox.confirm('订货单位含税进价为必填项', '提示', {
              confirmButtonText: '确定',
              type: 'warning',
              showCancelButton: false
            })
            return
          }
          if (item.one_price === '' || item.one_price === null) {
            MessageBox.confirm('一级供货价为必填项', '提示', {
              confirmButtonText: '确定',
              type: 'warning',
              showCancelButton: false
            })
            return
          }

          if (item.retail_price === '' || item.retail_price === null) {
            MessageBox.confirm('建议零售价为必填项', '提示', {
              confirmButtonText: '确定',
              type: 'warning',
              showCancelButton: false
            })
            return
          }
          const array = ['trade_price', 'one_price', 'two_price', 'three_price', 'four_price', 'five_price', 'retail_price']
          for (const obj of array) {
            if (item[obj] && item[obj].toString().length > 10) {
              MessageBox.confirm('价格最大长度为10位', '提示', {
                confirmButtonText: '确定',
                type: 'warning',
                showCancelButton: false
              })
              return
            }
          }
        }

        this.$refs.billInfoForm.validate((valid) => {
          if (valid) {
            if (!this.productListData.length) {
              MessageBox.confirm('请先添加商品', '提示', {
                confirmButtonText: '确定',
                type: 'warning',
                showCancelButton: false
              })
              return
            }

            const productListData = _.cloneDeep(this.productListData)
            productListData.forEach((item) => {
              if (item.brand || item.brand === null) {
                delete item.brand
              }
            })

            const data = {
              effective_at: this.adjustmentBillInfo.effective_at,
              number: this.adjustmentBillInfo.number,
              products: productListData
            }
            // 修改
            if (this.$route.params.id) {
              data.id = this.billId
            }
            this.isLoading = true
            if (tag === 'fromCheckTag') {
              this.isCheckToSaveLoading = true
            } else {
              this.isSaveLoading = true
            }
            this.isEditProductListTag = 1
            this.isEditEffectAtTag = 1
            storeProductPriceAdjustment(data).then(response => {
              const result = response.data.storeProductPriceAdjustment
              this.$notify({
                title: '成功',
                message: '操作成功',
                type: 'success'
              })
              if (tag === 'fromCheckTag') {
                this.dialogJudgeCheck = false
              } else {
                if (this.billId) {
                  // this.$router.push({
                  //   name: 'productPriceAdjustment'
                  // })
                } else {
                  this.$router.push({
                    name: 'productViewBill',
                    params: {
                      id: result.id,
                      tip: { content: result.code }
                    }
                  })
                  this.$store.dispatch('delVisitedViews', { name: 'productCreatBill' })
                }

              }

            }).finally(() => {
              this.isLoading = false
              this.isSaveLoading = false
              this.isCheckToSaveLoading = false
            })
          } else {
            return false
          }
        })
      },
      edit() {
        // 修改
        this.isReadOnly = false
      },
      cancel() {
        // 作废
        this.dialogCancel = true
      },
      toCancel() {
        const data = {
          'id': this.billId,
          'status': '已作废'
        }
        this.isCancelLoading = true
        storeProductPriceAdjustment(data).then(response => {
          this.$notify({
            title: '成功',
            message: '操作成功',
            type: 'success'
          })
          this.$store.dispatch('priceAdjustment', 'fromCreatPriceAdjustment')
          this.$router.push({
            name: 'productPriceAdjustment'
          })
          this.$store.dispatch('delVisitedViews', { path: this.routePath })
        }).finally(() => {
          this.isCancelLoading = false
        })
      },
      toPrintBill() {
        const { href } = this.$router.resolve({
          name: 'print',
          params: {
            id: this.billId
          }
        })
        window.open(href, '_blank')
      },
      deleteProduct(scope) {
        this.productListData.splice(scope.$index, 1)
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
        padding: 20px 0;
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
            object-fit: contain;
        }
    }

    .height100{
        height: 100%;
    }
    .scroller-y{
        overflow-y: auto;
    }
    .row-space{
        margin-top: 15px;
    }
    .line-h-28{
        line-height: 28px;
    }

</style>
