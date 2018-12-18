<template>
    <div class="app-container product-detail" style="overflow-y: auto" :style="{height: this.$store.getters.tableHeight + 220 + `px`}">
        <div class="groupBox">
            <el-row style="height: 100%;">
                <el-col :span="12">
                    <div class="name">
                        {{detail.name}}
                    </div>
                </el-col>
                <el-col :span="12" class="link">
                    <router-link class="link-type" :to="{ name: 'productEdit', params:{id: detail.id}}">
                       <el-button type="primary">修改</el-button>
                    </router-link>
                </el-col>

            </el-row>

            <el-row class="padding-0-20">
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">商品条码</div>
                    <div class="value">{{detail.bar_code}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">所属品牌</div>
                    <div class="value">{{detail.brand && detail.brand.name}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6" class="text-overflow">
                    <div class="label">产地</div>
                    <div class="value" :title="detail.make_place">{{detail.make_place || '暂无'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6" class="text-overflow">
                    <div class="label">生产厂商</div>
                    <div class="value" :title="detail.manufacturer">{{detail.manufacturer || '暂无'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">商品单位</div>
                    <div class="value">{{detail.unit}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">商品规格</div>
                    <div class="value">{{detail.spec_number+detail.spec_unit}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">建议零售价</div>
                    <div class="value">{{detail.retail_price + '元'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">保质期</div>
                    <div class="value">{{detail.quality_period }}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">当前状态</div>
                    <div class="value">{{detail.status }}</div>
                </el-col>
            </el-row>

        </div>
        <div class="groupBox">
            <div class="title">
                订货信息
            </div>
            <el-row class="padding-0-20">
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">订货单位</div>
                    <div class="value">{{detail.order_unit}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">包含单品数量</div>
                    <div class="value">{{detail.single_num}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">订货规格</div>
                    <div class="value">{{detail.spec_number+detail.spec_unit + '*' + detail.single_num}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">批发含税进价</div>
                    <div class="value">{{detail.trade_price + '元'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">一级供货价</div>
                    <div class="value">{{detail.one_price + '元'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">二级供货价</div>
                    <div class="value">{{detail.two_price && detail.two_price + '元' || '暂无'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">三级供货价</div>
                    <div class="value">{{detail.three_price && detail.three_price + '元' || '暂无'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">四级供货价</div>
                    <div class="value">{{detail.four_price && detail.four_price + '元' || '暂无'}}</div>
                </el-col>
                <el-col :xs="24" :sm="12" :md="6" :lg="6" :xl="6">
                    <div class="label">五级供货价</div>
                    <div class="value">{{detail.five_price && detail.five_price + '元' || '暂无'}}</div>
                </el-col>
            </el-row>

        </div>
        <div class="groupBox">
            <div class="title">
                商品图片
            </div>
            <div class="picturesImg" v-viewer="{movable: false}" v-if="detail.pictures && detail.pictures.length">
                <div v-for="(item,index ) in detail.pictures" :key="index" class="flex-item">
                    <el-card class="img-box" :body-style="{ padding: '0px' }">
                        <img :src="item" alt="">
                        <div class="mask" @click="showViewer(index, 'picturesImg')">
                            <i class="el-icon-zoom-in"></i>
                        </div>
                    </el-card>
                </div>
            </div>
            <div v-else>
                <p class="noData">暂无商品图片</p>
            </div>
        </div>
        <div class="groupBox">
            <div class="title">
                商品检验报告
            </div>
            <div class="checkPicturesImg" v-viewer="{movable: false}" v-if="detail.check_pictures && detail.check_pictures.length">
                <div v-for="(item,index ) in detail.check_pictures" :key="index" class="flex-item">
                    <el-card class="img-box" :body-style="{ padding: '0px' }">
                        <img :src="item" alt="">
                        <div class="mask" @click="showViewer(index, 'checkPicturesImg')">
                            <i class="el-icon-zoom-in"></i>
                        </div>
                    </el-card>
                </div>
            </div>
            <div v-else>
                <p class="noData">暂无商品检验报告</p>
            </div>
        </div>
    </div>
</template>

<script>
  import { productPaginatorDetail } from '../../api/product'
  export default {
    data() {
      return {
        detail: {
          id: 100
        },
        listQuery: {
          page: 1,
          limit: 1
        },
        more: {
          id: null
        }
      }
    },
    created() {
      if (this.$route.params && this.$route.params.id) {
        this.more.id = this.$route.params.id
        this.getDetail()
      }
    },
    methods: {
      getDetail() {
        productPaginatorDetail(this.listQuery, this.more).then(response => {
          this.detail = response.data.productPaginator.items[0]
        }).finally(() => {
        })
      },
      // 放大图片
      showViewer(index, name) {
        const viewer = this.$el.querySelector('.' + name).$viewer
        viewer.view(index)
      }
    }
  }
</script>
<style lang="scss" scoped>
    .product-detail {
       .groupBox{
           background-color: #ffffff;
           /*padding: 20px;*/
           /*padding: 0 20px;*/
           margin-bottom: 10px;
           &:last-child{
               margin-bottom: 0;
           }
           .name{
               font-size: 14px;
               color: #333333;
               padding: 20px;
               font-weight: bolder;
           }
           .title{
               font-size: 13px;
               color: #333333;
               border-bottom: 1px solid #cccccc;
               height: 40px;
               line-height: 40px;
               margin-bottom: 20px;
               padding-left: 20px;
           }
           .label, .value{
               display: inline-block;
               font-size: 13px;
               margin-bottom: 20px;
           }
           .label{
               width: 80px;
               color: #999999;
           }
           .value{
               color: #333333;
           }
           .flex-item{
               flex: 0 0 140px;
               margin-bottom: 20px;
           }
           .img-box{
               width: 120px;
               height: 120px;
               margin-right: 10px;
               position: relative;
               /deep/ .el-card__body{
                   height: 100%;
               }
               img{
                   width: 100%;
                   height: 100%;
                   object-fit: cover;
               }
               .mask {
                   position: absolute;
                   top: 0;
                   left: 0;
                   width: 120px;
                   height: 120px;
                   background: rgba(0, 0, 0, 0.6);
                   opacity: 0;
                   -webkit-transition:all 0.45s ease-in-out;
                   -moz-transition:all 0.45s ease-in-out;
                   -o-transition:all 0.45s ease-in-out;
                   -ms-transition:all 0.45s ease-in-out;
                   transition:all 0.45s ease-in-out;
                   i{
                       font-size: 24px;
                       color: #ffffff;
                       position: absolute;
                       top: 50%;
                       left: 50%;
                       transform: translate(-50%, -50%);
                   }
               }
               &:hover{
                   cursor:pointer;
                   .mask {
                       opacity: 1;
                   }
               }
           }
       }
        .padding-0-20{
            padding: 0 20px;
        }
        .picturesImg, .checkPicturesImg{
            padding: 0 20px;
            display: flex;
            overflow: hidden;
            overflow-x: auto;
        }
        .noData{
            padding: 0 0 20px 20px;
            font-size: 13px;
            color: #666666;
        }
        .link{
            text-align: right;
            padding: 15px 20px 0 0;
        }

    }
</style>
