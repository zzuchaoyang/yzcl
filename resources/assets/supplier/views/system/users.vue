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

      <el-table-column
              label="ID"
              width="80"
              align="center">
        <template slot-scope="scope">
          {{scope.row.id}}
        </template>
      </el-table-column>

      <el-table-column align="center" label='姓名'>
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label='手机'>
        <template slot-scope="scope">
          <span>{{ scope.row.mobile }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="角色">
        <template slot-scope="scope">
          <span>{{scope.row.role && scope.row.role.alias}}</span>
        </template>
      </el-table-column>
      <el-table-column align="center" fixed="right" label="操作" width="80" class-name="small-padding fixed-width">
        <template slot-scope="scope">
          <el-button type="primary" size="mini" @click="showAddDialog(scope.row)">编辑</el-button>
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

      <!--新增、编辑用户弹窗-->
      <el-dialog title="用户" class="dialogUser" :visible.sync="userDialog.storeUserDialogVisible" width="685px">
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
                <i class="el-icon-upload" v-if="!userDialog.temp.avatar"></i>
                <div class="el-upload__text" v-if="!userDialog.temp.avatar">请上传图片</div>
                <a target="_blank" v-if="userDialog.temp.avatar" :href="userDialog.temp.image_url">
                  <img :src="userDialog.temp.avatar" class="avatar">
                </a>
              </div>
            </el-col>
            <el-col :span="14">
              <el-form ref="dataForm" label-position="left" label-width="80px" :model="userDialog.temp">
                <el-col :span="24">
                  <el-form-item label="名称" prop="name"
                                :rules="[{ required: true, message: '请输入用户名', trigger: 'blur' }]">
                    <el-input v-model="userDialog.temp.name" placeholder="请输入用户名"></el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item label="密码" prop="password"
                                :rules="[{ required: userDialog.passwordRequired, message: '请输入密码', trigger: 'blur' }]">
                      <el-input
                              :type="userDialog.passwordType"
                              v-model="userDialog.temp.password"
                              placeholder="请输入密码"
                              name="password"
                              auto-complete="on" />
                      <span class="show-pwd" @click="showPwd">
                        <svg-icon v-if="userDialog.passwordType == 'password'" icon-class="eye" />
                        <svg-icon v-else icon-class="eye_open" />
                      </span>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item label="手机号" prop="mobile"
                                :rules="[{ required: true, message: '请输入手机号', trigger: 'blur' }]">
                    <el-input v-model="userDialog.temp.mobile" placeholder="请输入手机号"></el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="24">
                  <el-form-item label="角色" prop="name" :rules="[{ required: true, message: '请选择角色', trigger:  'change' }]">
                    <el-select
                            v-model="userDialog.temp.default_role_id"
                            filterable
                            remote
                            reserve-keyword
                            placeholder="请选择角色"
                            :remote-method="RolesSelectRemote"
                            :loading="RolesSelect.loading">
                      <el-option
                              v-for="item in RolesSelect.options"
                              :key="item.id"
                              :label="item.alias"
                              :value="item.id">
                      </el-option>
                    </el-select>
                  </el-form-item>
                </el-col>
              </el-form>
            </el-col>
          </el-row>
          <div slot="footer" class="dialog-footer">
              <el-button @click="userDialog.storeUserDialogVisible = false">取消</el-button>
              <el-button type="primary" @click="storeUser">确定</el-button>
          </div>
      </el-dialog>
  </div>
</template>

<script>

import { getUserPaginator, getRoles, storeUser } from '../../api/system'
import { listHelper } from '@common/utils/listHelper'
import { message, notify } from '@common/utils/helper'

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
        importance: undefined,
        title: undefined,
        type: undefined,
        sort: null
      },
      userDialog: {
        passwordRequired: true,
        passwordType: 'password',
        temp: {},
        storeUserDialogVisible: false
      },
      // 其他变量
      downloadLoading: false,
      RolesSelect: {
        loading: false,
        options: []
      }
    }
  },
  created() {
    this.getList()
    this.RolesSelectRemote()
  },
  methods: {
    getList(action = 'init') {
      this.listLoading = true
      getUserPaginator(this.listQuery).then(response => {
        listHelper.setList(this.list, this.listQuery, response.data.userPaginator)
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
    // 获取角色
    RolesSelectRemote() {
      this.RolesSelect.loading = true
      getRoles().then(response => {
        this.RolesSelect.options = response.data.roles
      }).finally(() => {
        this.RolesSelect.loading = false
      })
    },
    // 显示新增用户窗口
    showAddDialog(row) {
      if (row) {
        this.userDialog.passwordRequired = false
        this.RolesSelect.options = row.role ? [row.role] : [] // 显示默认楼盘
        this.userDialog.temp = Object.assign({}, row)
      } else {
        this.userDialog.passwordRequired = true
        this.userDialog.temp = {
          id: null,
          name: null,
          password: null,
          mobile: null,
          avatar: null,
          default_role_id: null
        }
      }
      this.RolesSelectRemote()
      this.userDialog.storeUserDialogVisible = true
    },
    // 用户保存
    storeUser() {
      this.$refs['dataForm'].validate((valid) => {
        if (!valid) {
          return false
        }
        const tempData = Object.assign({}, this.userDialog.temp)
        if (!this.userDialog.temp.avatar) {
          message.error('必须上传图片')
          return false
        }
        storeUser(tempData).then(() => {
          this.getList()
          this.userDialog.storeUserDialogVisible = false
          notify.success()
        })
      })
    },
    // 上传图片
    fileUploadSuccess(file) {
      this.userDialog.temp.avatar = file.url
    },
    // 查看密码
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
    }
  },
  watch: {
    userDialog: {
      handler(curVal, oldVal) {
        if (!curVal.storeUserDialogVisible) {
          this.$refs['dataForm'].resetFields()
        }
      },
      deep: true
    }
  }
}
</script>
<style lang="scss" scoped>
  .el-select {
    width: 100%;
  }
  .dialogUser{
    /deep/ .el-form--label-left {
      .el-form-item__label{
        text-align: right;
      }
    }
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 0;
    font-size: 16px;
    color: #889aa4;
    cursor: pointer;
    user-select: none;
  }
  .dialogUser {
    .el-form-item /deep/ .el-form-item__label {
      /*line-height: 22px !important;*/
      padding-bottom: 0 !important;
    }
  }

  .upload-img {
    position: relative;
    width: 200px;
    height: 200px;
    margin-left: 30px;
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
    margin: 42px 0 16px;
    line-height: 50px;
  }

  .el-upload__text {
    font-size: 16px;
    color: #c0c4cc;
  }

</style>
