<template>
    <div class="app-container">
        <el-card>
        <el-row type="flex" justify="space-between">
            <el-col :span="17">
                <el-form :inline="true">
                    <el-form-item>
                        <el-input size="small" v-model="listMore.name" placeholder="部门名称↵"
                                  @keyup.enter="getList('refresh')"
                                  @change="getList('refresh')" clearable></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-select size="small" v-model="listMore.status" @change="getList('refresh')" clearable
                                   placeholder="状态">
                            <el-option
                                    v-for="item in statusOptions"
                                    :key="item.value"
                                    :label="item.name"
                                    :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </el-col>
            <el-col :span="5" class="fn-tr">
                <el-button style='margin:0 0 20px 20px; display: inline-block' size="small" @click="getList('refresh')"
                           icon="el-icon-refresh" class="el-button--success">
                    刷新
                </el-button>

                <el-button style='margin:0 0 20px 20px;display: inline-block' type="primary" @click="showAddDialog('','edit')"
                           icon="el-icon-edit" size="small"
                           :loading="downloadLoading">新增
                </el-button>
            </el-col>
        </el-row>
        <tree-table :data="orgList" :columns="columns" border>
            <el-table-column align="center" label="部门名称" width="300">
                <template slot-scope="scope">
                    <el-button type="text">{{scope.row.name}}</el-button>
                </template>
            </el-table-column>
            <el-table-column align="center" label="备注">
                <template slot-scope="scope">
                    <el-button type="text">{{scope.row.remarks}}</el-button>
                </template>
            </el-table-column>
            <el-table-column align="center" label="状态" width="120">
                <template slot-scope="scope">
                    <el-switch v-if="scope.row.parent_id"
                            @change="changeStatus(scope.row)"
                            v-model="scope.row.status">
                    </el-switch>
                </template>
            </el-table-column>
            <el-table-column fixed="right" label="操作" align="center" width="160">
                <template slot-scope="scope">
                    <el-button type="primary" plain size="mini" v-if="scope.row.parent_id" @click="showAddDialog(scope.row,'edit')" style="width: auto">编辑</el-button>
                    <el-button type="danger" plain size="mini" v-if="scope.row.parent_id" @click="deleteOrg(scope.row)" style="width: auto">删除</el-button>
                </template>
            </el-table-column>
        </tree-table>

        <el-dialog v-el-drag-dialog title="部门详情" class="dialogProduct" :visible.sync="editDialog.visible" width="700px">
            <el-tabs v-model="tabActiveName" @tab-click="handleClick">
                <el-tab-pane label="基本信息" name="first">
                    <el-row :gutter="24">
                        <el-col :span="24">
                            <el-form ref="dataForm" label-position="right" label-width="80px" :model="editDialog.temp">
                                <el-row :gutter="24">
                                    <el-col :span="12">
                                        <el-form-item label="部门名称" prop="name"
                                                      :rules="[{ required: true, message: '请输入部门名称', trigger: 'blur' }]">
                                            <el-input v-model="editDialog.temp.name" placeholder="请输入部门名称"></el-input>
                                        </el-form-item>
                                    </el-col>
                                    <!--<el-col :span="12" v-if="editDialog.temp.isEdit === 'edit' || editDialog.temp.parent_id">-->
                                    <el-col :span="12">
                                        <el-form-item label="所属部门" prop="parent_id"
                                                      :rules="[{ required: true, message: '请选择所属部门', trigger: ['blur','change'] }]">
                                            <TreeSelect
                                                    :show-count="true"
                                                    :options="editDialog.orgListTree"
                                                    placeholder="请选择所属部门"
                                                    noChildrenText = "暂无数据"
                                                    v-model="editDialog.temp.parent_id"
                                                    :append-to-body="appendBody"
                                                    :normalizer="normalizer"
                                            />
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                                <el-row :gutter="24">
                                    <el-col :span="24">
                                        <el-form-item label="备注" prop="more">
                                            <el-input type="textarea" v-model="editDialog.temp.remarks"></el-input>
                                        </el-form-item>
                                    </el-col>
                                </el-row>
                            </el-form>
                        </el-col>
                    </el-row>
                </el-tab-pane>
                <el-tab-pane label="日志" name="second">
                    <journalSteps v-if="auditsList.length > 0" :data="auditsList"></journalSteps>
                    <div v-else class="no-audits">暂无相关信息</div>
                </el-tab-pane>
            </el-tabs>
            <div slot="footer" class="dialog-footer">
                <el-button @click="editDialog.visible = false">取消</el-button>
                <el-button type="primary" @click="storeOrg">确定</el-button>
            </div>
        </el-dialog>
        </el-card>
    </div>
