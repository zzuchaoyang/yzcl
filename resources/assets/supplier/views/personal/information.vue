<template>
    <div class="app-container" :style="{height:this.$store.getters.tableHeight + 218 + `px`}">
        <el-alert v-if="alertShow"
                  title="请完善公司信息"
                  close-text="知道了"
                  type="warning">
        </el-alert>
        <el-form :model="storeSupplier" ref="ruleForm" label-width="120px" class="demo-ruleForm">
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>公司信息</span>
                </div>
                <el-row>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="公司名称" prop="info.company.gsmc"
                                      :rules="[{ required: true, message: '请输入公司名称', trigger: 'blur' }]">
                            <el-input auto-complete="on" v-model="storeSupplier.info.company.gsmc"
                                      placeholder="请输入公司名称"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="工商性质" prop="info.company.gsxz"
                                      :rules="[{ required: true, message: '请选择工商性质', trigger: 'blur' }]">
                            <el-select class="filter-item" v-model="storeSupplier.info.company.gsxz"
                                       placeholder="请选择工商性质" style="width: 100%">
                                <el-option
                                        v-for="item in typeOptions"
                                        :key="item.value"
                                        :label="item.value"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="法人代表" prop="info.company.frdb"
                                      :rules="[{ required: true, message: '请输入法人代表姓名', trigger: 'blur' }]">
                            <el-input placeholder="请输入法人代表姓名" v-model="storeSupplier.info.company.frdb"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="法人联系电话" prop="info.company.frlxdh"
                                      :rules="[{ required: true, message: '请输入法人联系电话', trigger: 'blur' },tel]">
                            <el-input placeholder="请输入法人联系电话" v-model="storeSupplier.info.company.frlxdh"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="公司传真" prop="info.company.gscz">
                            <el-row>
                                <el-col :span="9" style="margin-right: -1px">
                                    <el-input placeholder="区号" v-model="faxNumber.areaCode"/>
                                </el-col>
                                <el-col :span="15">
                                    <el-input placeholder="请输入传真号码" v-model="faxNumber.faxCode"/>
                                </el-col>
                            </el-row>

                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="公司邮箱" prop="info.company.gsyx"
                                      :rules="[{ required: false, trigger: 'blur' },email]">
                            <el-input placeholder="请输入公司邮箱" v-model="storeSupplier.info.company.gsyx"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="业务联系人" prop="info.company.ywlxr"
                                      :rules="[{ required: true, message: '请输入业务员联系姓名', trigger: 'blur' }]">
                            <el-input placeholder="请输入业务员联系姓名" v-model="storeSupplier.info.company.ywlxr"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="业务联系电话" prop="info.company.ywlxdh"
                                      :rules="[{ required: true, message: '请输入业务员联系电话', trigger: 'blur' },tel]">
                            <el-input placeholder="请输入业务员联系电话" v-model="storeSupplier.info.company.ywlxdh"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="24" :sm="24" :lg="16">
                        <el-form-item label="公司地址" prop="info.company.gsdz"
                                      :rules="[{ required: true, message: '请输入公司地址', trigger: 'blur' }]">
                            <el-input placeholder="请输入公司地址" v-model="storeSupplier.info.company.gsdz" :readonly="!areaId">
                                <el-select v-model="provinceId" slot="prepend" :disabled="selectLoading" @click.native="clickSelectedArea('one',0)" @change="selectedArea('two',provinceId)" placeholder="请选择省份"
                                           :loading="selectLoading"
                                           style="width: 120px;border-right: 1px solid #ccc;">
                                    <el-option
                                            v-for="item in areaList.province"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                                <el-select v-model="cityId" slot="prepend" :disabled="selectLoading" @click.native="clickSelectedArea('two',provinceId)" @change="selectedArea('three',cityId)" placeholder="请选择市"
                                           :loading="selectLoading"
                                           style="width: 120px;margin-left: 20px;border-right: 1px solid #ccc;">
                                    <el-option
                                            v-for="item in areaList.city"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                                <el-select v-model="areaId" slot="prepend" :disabled="selectLoading" @click.native="clickSelectedArea('three',cityId)" @change="selectedArea('four',areaId)" placeholder="请选择区"
                                           :loading="selectLoading"
                                           style="width: 120px;margin-left: 20px">
                                    <el-option
                                            v-for="item in areaList.area"
                                            :key="item.id"
                                            :label="item.name"
                                            :value="item.id">
                                    </el-option>
                                </el-select>
                            </el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="24" :sm="24" :lg="24">
                        <el-form-item label="形象展示" prop="info.company.xxzs">
                            <el-upload
                                    class="avatar-uploader"
                                    action="/graphql-file"
                                    :show-file-list="false"
                                    :on-success="handleAvatarSuccess"
                                    :before-upload="beforeAvatarUpload">
                                <img v-if="storeSupplier.info.company.xxzs" :src="storeSupplier.info.company.xxzs"
                                     class="avatar">
                                <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                                <div slot="tip" class="el-upload__tip remark" style="color: #FF5C15">
                                    格式：jpg/png，大小：小于2M，尺寸：200*200px；该图将展示给零售商查看
                                </div>
                            </el-upload>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="24" :lg="24">
                        <el-form-item label="公司介绍" prop="info.company.gsjs"
                                      :rules="[{ required: true, message: '请输入公司介绍详细内容', trigger: 'blur' }]">
                            <el-input type="textarea"
                                      :rows="4"
                                      :maxlength="200"
                                      placeholder="请输入公司的简单介绍，用以展示给零售商查看；请您介绍公司经营的品类、品牌、经营年限、公司优势等。文字限制为200字以内；"
                                      v-model="storeSupplier.info.company.gsjs"></el-input>
                        </el-form-item>

                    </el-col>
                </el-row>
            </el-card>
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>财务信息</span>
                </div>
                <el-row>
                    <el-col :xs="24" :sm="24" :lg="24">
                        <p class="remark-txt">说明：个体工商户仅须填写开票名称，其他信息可留空。</p>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="开票名称" prop="info.finance.kpmc"
                                      :rules="[{ required: true, message: '请输入开票名称', trigger: 'blur' }]">
                            <el-input placeholder="请输入开票名称" v-model="storeSupplier.info.finance.kpmc"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="社会信用代码" prop="info.finance.shxydm"
                                      :rules="[{ required: storeSupplier.info.company.gsxz != '个体工商户'? true : false, message: '请输入统一社会信用代码/纳税人识别号', trigger: 'blur' },code]">
                            <el-input placeholder="请输入统一社会信用代码/纳税人识别号" v-model="storeSupplier.info.finance.shxydm"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="注册地址" prop="info.finance.zcdz"
                                      :rules="[{ required: storeSupplier.info.company.gsxz != '个体工商户'? true : false, message: '请输入营业执照注册地址', trigger: 'blur' }]">
                            <el-input placeholder="请输入营业执照注册地址" v-model="storeSupplier.info.finance.zcdz"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="联系电话" prop="info.finance.lxdh"
                                      :rules="[{ required: storeSupplier.info.company.gsxz != '个体工商户'? true : false, message: '请输入公司注册联系电话', trigger: 'blur' }]">
                            <el-input placeholder="请输入公司注册联系电话" v-model="storeSupplier.info.finance.lxdh"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="开户银行" prop="info.finance.khyh"
                                      :rules="[{ required: storeSupplier.info.company.gsxz != '个体工商户'? true : false, message: '请输入开户银行全称', trigger: 'blur' }]">
                            <el-input placeholder="请输入开户银行全称" v-model="storeSupplier.info.finance.khyh"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="8" :lg="6">
                        <el-form-item label="银行账号" prop="info.finance.yhzh"
                                      :rules="[{ required: storeSupplier.info.company.gsxz != '个体工商户'? true : false, message: '请输入开户银行账号', trigger: 'blur' }]">
                            <el-input placeholder="请输入开户银行账号" v-model="storeSupplier.info.finance.yhzh"/>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-card>
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span>证件信息</span>
                </div>
                <el-row>
                    <el-col :xs="24" :sm="24" :lg="24">
                        <p class="remark-txt">说明：营业执照为必须上传证件，其他证件请自行上传并注明证件名称。</p>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :xs="24" :sm="24" :lg="24">
                        <el-form-item prop="info.card" label-width="0">
                            <ul class="cardImgs img-list">
                                <viewer @inited="initImg" :images="this.storeSupplier.info.cards"
                                        class="viewer" ref="viewer"
                                >
                                    <li class="img-box el-upload-list--picture-card">
                                        <el-upload class="upLoad-box" v-if="!businessLicense.link"
                                                   list-type="picture-card"
                                                   action="/graphql-file"
                                                   :show-file-list="false"
                                                   :on-success="handleLicenseSuccess">
                                            <i class="el-icon-plus"></i>
                                        </el-upload>
                                        <div class="img-con" style="position: relative" v-if="businessLicense.link">
                                            <img :src="businessLicense.link" alt="执照"/>
                                            <span class="el-upload-list__item-actions">
                                            <span class="el-upload-list__item-preview" @click="showCardImg(0)">
                                                <i class="el-icon-zoom-in"></i>
                                            </span>
                                            <span class="el-upload-list__item-delete" @click="delLicenseImg()">
                                                <i class="el-icon-delete"></i>
                                            </span>
                                        </span>
                                        </div>
                                        <p class="img-name">营业执照</p>
                                    </li>
                                    <li class="img-box el-upload-list--picture-card" v-if="item.name != '营业执照'"
                                        v-for="(item, index) in storeSupplier.info.cards" :key="index">
                                        <div class="img-con" style="position: relative">
                                            <img :src="item.link" alt="执照"/>
                                            <span class="el-upload-list__item-actions">
                                            <span class="el-upload-list__item-preview" @click="showCardImg(index)">
                                                <i class="el-icon-zoom-in"></i>
                                            </span>
                                            <span class="el-upload-list__item-delete" @click="delCardImg(index)">
                                                <i class="el-icon-delete"></i>
                                            </span>
                                        </span>
                                        </div>
                                        <p class="img-name" :title="item.name">{{item.name}}</p>
                                    </li>
                                </viewer>
                            </ul>
                            <el-upload class="upLoad-box"
                                       v-if="storeSupplier.info.cards.length < 5"
                                       list-type="picture-card"
                                       action="/graphql-file"
                                       :show-file-list="false"
                                       :on-success="handleCardSuccess">
                                <i class="el-icon-plus"></i>
                            </el-upload>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-card>
            <el-form-item label-width="0" style="text-align: center">
                <el-button style="width: 120px" type="primary" @click="addStoreSupplier('ruleForm')">保存</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>
