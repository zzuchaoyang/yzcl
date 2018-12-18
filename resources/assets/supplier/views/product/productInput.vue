<template>
    <div class="app-container">
        <div class="el-card is-always-shadow product-input-content no-select-arrow" :style="{height: this.$store.getters.tableHeight + 200 + 'px'}">
            <el-row class="height100" v-loading="treeLoading">
                <el-col :span="5" class="height100 out-container">
                    <div class="grid-content content-left height100 scroller-y inner-container">
                        <tree-level
                                :treeData="treeData"
                                :expendLastTreeId="expendLastTreeId"
                                :addLevel="addLevel"
                                @expandGetItem="expandGetItem"
                                @clickItem="clickItem"></tree-level>
                    </div>
                </el-col>
                <el-col :span="19" class="height100 out-container">
                    <div class="grid-content content-right height100 scroller-y inner-container">

                        <div v-if="treeData.length">
                            <div class="title-box bg-white flex-box">
                                <div>
                                    <span class="label">当前品牌:</span>
                                    <span class="value">{{currentBrand.name}}</span>
                                </div>
                                <div>
                                    <el-button type="primary" :disabled="currentBrandStatus === '停用'" @click.stop="addLevel(clickCurrentBrandData, numArray[viewBrandInfoForm.level] + '级')" v-if="viewBrandInfoForm.level < 3 && !currentBrandIsLast">
                                        +添加{{numArray[viewBrandInfoForm.level]}}级品牌
                                    </el-button>
                                    <el-button type="primary" :disabled="currentBrandStatus === '停用'" @click.stop="toAddProduct" v-else>
                                        添加商品
                                    </el-button>

                                    <el-button type="primary" :disabled="currentBrandStatus === '停用'" @click.stop="setLadderPrice" v-show="currentBrandIsLast">
                                        设定阶梯定价
                                    </el-button>
                                    <el-button type="primary" :disabled="currentBrandStatus === '停用'" @click.stop="setBulkImport = true" v-show="currentBrandIsLast">
                                        批量导入
                                    </el-button>
                                </div>
                            </div>

                            <div class="brand-box bg-white" v-if="viewBrandInfoForm.level < 3">
                                <span class="line-height30">下级品牌:</span>
                                <div class="brand-value">
                                <span v-for="item in nextLevelBrandList" :key="item.id" @click="clickItem(item)" class="cur-pointer">
                                    <el-tag type="info"  size="small" >
                                        {{item.name}}
                                    </el-tag>
                                </span>

                                </div>
                            </div>

                            <div class="brand-info-box bg-white">
                                <div class="box-title flex-box">
                                    <div>
                                        品牌信息
                                    </div>
                                    <div>
                                        <el-button type="primary" @click="updateBrand('viewBrandInfoForm')" :loading="isSavedLoading">保存</el-button>
                                    </div>
                                </div>

                                <el-form :model="viewBrandInfoForm" v-loading="updateInfoFormLoading"
                                         ref="viewBrandInfoForm" :rules="brandInfoRules" label-width="140px"
                                         class="brand-info-pd">
                                    <div class="clearfix form-info">
                                        <el-row :gutter="20">
                                            <el-col :span="2">
                                                <div class="grid-content">
                                                    <el-form-item label="LOGO" prop="pictures" label-width="70px" class="view-avatar" >
                                                        <el-upload
                                                                class="avatar-uploader"
                                                                action="/graphql-file"
                                                                :show-file-list="false"
                                                                :on-success="viewHandleAvatarSuccess">
                                                            <img v-if="viewBrandInfoForm.logo_pic" :src="viewBrandInfoForm.logo_pic"
                                                                 class="avatar">
                                                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                                        </el-upload>
                                                    </el-form-item>
                                                </div>
                                            </el-col>
                                            <el-col :span="21" :offset="1">
                                                <el-row :gutter="20">
                                                    <el-col :span="8">
                                                        <div class="grid-content">
                                                            <el-form-item label="品牌名称" prop="name">
                                                                <el-input v-model.trim="viewBrandInfoForm.name" maxlength="45" placeholder="请输入品牌名称"></el-input>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                    <el-col :span="8">
                                                        <div class="grid-content brand-level">
                                                            <el-form-item label="品牌级别">
                                                                <el-select v-model="viewBrandInfoForm.level" clearable
                                                                           placeholder="" disabled>
                                                                    <el-option
                                                                            v-for="item in brandLevelOptions"
                                                                            :key="item.value"
                                                                            :label="item.label"
                                                                            :value="item.value">
                                                                    </el-option>
                                                                </el-select>

                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                    <el-col :span="8">
                                                        <div class="grid-content brand-level">
                                                            <el-form-item label="上级品牌" prop="superLevelBrand">
                                                                <el-row v-if="viewBrandInfoForm.level !== 3">
                                                                    <el-col :span="24">
                                                                        <el-select
                                                                                v-model="viewBrandInfoForm.superLevelBrand.super1.name"
                                                                                clearable placeholder="无" disabled>
                                                                            <el-option
                                                                                    v-for="item in brandLevelOptions"
                                                                                    :key="item.value"
                                                                                    :label="item.label"
                                                                                    :value="item.value">
                                                                            </el-option>
                                                                        </el-select>
                                                                    </el-col>
                                                                </el-row>
                                                                <el-row v-else>
                                                                    <el-col :span="12"
                                                                            v-if="viewBrandInfoForm.superLevelBrand.super1.name">
                                                                        <el-select
                                                                                v-model="viewBrandInfoForm.superLevelBrand.super1.name"
                                                                                clearable placeholder="" disabled>
                                                                            <el-option
                                                                                    v-for="item in brandLevelOptions"
                                                                                    :key="item.value"
                                                                                    :label="item.label"
                                                                                    :value="item.value">
                                                                            </el-option>
                                                                        </el-select>
                                                                    </el-col>
                                                                    <el-col :span="12"
                                                                            v-if="viewBrandInfoForm.superLevelBrand.super2.name">
                                                                        <el-select
                                                                                v-model="viewBrandInfoForm.superLevelBrand.super2.name"
                                                                                clearable placeholder="" disabled>
                                                                            <el-option
                                                                                    v-for="item in brandLevelOptions"
                                                                                    :key="item.value"
                                                                                    :label="item.label"
                                                                                    :value="item.value">
                                                                            </el-option>
                                                                        </el-select>
                                                                    </el-col>
                                                                </el-row>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                </el-row>
                                                <el-row :gutter="20">
                                                    <el-col :span="8">
                                                        <div class="grid-content">
                                                            <el-form-item label="生产厂商" prop="manufacturer">
                                                                <el-input v-model.trim="viewBrandInfoForm.manufacturer" maxlength="190" placeholder="请输入生产厂商"></el-input>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                    <el-col :span="8">
                                                        <div class="grid-content">
                                                            <el-form-item label="商品数量（SKU）" prop="quantity">
                                                                <el-input type="text"
                                                                          v-model.trim="viewBrandInfoForm.quantity" disabled></el-input>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                    <el-col :span="8">
                                                        <div class="grid-content">
                                                            <el-form-item label="是否为末级" prop="is_last_stage">
                                                                <el-switch
                                                                        :disabled="viewBrandInfoForm.disableLastStage || (productListData.items.length !== 0)"
                                                                        v-model="viewBrandInfoForm.is_last_stage">
                                                                </el-switch>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                </el-row>
                                                <el-row :gutter="20">
                                                    <el-col :span="8">
                                                        <div class="grid-content">
                                                            <el-form-item label="是否启用" prop="status">
                                                                <el-switch
                                                                        @change="changeSwitch"
                                                                        v-model="viewBrandInfoForm.status">
                                                                </el-switch>
                                                            </el-form-item>
                                                        </div>
                                                    </el-col>
                                                </el-row>
                                            </el-col>
                                        </el-row>


                                    </div>


                                </el-form>
                            </div>

                            <div class="product-list bg-white">
                                <div class="box-title">
                                    商品列表
                                </div>
                                <div class="pd15">
                                    <el-form label-width="90px" class="pdt3 text-right" :inline="true" @submit.native.prevent>
                                        <el-form-item prop="name" label="商品名称">
                                            <el-input
                                                    clearable
                                                    v-model.trim="more.name"
                                                    placeholder="请输入商品名称"
                                                    @keyup.enter="getProductPaginatorList()"
                                                    @change="getProductPaginatorList()"
                                            ></el-input>
                                        </el-form-item>
                                    </el-form>
                                    <template>
                                        <el-table
                                                :data="productListData.items"
                                                v-loading="productListData.listLoading"
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
                                                    align="center">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="name"
                                                    align="center"
                                                    label="商品名称">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="brand.name"
                                                    align="center"
                                                    label="商品品牌">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="manufacturer"
                                                    align="center"
                                                    label="生产厂商">
                                            </el-table-column>
                                            <el-table-column
                                                    prop="order_unit"
                                                    align="center"
                                                    width="100"
                                                    label="订货单位">
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    width="100"
                                                    label="订货规格">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.spec_number + scope.row.spec_unit + '*' + scope.row.single_num}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    prop="one_price"
                                                    align="center"
                                                    width="120"
                                                    label="一级供货价（元）">
                                            </el-table-column>
                                            <el-table-column align="center" fixed="right" label="操作" width="180">
                                                <template slot-scope="scope">
                                                    <el-button type="primary" plain @click="viewDetail(scope.row)">查看
                                                    </el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </template>
                                    <el-row type="flex" justify="end" class="pagination-container">
                                        <el-pagination background
                                                       @size-change="handleSizeChange"
                                                       @current-change="handleCurrentChange"
                                                       :current-page="paginator.page"
                                                       :page-sizes="[10, 20, 30, 50]"
                                                       :page-size="paginator.limit"
                                                       layout="total, sizes, prev, pager, next, jumper"
                                                       :total="productListData.total">
                                        </el-pagination>
                                    </el-row>
                                </div>

                            </div>
                        </div>
                        <div v-else>
                            <div class="title-box bg-white flex-box">
                                <div>
                                    <span class="label">还未添加品牌</span>
                                </div>
                            </div>
                            <div class="brand-box">
                                <div class="brand-value">
                                    <el-button type="primary" @click.stop="addLevel(null, '一级')">新增一级品牌</el-button>
                                </div>
                            </div>
                        </div>

                    </div>
                </el-col>
            </el-row>
            <el-dialog v-el-drag-dialog :title="'添加' + currentLevel + '品牌'" :visible.sync="addBrandDialog" width="40%">
                <el-form :model="brandInfoForm" :rules="brandInfoRules" v-loading="dialogFormLoading" ref="brandInfoDialogForm" label-width="95px">
                    <el-form-item label="品牌级别" prop="level" class="brand-level">
                        <el-select v-model="brandInfoForm.level" clearable placeholder="" disabled>
                            <el-option
                                    v-for="item in brandLevelOptions"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="上级品牌" class="brand-level">
                        <el-row class="super-brand" v-if="brandInfoForm.level !== 3">
                            <el-col :span="24">
                                <el-form-item>
                                    <el-select v-model="brandInfoForm.superLevelBrand.super1.name" clearable placeholder="" disabled>
                                        <el-option
                                                v-for="item in brandLevelOptions"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row class="super-brand" v-else>
                            <el-col :span="12">
                                <el-form-item>
                                    <el-select v-model="brandInfoForm.superLevelBrand.super1.name" clearable placeholder="" disabled>
                                        <el-option
                                                v-for="item in brandLevelOptions"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item>
                                    <el-select v-model="brandInfoForm.superLevelBrand.super2.name" clearable placeholder="" disabled>
                                        <el-option
                                                v-for="item in brandLevelOptions"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="品牌名称" prop="name">
                        <el-input v-model.trim="brandInfoForm.name" maxlength="45" placeholder="请输入品牌名称"></el-input>
                    </el-form-item>
                    <el-form-item label="品牌LOGO" prop="pictures">
                        <el-upload
                                class="avatar-uploader"
                                action="/graphql-file"
                                :show-file-list="false"
                                :on-success="handleAvatarSuccess">
                            <img v-if="brandInfoForm.logo_pic" :src="brandInfoForm.logo_pic" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="生产厂商" prop="manufacturer">
                        <el-input v-model.trim="brandInfoForm.manufacturer" maxlength="190" placeholder="请输入生产厂商"></el-input>
                    </el-form-item>
                    <!--<el-form-item label="包含商品数量" prop="quantity">-->
                        <!--<el-input v-model="brandInfoForm.quantity"></el-input>-->
                    <!--</el-form-item>-->
                    <el-form-item label="是否为末级" prop="is_last_stage">
                        <el-switch
                                :disabled="currentLevel === '三级' ? true : false"
                                v-model="brandInfoForm.is_last_stage">
                        </el-switch>
                    </el-form-item>
                    <el-form-item label="是否启用" prop="status">
                        <el-switch
                                v-model="brandInfoForm.status">
                        </el-switch>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="addBrandDialog = false">取 消</el-button>
                    <el-button type="primary" @click="confirmBrand('brandInfoDialogForm')">确 定</el-button>
                </div>
            </el-dialog>
            <el-dialog v-el-drag-dialog title="设定阶梯定价" :visible.sync="setPriceDialog" width="40%" class="set-price-dialog">
                <el-form :model="viewBrandInfoForm" :rules="brandInfoRules" v-loading="setPriceFormLoading" ref="setPriceDialogForm" label-width="160px">
                    <div class="set-tips">
                        阶梯定价策略：一级供货价为阶梯定价基数，请在下方填写其他供货价基于一级供货价的上浮比例；例如：一级供货价为1元，二级供货价上浮比例为0.02，则二级供货价自动核算为1+（1*0.02）=1.02元，可手动进行修改。
                    </div>
                    <el-form-item label="当前品牌">
                        {{viewBrandInfoForm.name}}
                        <!--<el-input v-model.trim="viewBrandInfoForm.name"></el-input>-->
                    </el-form-item>
                    <el-form-item label="一级供货价上浮比例" prop="one_increase_ratio">
                        <el-input v-model.trim="viewBrandInfoForm.price_increase_ratio.one_increase_ratio" type="number" disabled required>
                            <span slot="suffix" style="display: block">一级供货价为基数，默认上浮比例为0</span>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="二级供货价上浮比例" prop="two_increase_ratio">
                        <el-input
                                v-model.trim="viewBrandInfoForm.price_increase_ratio.two_increase_ratio"
                                @keyup.enter="toFormatFloat('two_increase_ratio')"
                                @change="toFormatFloat('two_increase_ratio')"
                                @input.native="decimal('two_increase_ratio')"
                                placeholder="请输入二级供货价上浮比例"></el-input>
                    </el-form-item>
                    <el-form-item label="三级供货价上浮比例" prop="three_increase_ratio">
                        <el-input
                                v-model.trim="viewBrandInfoForm.price_increase_ratio.three_increase_ratio"
                                @keyup.enter="toFormatFloat('three_increase_ratio')"
                                @change="toFormatFloat('three_increase_ratio')"
                                @input.native="decimal('three_increase_ratio')"
                                placeholder="请输入三级供货价上浮比例"></el-input>
                    </el-form-item>
                    <el-form-item label="四级供货价上浮比例" prop="four_increase_ratio">
                        <el-input
                                v-model.trim="viewBrandInfoForm.price_increase_ratio.four_increase_ratio"
                                @keyup.enter="toFormatFloat('four_increase_ratio')"
                                @change="toFormatFloat('four_increase_ratio')"
                                @input.native="decimal('four_increase_ratio')"
                                placeholder="请输入四级供货价上浮比例"></el-input>
                    </el-form-item>
                    <el-form-item label="五级供货价上浮比例" prop="five_increase_ratio">
                        <el-input
                                v-model.trim="viewBrandInfoForm.price_increase_ratio.five_increase_ratio"
                                @keyup.enter="toFormatFloat('five_increase_ratio')"
                                @change="toFormatFloat('five_increase_ratio')"
                                @input.native="decimal('five_increase_ratio')"
                                placeholder="请输入五级供货价上浮比例"></el-input>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="setPriceDialog = false">取 消</el-button>
                    <el-button type="primary" @click="confirmSetPrice('setPriceDialogForm')">确 定</el-button>
                </div>
            </el-dialog>
            <el-dialog v-el-drag-dialog title="批量导入" :visible.sync="setBulkImport" width="30%" class="set-price-dialog">
                <el-row class="file">
                    <el-col :span="12" class="file-l">
                        <input id="upload" type="file" ref="input" @change="bulkImport(this, $event)" accept=".csv,