</template>

<script>
  import elDragDialog from '@supplier/directive/el-dragDialog'
  import treeTable from '@common/components/TreeTable'
  import journalSteps from '@common/components/Journal'
  import { getJrOrganizationList, storeJrOrganization, deleteJrOrganization, getAudits } from '../../api/finance'
  // import { listHelper } from '@common/utils/listHelper'
  import { messageBox, notify } from '@common/utils/helper'

  import TreeSelect from '@riophae/vue-treeselect'
  // import the styles
  import '@riophae/vue-treeselect/dist/vue-treeselect.css'

  // 状态
  const statusOptions = [
    { name: '启用', value: true },
    { name: '禁用', value: false }
  ]
  export default {
    directives: { elDragDialog },
    components: { treeTable, TreeSelect, journalSteps },
    data() {
      return {
        auditsList: [],
        appendBody: true,
        normalizer(node) {
          return {
            id: node.id,
            label: node.name,
            children: node.children
          }
        },
        columns: [
          {
            text: '展开/折叠',
            value: 'event',
            width: 200,
            headerAlign: 'center'
          }
        ],
        tabActiveName: 'first',
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
        listMore: {
          name: null,
          status: true
        },
        downloadLoading: false,
        statusOptions,
        // 弹窗
        editDialog: {
          visible: false,
          temp: {},
          area: null,
          city: {},
          selectedCity: [],
          orgListTree: []
        },
        CitysSelect: {
          loading: false,
          options: [],
          city: []
        },
        orgList: []
      }
    },
    created() {
      this.getList()
    },
    methods: {
      handleClick(tab, event) {
        if (tab.label === '日志') {
          this.getAuditsList('jrOrg', this.editDialog.temp.id)
        }
      },
      // 获取列表
      getList(action = 'init') {
        this.listLoading = true
        getJrOrganizationList(this.listMore).then(response => {
          this.editDialog.orgListTree = this.create_tree(response.data.jrOrganizationList)
          this.orgList = Object.assign([], this.editDialog.orgListTree)
          // console.log(this.list)
          if (action === 'refresh') {
            notify.success()
          }
          this.listLoading = false
        })
      },
      create_tree(data) {
        var map = {}
        data.forEach(function(item) {
          map[item.id] = item
        })
        const val = []
        data.forEach(function(item) {
          var parent = map[item.parent_id]
          if (parent) {
            (parent.children || (parent.children = [])).push(item)
          } else {
            val.push(item)
          }
        })
        return val
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
      // 显示产品窗口
      showAddDialog(row, type) {
        this.audits = []
        this.tabActiveName = 'first'
        if (row) {
          // this.newCommunitiesSelect.options = row.newCommunity ? [row.newCommunity] : []
          this.editDialog.temp = Object.assign({}, row)
          this.editDialog.temp.isEdit = type

        } else {
          this.editDialog.temp = {
            name: null,
            parent_id: null,
            remarks: null,
            isEdit: type
          }
        }
        this.editDialog.visible = true
      },
      // 保存
      storeOrg() {
        this.$refs['dataForm'].validate((valid) => {
          if (!valid) {
            return false
          }
          const tempData = Object.assign({}, this.editDialog.temp)
          if (tempData.id) {
            delete tempData['status']
          }
          storeJrOrganization(tempData).then(() => {
            this.getList()
            this.editDialog.visible = false
            notify.success()
          })
        })
      },
      // 更改状态
      changeStatus(row) {
        storeJrOrganization(row).then(() => {
          notify.success()
          this.getList()
        })
      },
      // 删除
      deleteOrg(row) {
        messageBox.confirmDelete('删除后不可恢复，确定要删除吗？', () => {
          deleteJrOrganization(row.id).then(() => notify.success(), () => notify.error).finally(() => this.getList())
        })
      },
      // 获取日志
      getAuditsList(type, id) {
        if (id) {
          getAudits(type, id).then(response => {
            this.auditsList = response.data.audits
          })
        } else {
          this.auditsList = []
        }
      }
    }
  }
</script>
<style lang="scss" scoped>
    .dialogProduct {
        .filter-item {
            width: 145px;
        }
        /deep/ .el-dialog__body{
            padding-top: 0;
            max-height: 600px;
            overflow: auto;
            .el-textarea__inner{
                min-height: 100px!important;
            }
        }
        .el-tag--small {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .fw-city{
            /deep/ .el-form-item__label:before {
                content: '*';
                color: #f56c6c;
                margin-right: 4px;
            }
        }
    }
</style>