<script>
  import { StoreSupplier, AreaList, AreaList2 } from '../../api/finance'
  import { notify } from '@common/utils/helper'

  const typeOptions = [
    {
      label: '个体工商户',
      value: '个体工商户'
    },
    {
      label: '小规模纳税人',
      value: '小规模纳税人'
    },
    {
      label: '一般纳税人',
      value: '一般纳税人'
    }
  ]
  export default {
    name: 'informationCompany',
    data() {
      var validateCode = (rule, value, callback) => {
        if (value) {
          if ((value.length !== 18) || !(/^[0-9A-Z]+$/.test(value))) {
            callback(new Error('不是有效的统一社会信用编码'))
          } else {
            callback()
          }
        } else {
          callback()
        }
      }
      var validateTel = (rule, value, callback) => {
        if (value && !(/^1[34578]\d{9}$/.test(value))) {
          callback(new Error('请输入正确的联系电话'))
        } else {
          callback()
        }
      }
      var validateEmail = (rule, value, callback) => {
        if (value && !(/^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/.test(value))) {
          callback(new Error('请输入正确邮箱'))
        } else {
          callback()
        }
      }
      return {
        validateTel,
        validateEmail,
        validateCode,
        typeOptions,
        tel: { validator: validateTel, trigger: ['blur'] },
        email: { validator: validateEmail, trigger: ['blur'] },
        code: { validator: validateCode, trigger: ['blur'] },
        storeSupplier: {
          id: null,
          info: {
            company: {
              xxzs: null
            },
            finance: {},
            cards: []
          }
        },
        imageAvatar: null,
        alertShow: false,
        faxNumber: {
          areaCode: null,
          faxCode: null
        },
        businessLicense: {
          link: '',
          name: '营业执照'
        },
        areaList: {
          province: [],
          city: [],
          area: []
        },
        provinceId: null,
        cityId: null,
        areaId: null,
        selectLoading: true
      }
    },
    created() {
      if (!this.$store.state.user.company) {
        this.alertShow = true
        this.getAreaList('one', 0)
      } else {
        this.storeSupplier.id = this.$store.state.user.user.id
        this.storeSupplier.info = this._.cloneDeep(this.$store.state.user.company)
        if (this.storeSupplier.info.cards.length > 0 && this.storeSupplier.info.cards[0].name === '营业执照') {
          this.businessLicense.link = this._.clone(this.storeSupplier.info.cards[0].link)
        }
        if (this.storeSupplier.info.company.gscz) {
          const code = this._.clone(this.storeSupplier.info.company.gscz.split('-'))
          this.faxNumber.areaCode = code[0]
          this.faxNumber.faxCode = code[1]
        }
        this.getAreaList('five', this.storeSupplier.info.company.parent_ids)
      }

    },
    methods: {
      selectedArea(type, id) {
        this.selectLoading = true
        if (type === 'four') {
          this.selectLoading = false
          this.storeSupplier.info.company.area_id = id
        } else {
          this.getAreaList(type, id)
        }
      },
      clickSelectedArea(type, id) {
        if (this.selectLoading) {
          this.selectLoading = false
          return
        }
        if (type === 'four') {
          this.selectLoading = false
          this.storeSupplier.info.company.area_id = id
        } else {
          this.selectLoading = true
          if (type === 'two' && !this.provinceId) {
            notify.warning('请选择省份')
            this.areaList.city = null
            this.selectLoading = false
            return
          } else if (type === 'three' && !this.provinceId) {
            notify.warning('请选择市')
            this.areaList.area = []
            this.selectLoading = false
            return
          }
          this.getAreaList2(type, id)
        }
      },
      getAreaList(type, id) {
        AreaList(id).then((response) => {
          if (type === 'one') {
            this.areaList.province = this._.cloneDeep(response.data.areaList)
          } else if (type === 'two') {
            this.areaList.city = this._.cloneDeep(response.data.areaList)
          } else if (type === 'three') {
            this.areaList.area = this._.cloneDeep(response.data.areaList)
          } else if (type === 'five') {
            this.areaList.province.push(response.data.areaList[0])
            this.areaList.city.push(response.data.areaList[1])
            this.areaList.area.push(response.data.areaList[2])
            this.provinceId = this.storeSupplier.info.company.parent_ids[0]
            this.cityId = this.storeSupplier.info.company.parent_ids[1]
            this.areaId = this.storeSupplier.info.company.area_id
          }
          this.selectLoading = false
        })
      },
      getAreaList2(type, id) {
        AreaList2(id).then((response) => {
          if (type === 'one') {
            this.areaList.province = this._.cloneDeep(response.data.areaList)
          } else if (type === 'two') {
            this.areaList.city = this._.cloneDeep(response.data.areaList)
          } else if (type === 'three') {
            this.areaList.area = this._.cloneDeep(response.data.areaList)
          } else if (type === 'five') {
            this.areaList.province.push(response.data.areaList[0])
            this.areaList.city.push(response.data.areaList[1])
            this.areaList.area.push(response.data.areaList[2])
          }
          this.selectLoading = false
        })
      },
      addStoreSupplier() {
        this.$refs['ruleForm'].validate((valid) => {
          if (!valid) {
            return false
          }
          if (this.faxNumber.areaCode && this.faxNumber.faxCode) {
            this.storeSupplier.info.company.gscz = this._.clone(this.faxNumber.areaCode + `-` + this.faxNumber.faxCode)
          }
          const tempData = this._.cloneDeep(this.storeSupplier)
          if (tempData.info.cards.length === 0 || tempData.info.cards[0].name !== '营业执照') {
            notify.warning('请上传营业执照')
            return
          }
          if (tempData.info.company.parent_ids) {
            delete tempData.info.company.parent_ids
          }
          StoreSupplier(tempData).then((response) => {
            this.$store.commit('SET_COMPANY', response.data.storeSupplier.info)
            this.alertShow = false
            notify.success()
          })
        })
      },
      // v-viwer图片展示
      initImg(viewer) {
        this.$viewer = viewer
      },
      showCardImg(index) {
        this.$viewer.view(index)
      },
      // 形象上传
      handleAvatarSuccess(res) {
        this.storeSupplier.info.company.xxzs = res.url
      },
      beforeAvatarUpload(file) {
        const isJPG = file.type === 'image/jpeg' || 'image/png'
        const isLt2M = file.size / 1024 / 1024 < 2
        if (!isJPG) {
          this.$message.error('上传头像图片只能是 JPG、PNG 格式!')
        }
        if (!isLt2M) {
          this.$message.error('上传头像图片大小不能超过 2MB!')
        }
        return isJPG && isLt2M
      },
      // 营业执照
      handleLicenseSuccess(res, file) {
        this.businessLicense.link = res.url
        this.businessLicense.created_at = res.created_at
        this.storeSupplier.info.cards.unshift(this.businessLicense)
      },
      // 上传图片成功
      handleCardSuccess(res, file) {
        const cardImg = {
          name: file.name,
          link: res.url,
          created_at: res.created_at
        }
        this.storeSupplier.info.cards.push(cardImg)
      },
      delCardImg(index) {
        this.storeSupplier.info.cards.splice(index, 1)
      },
      // 删除营业执照
      delLicenseImg() {
        this.businessLicense.link = ''
        this.storeSupplier.info.cards.splice(0, 1)
      }
    }
  }
