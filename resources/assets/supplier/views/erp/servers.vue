<template>
    <div class="app-container">

        <el-table :data="list.items"
                  v-loading="listLoading"
                  element-loading-text="Loading"
                  border
                  fit
                  highlight-current-row>

            <el-table-column
                    label="ID"
                    width="50"
                    align="center">
                <template slot-scope="scope">
                    {{scope.row.id}}
                </template>
            </el-table-column>

            <el-table-column align="center" label='中介公司名称' width="280">
                <template slot-scope="scope">
                    <span><el-tag>{{ scope.row.abbr }}</el-tag> {{ scope.row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='缩写' width="130">
                <template slot-scope="scope">
                    <span>{{ scope.row.abbr }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="地址">
                <template slot-scope="scope">
                    <span>{{scope.row.host}}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="APP 地址">
                <template slot-scope="scope">
                    <span>{{scope.row.api_host}}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="IP">
                <template slot-scope="scope">
                    <span>{{ scope.row.ip }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="版本">
                <template slot-scope="scope">
                    <span>{{ scope.row.erp_status.version }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="分支">
                <template slot-scope="scope">
                    <span>{{ scope.row.erp_status.branch }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Ping">
                <template slot-scope="scope">
                    <span>{{ scope.row.erp_status.ping }}</span>
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
    </div>
</template>

<script>
  import { listHelper } from '@common/utils/listHelper'
  import { getServerPaginator } from '../../api/erp'

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
          importance: undefined,
          title: undefined,
          type: undefined,
          sort: null
        },
        // 其他变量
        temp: {}
      }
    },
    created() {
      this.getList()
    },
    methods: {
      // 获取列表
      getList() {
        this.listLoading = true
        getServerPaginator(this.listQuery).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.serverPaginator)
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
      }
    }
  }
</script>
