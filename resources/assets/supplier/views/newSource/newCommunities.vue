<template>
    <div class="app-container">
        <el-form :inline="true">
            <el-form-item>
                <el-input size="small" v-model="search.keywords" placeholder="楼盘名称 ↵" @keyup.enter="getList('refresh')" @change="getList('refresh')"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button size="small" @click="getList('refresh')" icon="el-icon-refresh">
                    刷新
                </el-button>
                <el-button type="primary" icon="document" @click="showAddNewCommunityDialog"
                           size="small"
                           :loading="downloadLoading">新增
                </el-button>
            </el-form-item>
        </el-form>


        <el-table :data="list.items"
                  v-loading="listLoading"
                  element-loading-text="Loading"
                  border
                  fit
                  :height="this.$store.getters.tableHeight"
                  highlight-current-row>

            <el-table-column align="center" fixed label='楼盘名称' width="250">
                <template slot-scope="scope">
                    <span>
                        <el-tag size="mini" type="danger" v-if="scope.row.is_new">新</el-tag>
                            <el-tooltip v-if="!!scope.row.deleted_at" content="ERP 已删除该楼盘" :open-delay='500'>
                                <span style="text-decoration:line-through">{{ scope.row.name }}</span>
                            </el-tooltip>
                        <span v-else>{{ scope.row.name }}</span>
                        <br>
                        <el-tag size="mini" type="info">{{scope.row.server.name}}</el-tag>
                    </span>
                </template>
            </el-table-column>

            <el-table-column align="center" fixed label='城市' width="90">
                <template slot-scope="scope">
                    <span>
                        {{ scope.row.city.name }}
                    </span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="展示" width="290">
                <template slot-scope="scope">
                    <span v-if="scope.row.is_new
                    && scope.row.is_valid
                    && scope.row.erp_status
                    && scope.row.erp_open_status">
                        <span v-if="scope.row.dc_at && scope.row.openCompanies.length">
                            <el-tag size="mini" type="success">合作公司</el-tag>
                        </span>
                        <span v-if="scope.row.fxs_at">
                            <el-tag size="mini" type="success">自己公司</el-tag>
                            <el-tag size="mini" type="success">好房多</el-tag>
                        </span>
                        <span v-if="scope.row.is_web_show">
                            <el-tag size="mini" type="success">网站</el-tag>
                        </span>
                        <span v-if="!scope.row.fxs_at && !scope.row.dc_at && !scope.row.is_web_show">
                            <el-tag size="mini" type="info">不展示</el-tag>
                        </span>
                    </span>
                    <span v-else>
                        <el-tag size="mini" type="info">不展示</el-tag>
                    </span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="合作公司">
                <template slot-scope="scope">
                    <span v-if="scope.row.openCompanies.length">
                        <el-tag v-for="company in scope.row.openCompanies" :key="company.id">{{ company.name }}</el-tag>
                    </span>
                    <span v-else>没有合作公司</span>
                </template>
            </el-table-column>

            <el-table-column label="ERP 控制" align="center">

                <el-table-column align="center" label="审核" width="80">
                    <template slot-scope="scope">
                        <el-tooltip content="ERP 进行审核操作，此处不能操作" :open-delay='500'>
                            <el-tag v-if="scope.row.erp_status" size="mini" type="success">已审核</el-tag>
                            <el-tag v-else size="mini" type="danger">未审核</el-tag>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="分销商" width="70">
                    <template slot-scope="scope">
                        <el-tooltip content="ERP 进行操作，此处不能操作" :open-delay='500'>
                            <el-tag v-if="!!scope.row.fxs_at" size="mini" type="success">展示</el-tag>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="网站" width="70">
                    <template slot-scope="scope">
                        <el-tooltip content="ERP 进行操作，此处不能操作" :open-delay='500'>
                            <el-tag v-if="!!scope.row.erp_web_at" size="mini" type="success">展示</el-tag>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="报备" width="70">
                    <template slot-scope="scope">
                        <el-tooltip content="ERP 进行操作，此处不能操作" :open-delay='500'>
                            <el-tag v-if="scope.row.erp_open_status" size="mini" type="success">开启</el-tag>
                        </el-tooltip>
                    </template>
                </el-table-column>

            </el-table-column>

            <el-table-column label="数据中心状态" align="center">

                <el-table-column align="center" label="合作" width="60">
                    <template slot-scope="scope">
                        <el-tooltip content="惠域通宝合作楼盘" :open-delay='500'>
                            <el-tag v-if="!!scope.row.dc_at" size="mini" type="success">合作</el-tag>
                        </el-tooltip>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="开启" width="60">
                    <template slot-scope="scope">
                        <el-switch
                                @change="changeStatus(scope.row, 'is_valid', scope.row.is_valid)"
                                v-model="scope.row.is_valid">
                        </el-switch>
                    </template>
                </el-table-column>

                <el-table-column align="center" label="网站" width="60">
                    <template slot-scope="scope">
                        <el-switch
                                @change="changeStatus(scope.row, 'is_web_show', scope.row.is_web_show)"
                                v-model="scope.row.is_web_show">
                        </el-switch>
                    </template>
                </el-table-column>

            </el-table-column>

            <el-table-column align="center" fixed="right" label="操作" width="180" class-name="small-padding fixed-width">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" @click="showOpenCompaniesDialog(scope.row)">合作</el-button>
                    <el-button type="info" size="mini" @click="syncNewCommunity(scope.row)" icon="el-icon-refresh">同步
                    </el-button>
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

        <!--新增楼盘弹窗-->
        <el-dialog title="新增楼盘" :visible.sync="addNewCommunityDialogVisible">
            <el-form ref="dataForm" label-position="left" label-width="70px"
                     style='width: 400px; margin-left:50px;'>
                <el-form-item label="中介公司" prop="server">
                    <el-select class="filter-item" v-model="temp.selectedServerId" @change="serverChanged"
                               placeholder="请选择">
                        <el-option v-for="item in servers" :key="item.id" :label="item.name"
                                   :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="楼盘" prop="erp-new-community">
                    <el-select filterable class="filter-item" v-model="temp.selectedCommunityId"
                               placeholder="请先选择中介公司">
                        <el-option v-for="item in temp.serverCommunities" :key="item.id" :label="item.title"
                                   :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-form>
            <div slot="footer" class="dialog-footer">
                <el-button @click="addNewCommunityDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="addNewCommunity">确定</el-button>
            </div>
        </el-dialog>

        <!--新楼盘合作公司管理-->
        <el-dialog title="合作公司" :visible.sync="openCompaniesDialogVisible">
            <el-transfer
                    filterable
                    filter-placeholder="请输入中介公司名称"
                    :titles="['未合作', '合作中']"
                    v-model="temp.openCompanies"
                    :props="{
                      key: 'id',
                      label: 'name'
                    }"
                    :data="servers">
            </el-transfer>
            <div slot="footer" class="dialog-footer">
                <el-button @click="openCompaniesDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="saveOpenCompanies">确定</el-button>
            </div>
        </el-dialog>

    </div>