</script>
<style lang="scss" scoped>
    .app-container {
        overflow-y: auto;
        background: #fff;
        .el-card {
            margin-bottom: 12px;
            .avatar-uploader {
                /deep/ .el-upload {
                    width: 75px;
                    height: 75px;
                    img {
                        width: 100%;
                        height: 100%;
                        border: 1px solid #dcd7d7;
                    }
                }
            }
            .remark-txt {
                margin-top: 0;
                margin-bottom: 10px;
                font-size: 12px;
                color: #FF5C15;
            }
        }
        .avatar-uploader .el-upload {
            border: 1px dashed #d9d9d9;
            border-radius: 6px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        .avatar-uploader .el-upload:hover {
            border-color: #409EFF;
        }
        .avatar-uploader-icon {
            font-size: 28px;
            color: #8c939d;
            width: 75px;
            height: 75px;
            line-height: 75px;
            text-align: center;
            border: 1px dashed #999;
        }
        .avatar {
            width: 178px;
            height: 178px;
            display: block;
        }
        .upLoad-box {
            display: inline-block;
        }
        .img-list {
            padding-left: 0;
            display: inline-block;
            float: left;
            margin: 0 0 30px;
            height: 148px;
            .img-box {
                margin-right: 12px;
                list-style-type: none;
                display: inline-block;
                *display: inline;
                *zoom: 1;
                .img-con {
                    width: 148px;
                    height: 148px;
                    overflow: hidden;
                    border-radius: 3px;
                    box-shadow: 0px 0px 6px #ccc;
                    img {
                        width: 100%;
                        height: 100%;
                    }
                }
                .img-name {
                    width: 148px;
                    margin: 0;
                    text-align: center;
                    overflow: hidden;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                }

            }
        }
    }
</style>
