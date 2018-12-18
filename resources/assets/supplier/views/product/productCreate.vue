<template>
    <div class="app-container product-edit" style="overflow-y: auto" :style="{height: this.$store.getters.tableHeight + 220 + `px`}">
        <el-form :model="productForm"
                 :rules="rules"
                 ref="productForm"
                 label-width="100px"
                 @submit.native.prevent
                 class="demo-ruleForm groupBox">
            <el-row>
                <div class="group-title">
                    基本信息
                </div>
            </el-row>
                <el-row>
                    <el-col :xs="24" :sm="18" :md="14" :lg="10" :xl="8">
                        <el-form-item label="商品条码" prop="bar_code">
                            <el-input size="small"
                                      type="number"
                                      v-model="productForm.bar_code"
                                      placeholder="请输入商品条形码编号"
                                      :disabled="isEdit"
                                      class="inputBtn" clearable>
                                <el-button :disabled="isEdit" size="mini" slot="append" @click.native="getBaseProduct('bar_code')">查询</el-button>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <div class="tip">{{bar_code_tip}}</div>
                    </el-col>
                </el-row>
            <el-row :gutter="10">
                <el-col :xs="24" :sm="18" :md="14" :lg="10" :xl="8">
                    <el-form-item label="商品状态" prop="status">
                        <el-row>
                            <el-col :span="8" class="p-r-10">
                                <el-select size="small"
                                           v-model="productForm.status"
                                           placeholder="请选择商品状态"
                                           clearable>
                                    <el-option label="正常" value="正常"></el-option>
                                    <el-option label="停用" value="停用"></el-option>
                                </el-select>
                            </el-col>
                        </el-row>

                    </el-form-item>
                    <el-form-item label="商品名称" prop="name">
                        <el-input size="small" maxlength=45 v-model.trim="productForm.name" placeholder="请输入商品名称" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="所属品牌">
                        <el-row style="margin-right: -10px">
                            <el-col :span="8" class="p-r-10">
                                <el-select size="small" v-model="superLevel.super1" disabled placeholder="请选择一级">
                                </el-select>
                            </el-col>
                            <el-col :span="8" class="p-r-10">
                                <el-select size="small" v-model="superLevel.super2" disabled placeholder="请选择二级">
                                </el-select>
                            </el-col>
                            <el-col :span="8" class="p-r-10">
                                <el-select size="small" v-model="superLevel.super3" disabled placeholder="请选择三级">
                                </el-select>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="商品单位" prop="unit">
                        <el-input size="small" maxlength=15 v-model.trim="productForm.unit" placeholder="请输入单个商品的单位名称，例如：瓶" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="商品规格" required>
                        <el-row style="margin-right: -10px">
                            <el-col :span="16" class="p-r-10">
                                <el-form-item prop="spec_number" class="m-b-0">
                                    <el-input size="small"
                                              v-model.trim="productForm.spec_number"
                                              @input.native="oninput"
                                              name="spec_number"
                                              @blur="onBlur"
                                              placeholder="请输入商品规格数量" clearable></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8" class="p-r-10">
                                <el-form-item prop="spec_unit" class="m-b-0">
                                    <el-autocomplete
                                            class="inline-input"
                                            v-model="productForm.spec_unit"
                                            :fetch-suggestions="querySearch"
                                            placeholder="请输入规格单位"
                                            :maxlength=5
                                            clearable
                                    ></el-autocomplete>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="建议零售价" prop="retail_price">
                        <el-input size="small" v-model.trim="productForm.retail_price" placeholder="请输入建议零售价(单位：元)"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="retail_price"
                                  clearable>
                        <span slot="suffix">
                            元
                        </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="保质期" required>
                        <el-row style="margin-right: -10px">
                            <el-col :span="16" class="p-r-10">
                                <el-form-item prop="quality_period_num" class="m-b-0">
                                    <el-input size="small" v-model="productForm.quality_period_num"
                                              type="number" placeholder="请输入保质期" clearable></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="8" class="p-r-10">
                                <el-form-item prop="quality_period_unit" class="m-b-0">
                                    <el-select size="small" v-model="productForm.quality_period_unit" placeholder="请选择保质期单位" clearable>
                                        <el-option label="天" value="天"></el-option>
                                        <el-option label="月" value="月"></el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="产地" prop="make_place">
                        <el-input size="small" maxlength=190 v-model.trim="productForm.make_place" placeholder="请输入商品产地" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="生产厂商" prop="manufacturer">
                        <el-input maxlength=190 v-model.trim="productForm.manufacturer" placeholder="请输入生产厂商名称" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="商品图片" prop="pictures">
                        <el-upload
                                action="/graphql-file"
                                list-type="picture-card"
                                :file-list="picturesImage"
                                name='file'
                                :on-success="fileUploadSuccess"
                                @click.native="file('pictures')"
                                :on-remove="handleRemove"
                                multiple>
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="商品检测照片" prop="check_pictures">
                        <el-upload
                                action="/graphql-file"
                                list-type="picture-card"
                                :file-list="checkPictures"
                                name='file'
                                :on-success="fileUploadSuccess"
                                @click.native="file('check_pictures')"
                                :on-remove="handleRemove"
                                multiple>
                            <i class="el-icon-plus"></i>
                        </el-upload>
                    </el-form-item>
                </el-col>
            </el-row>
            <el-row>
                <div class="group-title">
                    订货信息
                </div>
            </el-row>
            <el-row>
                <div class="hint">注意：供货价将按照阶梯定价策略为您自动生成，允许手动修改。如未设定阶梯定价，则默认比例均乘以1。</div>
            </el-row>
            <el-row>
                <el-col :xs="24" :sm="18" :md="14" :lg="10" :xl="8">
                <div class="desc">
                    <div class="flex-l">
                        阶梯定价策略：
                    </div>
                    <div class="flex-r">
                        <div>
                            一级供货价为最低商品供货价，允许手动修改；<br/>
                            二级供货价默认为一级供货价*{{this.ListPrice.two || 1}}，允许手动修改；<br/>
                            三级供货价默认为一级供货价*{{this.ListPrice.three || 1}}，允许手动修改；<br/>
                            四级供货价默认为一级供货价*{{this.ListPrice.four || 1}}，允许手动修改；<br/>
                            五级供货价默认为一级供货价*{{this.ListPrice.five || 1}}，允许手动修改。
                        </div>
                    </div>
                </div>
                </el-col>
            </el-row>
            <el-row :gutter="10">
                <el-col :xs="24" :sm="18" :md="14" :lg="10" :xl="8">
                    <el-form-item label="订货单位" prop="order_unit">
                        <el-input size="small" maxlength=15 v-model.trim="productForm.order_unit" :span="8" placeholder="请输入订货的最小单位名称，例如：箱" clearable>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="包含单品数量" prop="single_num">
                        <el-input size="small" type="number" v-model="productForm.single_num" placeholder="请输入最小订货单位包含单品数量" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="订货规格">
                        <el-input size="small"
                                  v-model="orderSpec"
                                  readonly
                                  placeholder="系统自动生成">
                        </el-input>
                    </el-form-item>
                    <el-form-item label="批发含税进价" prop="trade_price">
                        <el-input size="small"
                                  v-model.trim="productForm.trade_price"
                                  placeholder="请输入批发含税进价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="trade_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="一级供货价" prop="one_price">
                        <el-input size="small"
                                  :disabled="isEdit"
                                  v-model.trim="productForm.one_price"
                                  placeholder="请输入一级供货价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="one_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="二级供货价" prop="two_price">
                        <el-input size="small"
                                  :disabled="isEdit"
                                  v-model.trim="productForm.two_price"
                                  placeholder="请输入二级供货价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="two_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="三级供货价" prop="three_price">
                        <el-input size="small"
                                  :disabled="isEdit"
                                  v-model.trim="productForm.three_price"
                                  placeholder="请输入三级供货价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="three_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="四级供货价" prop="four_price">
                        <el-input size="small"
                                  :disabled="isEdit"
                                  v-model.trim="productForm.four_price"
                                  placeholder="请输入四级供货价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="four_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="五级供货价" prop="five_price">
                        <el-input size="small"
                                  :disabled="isEdit"
                                  v-model.trim="productForm.five_price"
                                  placeholder="请输入五级供货价"
                                  @input.native="oninput"
                                  @blur="onBlur"
                                  name="five_price"
                                  clearable>
                                <span slot="suffix">
                                    元
                                </span>
                        </el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-col :xs="24" :sm="12" :md="12" :lg="10">
                        <el-button type="primary" @click="submitForm('productForm')">保存</el-button>
                        </el-col>
                        <el-col :xs="24" :sm="12" :md="12" :lg="10" v-if="!isEdit">
                        <el-button type="primary" @click="submitForm('productForm', 'reset')" :loading="isLoadingBtn">保存并添加下一个</el-button>
                        </el-col>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>
  import { notify, messageBox } from '@common/utils/helper'
  import { productPaginatorDetail, storeProduct, brandList, baseProduct } from '../../api/product'
  export default {
    name: 'productCreate',
    data() {
      const isNumber = (rule, value, callback) => {
        setTimeout(() => {
          if (rule.field === 'bar_code') {
            if (!(/^[0-9]\d*$/).test(value)) {
              callback(new Error('请输入数字'))
            } else {
              callback()
            }
          } else {
            if (!(/^[1-9]\d*$/).test(value)) {
              callback(new Error('请输入正整数'))
            } else {
              callback()
            }
          }
        }, 500)
      }
      const numLength = (rule, value, callback) => {
        setTimeout(() => {
          if (value && value.toString().length > 10) {
            callback(new Error('最大长度为10位'))
          } else {
            callback()
          }
        }, 100)
      }
      const maxNumLength = (rule, value, callback) => {
        setTimeout(() => {
          if (value && value.toString().length > 4) {
            callback(new Error('最大长度为4位'))
          } else {
            callback()
          }
        }, 100)
      }
      return {
        superLevel: {
          super1: '',
          super2: '',
          super3: ''
        },
        isEdit: false, // 是否是编辑
        picturesImage: [],
        checkPictures: [],
        type: null,
        dialogVisible: false,
        isLoadingBtn: false,
        productForm: {
          bar_code: null,
          status: '正常',
          name: null,
          brand_id: null,
          unit: null,
          spec_number: null,
          spec_unit: null,
          retail_price: null,
          quality_period: null,
          quality_period_num: null, // 保质期
          quality_period_unit: '月', // 保质期单位
          make_place: null,
          manufacturer: null,
          pictures: [],
          check_pictures: [],
          order_unit: null,
          trade_price: null,
          one_price: null,
          two_price: null,
          three_price: null,
          four_price: null,
          five_price: null
        },
        listQuery: {
          page: 1,
          limit: 1
        },
        more: {
          id: null
        },
        brandMore: {
          id: null
        },
        ListPrice: {
          two: 1,
          three: 1,
          four: 1,
          five: 1
        }, // 阶梯定价
        supplierId: null, // 供应商id
        bar_code_tip: null,
        restaurants: [
          { value: 'ml' },
          { value: 'g' },
          { value: '张' },
          { value: 'mA' },
          { value: 'm' }
        ],
        rules: {
          bar_code: [
            { required: true, message: '请输入商品条形码编号', trigger: 'blur' },
            { validator: isNumber, trigger: 'blur' }
          ],
          status: [
            { required: true, message: '请选择商品状态', trigger: 'change' }
          ],
          name: [
            { required: true, message: '请输入商品名称', trigger: 'blur' }
          ],
          unit: [
            { required: true, message: '请输入单个商品的单位名称', trigger: 'blur' }
          ],
          spec_number: [
            { required: true, message: '请输入商品规格数量', trigger: 'blur' },
            { validator: numLength, trigger: 'blur' }
          ],
          spec_unit: [
            { required: true, message: '请输入规格单位', trigger: 'change' }
          ],
          retail_price: [
            { required: true, message: '请输入建议零售价', trigger: 'blur' },
            { validator: numLength, trigger: 'blur' }
          ],
          quality_period_num: [
            { required: true, message: '请输入保质期', trigger: 'blur' },
            { validator: isNumber, trigger: 'blur' },
            { validator: numLength, trigger: 'blur' }
          ],
          quality_period_unit: [
            { required: true, message: '请选择保质期单位', trigger: 'change' }
          ],
          pictures: [
            { required: true, message: '请上传商品图片', trigger: 'change' }
          ],
          order_unit: [
            { required: true, message: '请输入订货的最小单位名称', trigger: 'blur' }
          ],
          single_num: [
            { required: true, message: '请输入最小订货单位包含单品数量', trigger: 'blur' },
            { validator: isNumber, trigger: 'blur' },
            { validator: maxNumLength, trigger: 'blur' }
          ],
          trade_price: [
            { required: true, message: '请输入批发含税进价', trigger: 'blur' },
            { validator: numLength, trigger: 'blur' }
          ],
          one_price: [
            { required: true, message: '请输入一级供货价', trigger: 'blur' },
            { validator: numLength, trigger: 'blur' }
          ],
          two_price: [
            { validator: numLength, trigger: 'blur' }
          ],
          three_price: [
            { validator: numLength, trigger: 'blur' }
          ],
          four_price: [
            { validator: numLength, trigger: 'blur' }
          ],
          five_price: [
            { validator: numLength, trigger: 'blur' }
          ]
        }
      }
    },
    activated() {
      // if (this.$route.params) {
      //   this.isEdit = false
      //   this.brandMore.id = this.$route.params.id
      //   this.productForm.brand_id = this.$route.params.id
      //   this.getBrand()
      // }
    },
    created() {
      if (this.$route.params) {
        this.isEdit = false
        this.brandMore.id = this.$route.params.id
        this.productForm.brand_id = this.$route.params.id
        this.getBrand()
      }
    },
    computed: {
      orderSpec() {
        if (this.productForm.single_num && this.productForm.spec_unit && this.productForm.spec_number) {
          return this.productForm.spec_number + this.productForm.spec_unit + '*' + this.productForm.single_num
        }
      }
    },
    watch: {
      'productForm.one_price': {
        deep: true,
        handler: function(val) {
          // 新增时的价格联动
          if (val && !this.isEdit) {
            this.changePrice(val)
          }
        }
      }
    },
    methods: {
      fomatFloat(num, pos = 2) {
        return Math.round(num * Math.pow(10, pos)) / Math.pow(10, pos)
      },
      // 控制保留两位小数
      oninput(e) {
        const val = e.target.value.match(/^\d*(\.?\d{0,2})/g)[0] || ''
        setTimeout(() => {
          if (this.productForm[e.target.name] !== val) {
            this.productForm[e.target.name] = val
          }
        }, 100)
      },
      onBlur(e) {
        const val = e.target.value
        val ? this.productForm[e.target.name] = parseFloat(val) : ''
      },
      // 价格的五级联动
      changePrice(val) {
        if (val) {
          this.productForm.two_price = this.fomatFloat(this.ListPrice.two * val)
          this.productForm.three_price = this.fomatFloat(this.ListPrice.three * val)
          this.productForm.four_price = this.fomatFloat(this.ListPrice.four * val)
          this.productForm.five_price = this.fomatFloat(this.ListPrice.five * val)
        } else {
          this.productForm.two_price = ''
          this.productForm.three_price = ''
          this.productForm.four_price = ''
          this.productForm.five_price = ''
        }
      },
      // 获取品牌名称
      getBrand(isLast = true) {
        brandList(this.brandMore).then(response => {
          const brandList = response.data.brandList[0]
          // 为末级品牌时获取阶梯定价/生产厂商
          if (isLast) {
            this.getListPrice(response.data.brandList[0].price_increase_ratio)
            this.isEdit ? '' : this.productForm.manufacturer = brandList.manufacturer
          }
          if (brandList.level === 3) {
            this.superLevel.super3 = brandList.name
            this.superLevel.super2 = brandList.parent_brand.name
            this.brandMore.id = brandList.parent_brand.id
            this.getBrand(isLast = false)
          } else if (brandList.level === 2) {
            this.superLevel.super2 = brandList.name
            this.superLevel.super1 = brandList.parent_brand.name
          } else if (brandList.level === 1) {
            this.superLevel.super1 = brandList.name
          }
        })
      },
      // 封装阶梯定价数据
      getListPrice(val) {
        if (val) {
          this.ListPrice.two = 1 + val.two_increase_ratio
          this.ListPrice.three = 1 + val.three_increase_ratio
          this.ListPrice.four = 1 + val.four_increase_ratio
          this.ListPrice.five = 1 + val.five_increase_ratio
        } else {
          this.ListPrice.two = 1
          this.ListPrice.three = 1
          this.ListPrice.four = 1
          this.ListPrice.five = 1
        }
      },
      // 查找商品字典
      getBaseProduct() {
        // 判重
        if (this.productForm.bar_code) {
          this.more.bar_code = this.productForm.bar_code
          this.more.supplier_id = this.$store.state.user.user.id
          this.more.check_base = true
        } else {
          return false
        }
        productPaginatorDetail(this.listQuery, this.more).then(response => {
          if (response.data.productPaginator.items.length) {
            this.bar_code_tip = '该商品条码已存在，不能重复录入！'
          } else {
            this.bar_code_tip = ''
            // 条码查询商品字典
            baseProduct({ 'bar_code': this.productForm.bar_code }).then(response => {
              if (response.data.baseProductQuery.length) {
                const baseProduct = response.data.baseProductQuery[0]
                this.productForm.name = baseProduct.name
                this.productForm.retail_price = baseProduct.retail_price
                // this.productForm.unit = baseProduct.unit
              }
            })
          }
        }).finally(() => {
        })
      },
      // 表单验证 - 保存
      submitForm(formName, reset) {
        this.$refs[formName].validate((valid) => {
          if (!valid) {
            messageBox.selfConfirm('您填写的信息验证未通过，请检查信息是否有误！')
            return false
          }
          this.isLoadingBtn = true
          const tempData = Object.assign({}, this.productForm)
          tempData.quality_period = tempData.quality_period_num + tempData.quality_period_unit
          tempData.brand ? delete tempData.brand : ''
          delete tempData.quality_period_num
          delete tempData.quality_period_unit
          storeProduct(tempData).then((response) => {
            const id = response.data.storeProduct.id
            this.isLoadingBtn = false
            notify.success('保存成功！')
            const removePath = this.$route.fullPath
            setTimeout(() => {
              if (reset) {
                this.resetForm(formName, tempData)
                document.querySelector('.product-edit').scrollTop = 0
              } else {
                this.resetForm(formName)
                this.$router.push({
                  name: 'product.detail',
                  params: {
                    id: id
                  }
                })
                this.$store.dispatch('delVisitedViews', { path: removePath })
              }
            }, 1000)
          }).finally(() => {
            this.isLoadingBtn = false
          })
        })
      },
      // 重置表单
      resetForm(formName, data) {
        this.picturesImage = []
        this.checkPictures = []
        this.$refs[formName].resetFields()
        if (data) {
          // 重置保留项
          this.productForm.spec_unit = data.spec_unit
          this.productForm.unit = data.unit
          this.productForm.quality_period_num = parseFloat(data.quality_period)
          this.productForm.quality_period_unit = data.quality_period.slice(-1)
          this.productForm.make_place = data.make_place
          this.productForm.order_unit = data.order_unit
          this.productForm.manufacturer = data.manufacturer
        }
      },
      // 规格单位
      querySearch(queryString, cb) {
        var restaurants = this.restaurants
        var results = queryString ? restaurants.filter(this.createFilter(queryString)) : restaurants
        // 调用 callback 返回建议列表的数据
        cb(results)
      },
      createFilter(queryString) {
        return (restaurant) => {
          return (restaurant.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0)
        }
      },
      // 上传图片
      file(type) {
        this.type = type
      },
      fileUploadSuccess(file) {
        setTimeout(() => {
          this.type ? this.productForm[this.type].push(file.url) : ''
        }, 100)
      },
      // 删除图片
      handleRemove(file, fileList) {
        setTimeout(() => {
          this.type ? this.productForm[this.type].splice(file.url, 1) : ''
        }, 100)
      }
    }
  }
</script>
<style lang="scss" scoped>
    .product-edit{
        .tip{
            font-size:12px;
            color: #f56c6c;
            line-height: 32px;
            margin-left: 20px;
        }
        .groupBox{
            background-color: #ffffff;
            padding: 0 20px;
            .desc, .hint{
                font-size: 13px;
                color: #E3821C;
                padding-bottom: 20px;
                display:flex;
            }
            .hint{
                padding-bottom: 5px;
            }
            .flex-l{
                flex: 0 0 92px
            }
            .flex-r{
                flex: 1;
            }
            .p-r-10{
                padding-right: 10px;
            }
        }
        .group-title{
            font-size: 13px;
            color: #333;
            padding: 30px 0;
        }
    }

</style>