</template>

<script>
  import {
    getNewCommunityPaginator,
    getNewCommunitiesFromERP,
    storeNewCommunity,
    syncNewCommunityOpenCompanies,
    updateNewCommunityOpenCompanies,
    webShowNewCommunity
  } from '../../api/newSource'
  import { getServers } from '../../api/system'
  import { listHelper } from '@common/utils/listHelper'
  import { notify } from '@common/utils/helper'

  export default {
    data() {
      return {
        list: {
          items: null,
          total: null,
          listLoading: true
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: null
        },
        search: {
          with_deleted: true,
          keywords: null
        },
        servers: [],
        downloadLoading: false,
        // 其他变量
        temp: {},
        // 新增楼盘弹窗,
        addNewCommunityDialogVisible: false,
        // 合作公司弹窗
        openCompaniesDialogVisible: false
      }
    },
    created() {
      this.getList()
    },
    methods: {
      // 获取列表
      getList(action = 'init') {
        this.listLoading = true
        getNewCommunityPaginator(this.listQuery, this.search).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.newCommunityPaginator)
          if (action === 'refresh') {
            notify.success()
          }
          this.listLoading = false
        })
      },
      // 更改楼盘是否在网站展示
      changeStatus(item, field, value) {
        webShowNewCommunity(item.id, field, value).then(response => {
          if (response.data) {
            this.$notify({
              title: '成功',
              message: '操作成功',
              type: 'success',
              duration: 2000
            })
          } else {
            item[field] = !item[field]
            this.$notify({
              title: '失败',
              message: '操作失败',
              type: 'error',
              duration: 2000
            })
          }
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
      // 获取中介公司列表
      getServerList() {
        if (!this.servers.length) {
          getServers().then(response => {
            this.servers = response.data.servers
          })
        }
      },
      /**
       * 新增楼盘
       */
      // 显示添加楼盘窗口
      showAddNewCommunityDialog() {
        this.temp = {
          // 中介公司列表
          servers: [],
          // 中介公司的新房列表
          serverCommunities: [],
          // 选择的中介公司 id
          selectedServerId: null,
          // 选择的小区 ID
          selectedCommunityId: null,
          // 选择的中介公司对象
          serverSelected: {},
          // 选择的小区对象
          selectedCommunity: {},
          // 选择的小区详细信息
          selectedCommunityInfo: {}
        }
        this.addNewCommunityDialogVisible = true
        this.getServerList()
      },
      // 选择中介公司
      serverChanged(value) {
        this.temp.serverSelected = this.servers.filter((item) => {
          return item.id === value
        })[0]
        // 获取对应中介公司的新楼盘列表
        getNewCommunitiesFromERP(this.temp.serverSelected.host).then(response => {
          this.temp.serverCommunities = response.data
        })
      },
      // 添加楼盘
      addNewCommunity() {
        storeNewCommunity(this.temp.selectedCommunityId, this.temp.selectedServerId).then(response => {
          this.addNewCommunityDialogVisible = false
          this.getList()
          this.$notify({
            title: '成功',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
        })
      },
      /**
       * 管理合作楼盘
       */
      // 管理开放的中介公司
      showOpenCompaniesDialog(row) {
        this.temp = {
          openCompanies: this._.map(row.openCompanies, 'id'),
          row: row
        }
        this.getServerList()
        this.openCompaniesDialogVisible = true
      },
      saveOpenCompanies() {
        updateNewCommunityOpenCompanies(this.temp.row.id, this.temp.openCompanies).then(response => {
          this.getList()
          this.openCompaniesDialogVisible = false
          this.$notify({
            title: '成功',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
        })
      },
      /**
       * 同步楼盘信息
       */
      syncNewCommunity(row) {
        syncNewCommunityOpenCompanies(row.id).then(response => {
          this.getList()
          this.$notify({
            title: '同步成功',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
        })
      }
    }
  }
</script>