application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
                        <div class="tip">文件格式：xlsx</div>
                    </el-col>
                    <el-col :span="12" class="file-r">
                        <el-button :disabled="!productData.length" type="primary" size="medium" :loading="upLoading"  @click="upBulkImport" >
                            上传
                        </el-button>
                    </el-col>
                </el-row>
                <el-row class="text-center">
                    <div class="file-desc">
                        <span>请下载商品导入模板，按格式上传</span>
                        <el-button type="primary"  @click="downBulkImport" >
                            下载商品模板
                        </el-button>
                    </div>
                </el-row>
            </el-dialog>

        </div>
    </div>
</template>

<script>
  import TreeLevel from '@supplier/components/treeLevel'
  import { storeBrand, brandList, productPaginatorList, batchImport } from '@supplier/api/product'
  import { listHelper } from '@common/utils/listHelper'
  import { notify, messageBox } from '@common/utils/helper'
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import { MessageBox } from 'element-ui'
  import { downFile } from '../../api/common'
  export default {
    name: 'productInput',
    components: { TreeLevel },
    directives: { elDragDialog },
    data() {
      const checkNumber = (rule, value, callback) => {
        const isNumberReg = /^([1-9]\d*|[0]{1,1})$/
        if (value === '' || value === null) {
          return callback(new Error('请输入商品数量'))
        }
        if (!isNumberReg.test(value)) {
          callback(new Error('商品数量为正整数'))
        } else {
          callback()
        }
      }
      return {
        treeLoading: true,
        numArray: ['一', '二', '三', '四', '五'],
        brandInfoRules: {
          name: [
            { required: true, message: '请输入品牌名称', trigger: 'blur' }
          ],
          manufacturer: [
            { required: false, message: '请输入生产厂商' }
          ],
          quantity: [
            { required: false, validator: checkNumber }
          ],
          is_last_stage: [
            { required: false, message: '请设置该品牌是否为末级' }
          ],
          status: [
            { required: false, message: '请设置该品牌是否启用' }
          ]
        },
        productListData: {
          items: [],
          total: null,
          listLoading: false
        },
        currentPage: 1,
        addBrandDialog: false,
        setPriceDialog: false,
        setBulkImport: false,
        dialogFormLoading: false,
        updateInfoFormLoading: false,
        setPriceFormLoading: false,
        brandLevelOptions: [
          {
            value: 1,
            label: '一级'
          },
          {
            value: 2,
            label: '二级'
          },
          {
            value: 3,
            label: '三级'
          },
          {
            value: 4,
            label: '四级'
          },
          {
            value: 5,
            label: '五级'
          }
        ],
        brandList: [],
        currentBrand: {
          id: null,
          name: null
        },
        currentBrandIsLast: false,
        currentBrandStatus: '正常',
        // 新增品牌信息
        brandInfoForm: { // 品牌信息
          name: '', // 品牌名称
          level: 1, // 品牌级别
          logo_pic: '', // 皮牌logo
          manufacturer: '', // 生产厂商
          // quantity: 10, // 商品数量
          is_last_stage: false, // 是否为末级
          status: false, // 是否启用
          superLevelBrand: {
            super1: {
              id: null,
              name: null
            },
            super2: {
              id: null,
              name: null
            }
          } // 上级品牌
        },
        // 查看品牌信息
        viewBrandInfoForm: { // 品牌信息
          id: null,
          name: null, // 品牌名称
          logo_pic: null,
          level: null, // 品牌级别
          manufacturer: '', // 生产厂商
          quantity: null, // 商品数量
          is_last_stage: false, // 是否为末级
          status: false, // 是否启用
          price_increase_ratio: {
            one_increase_ratio: null,
            two_increase_ratio: null,
            three_increase_ratio: null,
            four_increase_ratio: null,
            five_increase_ratio: null
          }, // 阶梯价格
          superLevelBrand: {
            super1: {
              id: null,
              name: null
            },
            super2: {
              id: null,
              name: null
            }
          } // 上级品牌
        },
        nextLevelBrandList: [], // 下级品牌
        currentLevel: null,
        getBrandOneMore: {
          'pid': 0,
          'level': 1,
          'status': '正常'
        },
        brandTreeData: {
          data: []
        },
        treeData: [],
        paginator: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        more: {
          brand_id: null,
          name: null,
          status: '',
          supplier_id: this.$store.state.user.user.id
        },
        addProductParams: {
          super1: null,
          super2: null,
          super3: null
        },
        oneLevel: {
          data: []
        },
        twoLevel: {
          data: []
        },
        threeLevel: {
          data: []
        },
        clickCurrentBrandData: null,
        selectedBrandId: null,
        isSavedLoading: false,
        expendLastTreeId: null,
        productData: [],
        upLoading: false,
        firstAddLevel: false
      }
    },
    created() {
      // 先获取第一级品牌,通过点击品牌调接口获取下级品牌
      // this.getBrandList(this.getBrandOneMore).then((result) => {
      //   this.$set(this.brandTreeData, 'data', result)
      //   this.assignBrandInfo(this.brandTreeData.data[0])
      //   this.getChildrenResult(this.brandTreeData.data[0], 'getNextLevelTag')
      //
      // })
      // 一次获取所有品牌数据
      this.getOneLevelData()

      // 从保存商品进来
      this.selectedBrandId = this.$route.params.id || null
    },
    watch: {
      // 'viewBrandInfoForm.id': {
      //   deep: true,
      //   handler(value) {
      //     if (value) {
      //       this.more.brand_id = value
      //       setTimeout(() => {
      //         this.getProductPaginatorList()
      //       }, 100)
      //     }
      //   }
      // },
      'viewBrandInfoForm.status': {
        deep: true,
        handler(value) {
          if (value === '正常' || value === true) {
            this.viewBrandInfoForm.status = true
          } else {
            this.viewBrandInfoForm.status = false
          }
        }
      }
    },
    methods: {
      decimal(key) {
        let transformedInput = this.viewBrandInfoForm.price_increase_ratio[key].toString()
        transformedInput = transformedInput.replace(/[^\d.]/g, '')
        transformedInput = transformedInput.replace(/^\./g, '')
        transformedInput = transformedInput.replace(/\.{2}/g, '.')
        transformedInput = transformedInput.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.')
        transformedInput = transformedInput.replace(/^(\-)*(\d+)\.(\d{2}).*$/, '$1$2.$3')

        this.$nextTick(() => {
          this.$set(this.viewBrandInfoForm.price_increase_ratio, key, transformedInput)
        })
      },
      toFormatFloat(key) {
        let value = parseFloat(this.viewBrandInfoForm.price_increase_ratio[key])
        if (isNaN(value)) {
          value = null
        }
        this.$set(this.viewBrandInfoForm.price_increase_ratio, key, value)
      },
      getOneLevelData() {
        this.getBrandList({ level: 1 }).then((result) => {
          this.$set(this.oneLevel, 'data', result)
          this.oneLevel.data.forEach((item) => {
            item.children = []
          })
          this.getTwoLevelData()
        })
      },
      getTwoLevelData() {
        this.getBrandList({ level: 2 }).then((result) => {
          this.$set(this.twoLevel, 'data', result)
          this.twoLevel.data.forEach((item) => {
            item.children = []
          })
          this.getThreeLevelData()
        })
      },
      getThreeLevelData() {
        this.getBrandList({ level: 3 }).then((result) => {
          this.$set(this.threeLevel, 'data', result)
          this.packageData()
        })
      },
      packageData() {
        this.oneLevel.data.forEach((item, index) => {
          this.twoLevel.data.forEach((value, idx) => {
            if (value.parent_brand && (item.id === value.parent_brand.id)) {
              if (!item.children) {
                item.children = []
              }
              item.children.push(value)
            }
          })
        })
        this.twoLevel.data.forEach((item, index) => {
          this.threeLevel.data.forEach((value, idx) => {
            if (value.parent_brand && (item.id === value.parent_brand.id)) {
              if (!item.children) {
                item.children = []
              }
              item.children.push(value)
            }
          })
        })
        this.treeLoading = false
        this.$set(this.brandTreeData, 'data', this.oneLevel.data)
        this.treeData = []
        this.brandTreeData.data.forEach((item) => {
          this.treeData.push(item)
        })

        if (this.selectedBrandId) {
          // 从保存商品进来
          this._getCurrentOneLevel()
        } else {
          // 点击商品录入进来
          if (this.brandTreeData.data.length) {
            this.assignBrandInfo(this.brandTreeData.data[0], true)
            this.getChildrenResult(this.brandTreeData.data[0], 'getNextLevelTag')
          }
        }
      },
      //  给brandInfoFor赋值(查看品牌信息)
      assignBrandInfo(item, fromProduct) {
        let disableLastStage = false
        if (item.level < 3) {
          // 没有子集
          if (!!(item.children && item.children.length || 0)) {
            disableLastStage = true
          }
        } else if (item.level === 3) {
          disableLastStage = true
        }

        this.viewBrandInfoForm = { // 品牌信息
          id: item.id,
          name: item.name, // 品牌名称
          logo_pic: item.logo_pic, // 品牌名称
          level: item.level, // 品牌级别
          manufacturer: item.manufacturer, // 生产厂商
          quantity: item.quantity || 0, // 商品数量
          is_last_stage: item.is_last_stage, // 是否为末级
          disableLastStage: disableLastStage,  // 判断不能设置为末级(有children 或者 为第三级)
          status: item.status, // 是否启用
          price_increase_ratio: {
            one_increase_ratio: 0,
            two_increase_ratio: (item.price_increase_ratio && item.price_increase_ratio.two_increase_ratio) || null,
            three_increase_ratio: (item.price_increase_ratio && item.price_increase_ratio.three_increase_ratio) || null,
            four_increase_ratio: (item.price_increase_ratio && item.price_increase_ratio.four_increase_ratio) || null,
            five_increase_ratio: (item.price_increase_ratio && item.price_increase_ratio.five_increase_ratio) || null
          }, // 阶梯价格
          superLevelBrand: {
            super1: {
              id: null,
              name: null
            },
            super2: {
              id: null,
              name: null
            }
          } // 上级品牌
        }

        // 从添加商品过来且为第三级的时候 一级品牌从接口中读取
        if (this.selectedBrandId && fromProduct) {
          if (item.level === 3) {
            this.getBrandList({ id: item.parent_brand.id }).then((result) => {
              if (item.parent && item.parent.parent) {
              } else {
                item.parent = {
                  parent: null,
                  id: null,
                  name: null
                }
              }
              item.parent.parent = result[0].parent_brand
              item.parent.id = result[0].id
              item.parent.name = result[0].name
              this.viewBrandInfoForm.superLevelBrand = this.viewCurrentLevel(item)
              this.expandTree(this.viewBrandInfoForm.superLevelBrand)
            })
          } else if (item.level === 2) {
            item.parent = item.parent_brand
            this.viewBrandInfoForm.superLevelBrand = this.viewCurrentLevel(item)
            this.expandTree(this.viewBrandInfoForm.superLevelBrand)
          } else if (item.level === 1) {
            this.viewBrandInfoForm.superLevelBrand = this.viewCurrentLevel(item)
          }

        } else {
          this.viewBrandInfoForm.superLevelBrand = this.viewCurrentLevel(item)
        }

        this.currentBrand = {
          id: _.cloneDeep(item.id),
          name: _.cloneDeep(item.name),
        }
        this.currentBrandIsLast = _.cloneDeep(item.is_last_stage)
        this.currentBrandStatus = _.cloneDeep(item.status)
      },
      expandTree(value) {
        this.treeData.forEach((tree1) => {
          if (value.super1.id === tree1.id) {
            tree1._expanded = true
            tree1._show = true
            if (tree1.children) {
              tree1.children.forEach((tree2) => {
                if (value.super2.id === tree2.id) {
                  tree2._expanded = true
                  tree2._show = true
                }
              })
            }
          }
        })
        // 最后一级选中状态
        if (this.viewBrandInfoForm.level === 3) {
          this.expendLastTreeId = value.super3.id
        }

      },
      getBrandList(more) {
        return new Promise((resolve, reject) => {
          return brandList(more).then(response => {
            const result = response.data.brandList
            resolve(result)
          }).catch(error => {
            reject(error)
          })
        })
      },
      _getCurrentOneLevel() {
        this.getBrandList({ id: this.selectedBrandId }).then((result) => {
          this._selectedBrand = result[0]
          this._getCurrentTwoLevel()
        })
      },
      _getCurrentTwoLevel() {
        this.getBrandList({ pid: this.selectedBrandId }).then((result) => {
          if (!this._selectedBrand.children) {
            this._selectedBrand.children = []
          }
          this.$set(this._selectedBrand, 'children', result)
          this.assignBrandInfo(this._selectedBrand, true)
          this.getChildrenResult(this._selectedBrand, 'getNextLevelTag')
        })
      },
      handleSizeChange(val) {
        this.paginator.limit = val
        this.getProductPaginatorList()
      },
      handleCurrentChange(val) {
        this.paginator.page = val
        this.getProductPaginatorList('noResetPage')
      },
      viewDetail(row) {
        this.$router.push({
          name: 'product.detail',
          params: {
            id: row.id
          }
        })
      },
      // 点击树添加品牌
      addLevel(item, currentLevel) {
        this.addBrandDialog = true
        this.currentLevel = currentLevel
        // 添加一级 item 为null
        if (!item) {
          this.firstAddLevel = true
          item = {
            'level': 0,
            'pid': 0,
            'name': null,
            'logo_pic': '', // 品牌logo商品数量
            'manufacturer': '',
            'is_last_stage': false,
            'status': true,
            'superLevelBrand': { // 上级品牌(只是渲染内容)
              'super1': null,
              'super2': null
            }
          }
        } else {
          this.firstAddLevel = false
        }

        // 品牌信息
        this.brandInfoForm = {
          level: item.level + 1, // 品牌级别
          pid: item.id, // 上级品牌
          name: null, // 品牌名称
          logo_pic: null, // 品牌logo
          manufacturer: item.manufacturer, // 生产厂商
          // quantity: null, // 商品数量
          is_last_stage: currentLevel === '三级' ? true : false, // 是否为末级
          status: true, // 是否启用
          superLevelBrand: { // 上级品牌(只是渲染内容)
            super1: this.getSuperLevel(item).super1,
            super2: this.getSuperLevel(item).super2
          }
        }
      },
      viewCurrentLevel(item) {
        // 读取该品牌的商品
        this.more.brand_id = this.viewBrandInfoForm.id
        this.more.name = null
        this.getProductPaginatorList()

        // 获取上级品牌
        let super1 = {}
        let super2 = {}
        let super3 = {}
        if (item.level === 1) {
          super1 = {
            id: null,
            name: '无'
          }
        } else if (item.level === 2) {
          super1 = {
            id: item.parent && item.parent.id,
            name: item.parent && item.parent.name
          }
          super2 = {}
        } else if (item.level === 3) {
          super1 = {
            id: item.parent && item.parent.parent && item.parent.parent.id,
            name: item.parent && item.parent.parent && item.parent.parent.name
          }
          super2 = {
            id: item.parent && item.parent.id,
            name: item.parent && item.parent.name
          }
          super3 = {
            id: item.id,
            name: item.name
          }
        }
        this.addProductParams.super1 = super1
        this.addProductParams.super2 = super2
        this.addProductParams.super3 = super3

        return {
          super1: super1,
          super2: super2,
          super3: super3
        }
      },
      getSuperLevel(item) {
        let super1 = {}
        let super2 = {}
        if (item.level === 0) {
          super1 = {
            id: null,
            name: '无'
          }
          super2 = {}
        } else if (item.level === 1) {
          super1 = {
            id: item.id,
            name: item.name
          }
          super2 = {}
        } else if (item.level === 2) {
          super1 = {
            id: item.parent && item.parent.id,
            name: item.parent && item.parent.name
          }
          super2 = {
            id: item.id,
            name: item.name
          }
        }
        return {
          super1: super1,
          super2: super2
        }
      },
      findCurrentBrant(level, id, data, action) {
        let curIdx
        if (level === 1) {
          curIdx = this._.findIndex(this.brandTreeData.data, function(o) { return o.id === id })
          if (curIdx !== -1) {
            if (action === 'update') {
              this._.forIn(data, (val, key) => {
                if (key !== 'children') {
                  this.brandTreeData.data[curIdx][key] = val
                }
              })
              if (this.brandTreeData.data[curIdx].children) {
                this._.forEach(this.brandTreeData.data[curIdx].children, (item2) => {
                  item2.status = data.status
                  if (item2.children) {
                    this._.forEach(item2.children, (item3) => {
                      item3.status = data.status
                    })
                  }
                })
              }
            } else {
              if (!this.brandTreeData.data[curIdx].children) {
                this.brandTreeData.data[curIdx].children = []
              }
              this.brandTreeData.data[curIdx].children.push(data)
            }
          }
        } else if (level === 2) {
          this._.each(this.brandTreeData.data, (item, index) => {
            curIdx = this._.findIndex(item.children, function(o) { return o.id === id })
            if (curIdx !== -1) {
              if (action === 'update') {
                this._.forIn(data, (val, key) => {
                  if (key !== 'children') {
                    item.children[curIdx][key] = val
                  }
                })
                if (item.children[curIdx].children) {
                  this._.forEach(item.children[curIdx].children, (item3) => {
                    item3.status = data.status
                  })
                }

              } else {
                if (!item.children[curIdx].children) {
                  item.children[curIdx].children = []
                }
                item.children[curIdx].children.push(data)
              }
            }
          })
        } else if (level === 3) {
          this._.each(this.brandTreeData.data, (item, index) => {
            if (item.children && item.children.length) {
              this._.each(item.children, (obj, i) => {
                curIdx = this._.findIndex(obj.children, function(o) { return o.id === id })
                if (curIdx !== -1) {
                  if (action === 'update') {
                    this._.forIn(data, (val, key) => {
                      if (key !== 'children') {
                        obj.children[curIdx][key] = val
                      }
                    })
                  } else {
                    if (!obj.children[curIdx].children) {
                      obj.children[curIdx].children = []
                    }
                    obj.children[curIdx].children.push(data)
                  }
                }
              })
            }
          })
        }


        this.treeData = []
        this.brandTreeData.data.forEach((item) => {
          this.treeData.push(item)
        })
      },
      confirmBrand(ref) {
        this.$refs[ref].validate((valid) => {
          if (valid) {
            const brandInfoForm = _.cloneDeep(this.brandInfoForm)
            brandInfoForm.status = brandInfoForm.status ? '正常' : '停用'
            delete brandInfoForm.superLevelBrand
            delete brandInfoForm.quantity
            this.dialogFormLoading = true
            storeBrand(brandInfoForm).then(response => {
              this.$notify({
                title: '成功',
                message: '操作成功',
                type: 'success'
              })
              this.dialogFormLoading = false
              this.addBrandDialog = false
              const result = response.data.storeBrand
              if (!result.children) {
                result.children = []
              }
              if (this.currentLevel === '一级') {
                this.brandTreeData.data.push(result)
                this.treeData = []
                this.brandTreeData.data.forEach((item) => {
                  this.treeData.push(item)
                })
                if (this.treeData.length === 1 && this.firstAddLevel) {
                  this.assignBrandInfo(this.treeData[0])
                }
              } else if (this.currentLevel === '二级') {
                this.findCurrentBrant(1, brandInfoForm.pid, result)
              } else if (this.currentLevel === '三级') {
                this.findCurrentBrant(2, brandInfoForm.pid, result)
              }
            }).finally(() => {
              this.dialogFormLoading = false
            })
          } else {
            return false
          }
        })
      },
      updateBrand(ref) {
        this.$refs[ref].validate((valid) => {
          if (valid) {
            this.toStoreBrand()
          } else {
            return false
          }
        })
      },
      toStoreBrand(tag) {
        const viewBrandInfoForm = _.cloneDeep(this.viewBrandInfoForm)
        viewBrandInfoForm.status = viewBrandInfoForm.status ? '正常' : '停用'
        delete viewBrandInfoForm.superLevelBrand
        delete viewBrandInfoForm.pid
        delete viewBrandInfoForm.disableLastStage
        if (tag === 'setLadderPriceTag') {
          this.setPriceFormLoading = true
        } else {
          this.updateInfoFormLoading = true
          this.isSavedLoading = true
        }
        storeBrand(viewBrandInfoForm).then(response => {
          this.$notify({
            title: '成功',
            message: '操作成功',
            type: 'success'
          })
          if (tag === 'setLadderPriceTag') {
            this.setPriceFormLoading = false
            this.setPriceDialog = false
          } else {
            this.updateInfoFormLoading = false
          }
          const result = response.data.storeBrand
          this.currentBrand = {
            id: result.id,
            name: result.name
          }
          this.currentBrandIsLast = result.is_last_stage
          this.currentBrandStatus = result.status
          if (!result.children) {
            result.children = []
          }
          this.findCurrentBrant(viewBrandInfoForm.level, viewBrandInfoForm.id, result, 'update')
          this.getProductPaginatorList()
        }).finally(() => {
          if (tag === 'setLadderPriceTag') {
            this.setPriceFormLoading = false
          } else {
            this.updateInfoFormLoading = false
            this.isSavedLoading = false
          }
        })
      },
      // 点击品牌名称展示右边数据
      clickItem(item) {
        this.clickCurrentBrandData = item
        this.expendLastTreeId = null
        this.assignBrandInfo(item, false)
        this.getChildrenResult(item, 'getNextLevelTag')
      },
      // 点击展开
      expandGetItem(item) {
        // this.getChildrenResult(item)
      },
      getChildrenResult(item, tag) {
        // const more = {
        //   'pid': item.id && item.id,
        //   'level': item.level + 1,
        //   'status': '正常'
        // }
        // this.getBrandList(more).then((result) => {
        //   this.$set(item, 'children', result)
        //   if (tag === 'getNextLevelTag') {
        //     this.nextLevelBrandList = item.children
        //   }
        // })
        if (tag === 'getNextLevelTag') {
          this.nextLevelBrandList = item.children
        }
      },
      toAddProduct() {
        // this.$store.dispatch('delVisitedViews', { name: 'productCreate' })
        this.$router.push({
          name: 'productCreate',
          params: {
            id: this.viewBrandInfoForm.id
          }
        })
      },
      getProductPaginatorList(tag) {
        if (tag !== 'noResetPage') {
          this.paginator.page = 1
        }
        this.productListData.listLoading = true
        productPaginatorList(this.paginator, this.more).then(response => {
          listHelper.setList(this.productListData, this.paginator, response.data.productPaginator)
        }).finally(() => {
          this.productListData.listLoading = false
        })
      },
      setLadderPrice() {
        this.$refs.viewBrandInfoForm.validate((valid) => {
          if (valid) {
            this.setPriceDialog = true
          } else {
            return false
          }
        })
      },
      confirmSetPrice(ref) {
        // 设定阶梯定价去更新品牌信息
        this.$refs[ref].validate((valid) => {
          if (valid) {
            this.toStoreBrand('setLadderPriceTag')
          } else {
            return false
          }
        })
      },
      handleAvatarSuccess(res, file) {
        this.brandInfoForm.logo_pic = res.url
      },
      viewHandleAvatarSuccess(res, file) {
        this.viewBrandInfoForm.logo_pic = res.url
      },
      changeSwitch(value) {
        if (value) {
          MessageBox.confirm('是否确认启用当前品牌（下级品牌和商品将同步启用）？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            this.viewBrandInfoForm.status = value
          }, () => {
            this.viewBrandInfoForm.status = !value
          })
        } else {
          MessageBox.confirm('是否确认禁用当前品牌（禁用后将不能添加子品牌或者商品,下级品牌和商品将同步禁用）？', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
          }).then(() => {
            this.viewBrandInfoForm.status = value
          }, () => {
            this.viewBrandInfoForm.status = !value
          })
        }
      },
      downBulkImport() {
        window.location.href = 'https://media.1fangxin.cn/%E6%83%A0%E5%9F%9F%E9%80%9A%E5%AE%9D%E5%95%86%E5%93%81%E6%89%B9%E9%87%8F%E5%AF%BC%E5%85%A5%E6%A8%A1%E6%9D%BF.xlsx'
      },
      // 批量导入商品
      upBulkImport() {
        if (this.productData && this.productData.length) {
          this.upLoading = true
          batchImport(this.productData).then(response => {
            this.upLoading = false
            const code = response.data.batchImportProduct.code
            const format = response.data.batchImportProduct.format
            if (code) {
              messageBox.selfConfirm('您填写第' + code.substring(0, code.length - 1) + '行的商品条码已存在，不能重复录入！')
            }
            if (format) {
              messageBox.selfConfirm('您填写第' + format.substring(0, format.length - 1) + '行的格式有误，请检查信息！')
            }
            if (!code && !format) {
              notify.success('批量导入成功！')
              this.setBulkImport = false
              setTimeout(() => {
                // window.location.reload()
              }, 500)
            }
          }).finally(() => {
            this.$refs.input.value = ''
          })
        }
      },
      bulkImport(obj, event) {
        const _this = this
        // let inputDOM = this.$refs.inputer
        const tHeader = {
          '商品条码': 'bar_code',
          '商品名称': 'name',
          '商品单位': 'unit',
          '商品规格数量': 'spec_number',
          '规格单位': 'spec_unit',
          '建议零售价（元）': 'retail_price',
          '保质期时长（月）': 'quality_period',
          '产地': 'make_place',
          '生产厂商': 'manufacturer',
          '订货单位': 'order_unit',
          '包含单品数量': 'single_num',
          '批发含税进价（元）': 'trade_price',
          '一级供货价（元）': 'one_price',
          '二级供货价（元）': 'two_price',
          '三级供货价（元）': 'three_price',
          '四级供货价（元）': 'four_price',
          '五级供货价（元）': 'five_price'
        }
        // 通过DOM取文件数据
        this.file = event.currentTarget.files[0]
        const rABS = false // 是否将文件读取为二进制字符串
        const f = this.file
        const reader = new FileReader()
        FileReader.prototype.readAsBinaryString = function(f) {
          let binary = ''
          const rABS = false // 是否将文件读取为二进制字符串
          // var pt = this
          let wb // 读取完成的数据
          let outdata
          let reader = new FileReader()
          reader.onload = function(e) {
            var bytes = new Uint8Array(reader.result)
            var length = bytes.byteLength
            for (var i = 0; i < length; i++) {
              binary += String.fromCharCode(bytes[i])
            }
            var XLSX = require('xlsx')
            if (rABS) {
              wb = XLSX.read(btoa(fixdata(binary)), { // 手动转化
                type: 'base64'
              })
            } else {
              wb = XLSX.read(binary, {
                type: 'binary'
              })
            }
            outdata = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])// outdata就是你想要的东西
            this.da = [...outdata]
            _this.productData = []
            this.da.map(v => {
              let obj = {}
              _.forEach(tHeader, function(value, key) {
                if (value === 'make_place' || value === 'manufacturer' || value === 'two_price' || value === 'three_price' || value === 'four_price' || value === 'five_price') {
                  obj[value] = v[key] || null
                } else {
                  if (!v[key]) {
                    messageBox.selfConfirm('您上传的文件有误或填写的信息不完整，请检查！')
                    _this.$refs.input.value = ''
                    return false
                  }
                  if (value === 'quality_period') {
                    obj[value] = v[key] + '月'
                  } else {
                    obj[value] = v[key]
                  }
                }
                obj.brand_id = _this.viewBrandInfoForm.id
                // 暂时写固定的默认图片
                obj.pictures = ['http://oyb630403.bkt.clouddn.com/assets/images/wx/hytb_defaule.jpg']
                obj.check_pictures = []
                obj.status = '正常'
              })
              _this.productData.push(obj)
            })
          }
          reader.readAsArrayBuffer(f)
        }
        if (rABS) {
          reader.readAsArrayBuffer(f)
        } else {
          reader.readAsBinaryString(f)
        }
      }
    }
  }
