<template>
    <div class="app-container">

        <el-table :data="list.items"
                  v-loading="listLoading"
                  element-loading-text="Loading"
                  border
                  fit
                  :height="this.$store.getters.tableHeight"
                  highlight-current-row>

            <el-table-column
                    label="所属公司"
                    width="280"
                    align="center">
                <template slot-scope="scope">
                    <el-tag>{{ scope.row.server.abbr }}</el-tag>
                    {{scope.row.server && scope.row.server.name}}
                </template>
            </el-table-column>

            <el-table-column align="center" label='ERP ID' width="80">
                <template slot-scope="scope">
                    <span>{{ scope.row.eid }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label='姓名' width="120">
                <template slot-scope="scope">
                    <span>{{ scope.row.name }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="状态" width="90">
                <template slot-scope="scope">
                    <span>{{scope.row.status}}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="性别" width="80">
                <template slot-scope="scope">
                    <span>{{scope.row.gender}}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="电话">
                <template slot-scope="scope">
                    <span>{{ scope.row.mobile }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="学历">
                <template slot-scope="scope">
                    <span>{{ scope.row.education }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="毕业院校">
                <template slot-scope="scope">
                    <span>{{ scope.row.graduated_from }}</span>
                </template>
            </el-table-column>
            <el-table-column align="center" label="入职时间">
                <template slot-scope="scope">
                    <span>{{ scope.row.worked_at }}</span>
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
  import { getAgentPaginator } from '../../api/erp'

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
        getAgentPaginator(this.listQuery).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.agentPaginator)
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
