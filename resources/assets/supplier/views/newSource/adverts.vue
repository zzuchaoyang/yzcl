<template>
    <div class="app-container">

        <el-row type="flex" justify="end">
            <el-button style='margin:0 0 20px 20px;' size="small" @click="getList('refresh')" icon="el-icon-refresh">
                刷新
            </el-button>

            <el-button style='margin:0 0 20px 20px;' type="primary" icon="document" @click="showAddDialog()"
                       size="small"
                       :loading="downloadLoading">新增
            </el-button>
        </el-row>


        <el-table :data="list.items"
                  v-loading="listLoading"
                  element-loading-text="Loading"
                  border
                  fit
                  :height="this.$store.getters.tableHeight"
                  highlight-current-row>

            <el-table-column align="center" fixed label='标题' width="250">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='位置' width="160">
                <template slot-scope="scope">
                    <span>{{ scope.row.type }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='楼盘' width="250">
                <template slot-scope="scope">
                    <span>{{ scope.row.newCommunity && scope.row.newCommunity.name }}</span>
                </template>
            </el-table-column>

            <el-table-column prop="start_at" align="center" label='开始时间' width="200">
                <!--<template slot-scope="scope">-->
                <!--<span>{{ scope.row.start_at }}</span>-->
                <!--</template>-->
            </el-table-column>

            <el-table-column prop="end_at" align="center" label='结束时间' width="200">
                <!--<template slot-scope="scope">-->
                <!--<span>{{ scope.row.end_at }}</span>-->
                <!--</template>-->
            </el-table-column>

            <el-table-column align="center" label='价格' width="120">
                <template slot-scope="scope">
                    <span>{{ scope.row.price }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='操作人' width="100">
                <template slot-scope="scope">
                    <span>{{ scope.row.user_name }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='备注' width="250">
                <template slot-scope="scope">
                    <span>{{ scope.row.more }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" fixed="right" label='状态' width="80">
                <template slot-scope="scope">
                    <el-switch
                            @change="changeStatus(scope.row)"
                            v-model="scope.row.status">
                    </el-switch>
                </template>
            </el-table-column>

            <el-table-column align="center" fixed="right" label="操作" width="180" class-name="small-padding fixed-width">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" @click="showAddDialog(scope.row)">编辑</el-button>
                    <el-button type="danger" size="mini" @click="deleteAdvert(scope.row)">删除</el-button>
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


        <!--新增、编辑广告弹窗-->
        <el-dialog title="新房广告" class="dialogAdvert" :visible.sync="storeFxsAdvertDialogVisible" width="60%">
            <el-row :gutter="24">
                <el-col :span="10">
                    <div class="upload-img">
                        <el-row>
                            <el-upload
                                    class="upload-demo"
                                    action="/graphql-file"
                                    :data='{_token:token}'
                                    name='file'
                                    :on-success="fileUploadSuccess"
                                    multiple
                                    :limit="3">
                                <el-button type="primary" icon="el-icon-edit" circle></el-button>
                            </el-upload>
                        </el-row>
                        <i class="el-icon-upload" v-if="!temp.image_url"></i>
                        <div class="el-upload__text" v-if="!temp.image_url">请上传图片</div>
                        <a target="_blank" v-if="temp.image_url" :href="temp.image_url">
                            <img :src="temp.image_url" class="avatar">
                        </a>
                    </div>
                    <div class="upload-img_hint">
                        <h3 class="title">温馨提示：</h3>
                        <p class="hint-con">楼盘广告添加后，默认为关闭状态，如需修改，可手动开启.</p>
                    </div>
                </el-col>
                <el-col :span="13">
                    <el-form ref="dataForm" label-position="top" label-width="80px" :model="temp">
                        <el-row :gutter="24">
                            <el-col :span="12">
                                <el-form-item label="名称" prop="name"
                                              :rules="[{ required: true, message: '请输入新房广告名称', trigger: 'blur' }]">
                                    <el-input v-model="temp.name" placeholder="请输入广告名称"></el-input>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="楼盘" prop="name">
                                    <el-select
                                            v-model="temp.new_community_id"
                                            filterable
                                            remote
                                            reserve-keyword
                                            placeholder="请输入楼盘名称"
                                            :remote-method="newCommunitiesSelectRemote"
                                            :loading="newCommunitiesSelect.loading">
                                        <el-option
                                                v-for="item in newCommunitiesSelect.options"
                                                :key="item.id"
                                                :label="item.name"
                                                :value="item.id">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row :gutter="24">
                            <el-col :span="12">
                                <el-form-item label="类型" prop="type">
                                    <el-select class="filter-item" v-model="temp.type" placeholder="请选择类型">
                                        <el-option v-for="item in typeOptions" :key="item.value" :label="item.value"
                                                   :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="优先级" prop="sort">
                                    <el-select class="filter-item" v-model="temp.sort" placeholder="请选择优先级">
                                        <el-option v-for="item in typeGradeOptions" :key="item.value"
                                                   :label="item.value"
                                                   :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row :gutter="24">
                            <el-col :span="12">
                                <el-form-item label="尺寸" prop="size">
                                    <el-select class="filter-item" v-model="temp.size" placeholder="请选择尺寸">
                                        <el-option v-for="item in typeSizeOptions" :key="item.value" :label="item.value"
                                                   :value="item.value">
                                        </el-option>
                                    </el-select>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="价格" prop="price">
                                    <el-input v-model="temp.price" placeholder="请输入价格" type="number"></el-input>
                                </el-form-item>
                            </el-col>
                        </el-row>

                        <el-row :gutter="24">
                            <el-col :span="12">
                                <el-form-item label="开始时间" prop="start_at">
                                    <el-date-picker
                                            v-model="temp.start_at"
                                            type="datetime"
                                            format="yyyy-MM-dd HH:mm:ss"
                                            value-format="yyyy-MM-dd HH:mm:ss"
                                            placeholder="选择开始时间" style="width: 100%;">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                            <el-col :span="12">
                                <el-form-item label="结束时间" prop="end_at">
                                    <el-date-picker
                                            v-model="temp.end_at"
                                            type="datetime"
                                            format="yyyy-MM-dd HH:mm:ss"
                                            value-format="yyyy-MM-dd HH:mm:ss"
                                            placeholder="选择结束时间" style="width: 100%;">
                                    </el-date-picker>
                                </el-form-item>
                            </el-col>
                        </el-row>
                        <el-row :gutter="24">
                            <el-col :span="24">
                                <el-form-item label="备注" prop="more">
                                    <el-input type="textarea" v-model="temp.more"></el-input>
                                </el-form-item>
                            </el-col>
                        </el-row>

                    </el-form>
                </el-col>
            </el-row>

            <div slot="footer" class="dialog-footer">
                <el-button @click="storeFxsAdvertDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="storeFxsAdvert">确定</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
  import {
    deleteFxsAdvert,
    getAdvertPaginator, getNewCommunityPaginator, storeFxsAdvert
  } from '../../api/newSource'
  import { listHelper } from '@common/utils/listHelper'
  import { message, messageBox, notify } from '@common/utils/helper'

  // 广告类型
  const typeOptions = [
    { value: '首页顶部（电脑）' },
    { value: '首页轮播（移动）' },
    { value: '好房广告（电脑）' },
    { value: '好房广告（移动）' },
    { value: '炸屏广告（电脑）' },
    { value: '炸屏广告（移动）' },
    { value: '首页轮播（电脑）' },
    { value: '首页左侧（电脑）' },
    { value: '首页右侧（电脑）' },
    { value: '楼盘优惠（电脑）' },
    { value: '楼盘优惠（移动）' },
    { value: '楼盘优惠（专题）' },
    { value: '楼盘页头（电脑）' },
    { value: '楼盘页头（移动）' }
  ]
  // 广告排序
  const typeGradeOptions = [
    { value: '0' },
    { value: '1' },
    { value: '2' },
    { value: '3' },
    { value: '4' },
    { value: '5' },
    { value: '6' },
    { value: '7' },
    { value: '8' },
    { value: '9' }
  ]
  // 广告尺寸
  const typeSizeOptions = [
    { value: '原始尺寸' },
    { value: '400X300' },
    { value: '640X480' },
    { value: '1200X70' },
    { value: '1200X840' },
    { value: '1440X960' }
  ]

  export default {
    data() {
      return {
        token: null,
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
        servers: [],
        downloadLoading: false,
        // 其他变量
        temp: {},
        // 广告弹窗,
        storeFxsAdvertDialogVisible: false,
        typeOptions,
        typeGradeOptions,
        typeSizeOptions,
        // 楼盘 select
        newCommunitiesSelect: {
          loading: false,
          options: []
        }
      }
    },
    created() {
      this.getList()
    },
    methods: {
      // 获取列表
      getList(action = 'init') {
        this.listLoading = true
        getAdvertPaginator(this.listQuery).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.fxsAdvertPaginator)
          if (action === 'refresh') {
            notify.success()
          }
          this.listLoading = false
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
      // 显示广告窗口
      showAddDialog(row) {
        this.token = document.head.querySelector('meta[name="csrf-token"]').content
        if (row) {
          this.newCommunitiesSelect.options = row.newCommunity ? [row.newCommunity] : [] // 显示默认楼盘
          this.temp = Object.assign({}, row)
        } else {
          this.temp = {
            end_at: null,
            id: null,
            more: null,
            name: null,
            newCommunity: null,
            price: null,
            start_at: null,
            status: false,
            type: '首页顶部（电脑）',
            user_name: null,
            sort: 0,
            size: '原始尺寸',
            image_url: null,
            new_community_id: null
          }
        }
        this.newCommunitiesSelectRemote()
        this.storeFxsAdvertDialogVisible = true
      },
      // 获取楼盘列表
      newCommunitiesSelectRemote(key) {
        this.newCommunitiesSelect.loading = true
        getNewCommunityPaginator({ page: 1, limit: 30 }, { keywords: key }).then(response => {
          this.newCommunitiesSelect.options = response.data.newCommunityPaginator.items
        }).finally(() => {
          this.newCommunitiesSelect.loading = false
        })
      },
      // 新房广告保存
      storeFxsAdvert() {
        this.$refs['dataForm'].validate((valid) => {
          if (!valid) {
            return false
          }
          const tempData = Object.assign({}, this.temp)

          if (!this.temp.image_url) {
            message.error('必须上传图片')
            return false
          }

          storeFxsAdvert(tempData).then(() => {
            this.getList()
            this.storeFxsAdvertDialogVisible = false

            notify.success()
          })
        })
      },
      // 删除新房广告
      deleteAdvert(row) {
        messageBox.confirmDelete('删除后不可恢复，确定要删除吗？', () => {
          deleteFxsAdvert(row.id).then(() => notify.success(), () => notify.error).finally(() => this.getList())
        })
      },
      // 更改广告状态
      changeStatus(row) {
        storeFxsAdvert(row).then(() => {
          this.storeFxsAdvertDialogVisible = false
          // 保存成功重置表单
          notify.success()
        })
      },
      // 上传图片
      fileUploadSuccess(file) {
        this.temp.image_url = file.url
      }
    }
  }
</script>
<style lang="scss" scoped>
    .el-select {
        width: 100%;
    }

    .dialogAdvert {
        .el-form-item /deep/ .el-form-item__label {
            line-height: 22px !important;
            padding-bottom: 0 !important;
        }
    }

    .upload-img {
        position: relative;
        height: 300px;
        padding: 6px;
        background-color: #fff;
        border: 1px solid #ddd;
        overflow: hidden;
        margin-bottom: 5px;
        vertical-align: middle;
        text-align: center;
        border-radius: 4px;
        img {
            width: 100%;
            height: 100%;
        }
        .el-row {
            padding: 8px 8px 0 0;
            display: none;
            text-align: right;
        }
        &:hover {
            .el-row {
                display: block;
            }
        }
        .el-row {
            position: absolute;
            right: 0;
            top: 0;
            .upload-demo {
                display: inline-block;
                *display: inline;
                *zoom: 1;
            }
        }
        & /deep/ .el-upload-list {
            display: none !important;
        }
    }

    .upload-img_hint {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        .title {
            margin: 0;
            font-size: 16px;
            color: #409eff;
        }
        .hint-con {
            font-size: 14px;
            color: #409eff;
        }
    }

    .el-icon-upload {
        font-size: 80px;
        color: #c0c4cc;
        margin: 90px 0 16px;
        line-height: 50px;
    }

    .el-upload__text {
        font-size: 16px;
        color: #c0c4cc;
    }
</style>