</script>
<style lang="scss" scoped>
    .form-info{
        /*display: flex;*/
    }

    .text-center{
        text-align: center;
    }
    .height100{
        height: 100%;
    }
    .scroller-y{
        overflow-y: auto;
    }
    .out-container{
        /*position: relative;*/
        /*overflow: hidden;*/
    }
    .inner-container{
        /*position: absolute;*/
        /*left: 0;*/
        /*top: 0;*/
        /*right: '-17px';*/
        /*bottom: 0;*/
        /*overflow-x: hidden;*/
        /*overflow-y: scroll;*/
    }
    .product-input-content {
        .line-height30{
            line-height: 30px;
            height: 30px;
        }
        .pdt3{
            padding-top: 3px;
        }
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
            padding: 10px 15px 0 15px;
            background-color: #f9f9f9;
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
        }
        .box-title {
            padding: 10px 15px;
            border-bottom: 1px solid #eeeeee;
        }
        .bg-white {
            background-color: #fff;
        }
        .title-box{
            padding: 10px 15px 7px;
            border-bottom: 1px solid #eeeeee;
        }
        .brand-box {
            padding: 10px 15px;
            margin-bottom: 10px;
            min-height: 50px;
        }
        .brand-box{
            .el-tag{
                margin: 3px;
            }
        }
        .brand-info-box {
            margin-bottom: 10px;
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
        .level-title-three{

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
        .product-list{
            .img{
                width: 40px;
                height: 40px;
                vertical-align: middle;
                object-fit: contain;
            }
        }
        .super-brand{
            .el-form-item--mini.el-form-item, .el-form-item--small.el-form-item{
                margin-bottom: 0;
            }
        }
        .brand-info-pd{
            padding: 15px 15px 15px 0;
        }
    }
    .set-tips{
        margin-top: -25px;
        padding: 10px 0 20px 0;
        line-height: 24px;
        color: #F39800;
    }
    .cur-pointer{
        cursor: pointer;
    }
    .product-input-content .avatar-uploader-icon{
        width: 100px;
        height: 100px;
        line-height: 100px;
    }
    .avatar{
        width: 100px;
        height: 100px;
        object-fit: scale-down;
    }
    .file{
        margin: 20px 50px 150px;
        .tip{
            padding-top: 20px;
        }
        .file-r{
            text-align: right;
        }
        .file-l{
            text-align: left;
        }
    }
    .file-desc{
        margin-bottom: 20px;
    }

</style>
