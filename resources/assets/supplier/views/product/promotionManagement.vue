<template>
    <div class="app-container">
        <el-card>
            <el-row type="flex" justify="space-between">
                <el-col :span="24">
                    <el-form :inline="true" :model="more">
                        <el-form-item label="类型">
                            <el-select v-model="more.type" clearable placeholder="请选择">
                                <el-option
                                        v-for="item in typeOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="期限">
                            <el-select v-model="more.term" clearable placeholder="请选择">
                                <el-option
                                        v-for="item in termOptions"
                                        :key="item.value"
                                        :label="item.label"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item label="有效期">
                            <el-date-picker
                                    v-model="more.effect_date"
                                    type="date"
                                    placeholder="请设置有效日期">
                            </el-date-picker>
                        </el-form-item>
                        <el-form-item label="状态">
                            <el-select
                                    multiple
                                    collapse-tags
                                    style="margin-left: 20px;"
                                    v-model="more.status"
                                    placeholder="选择状态">
                                <el-option
                                        v-for="item in statusOptions"
                                        :key="item.value"
                                        :label="item.name"
                                        :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item prop="name">
                            <el-input v-model="more.amount" placeholder="请输入产品名称"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" @click="toAddProduct">新增</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-row>
            <el-table :data="lists"
                      fit
                      :height="this.$store.getters.tableHeight"
                      highlight-current-row>
                <el-table-column
                        type="selection"
                        width="55">
                </el-table-column>
                <el-table-column
                        label="序号"
                        type="index"
                        width="50"
                        align="center">
                </el-table-column>
                <el-table-column align="center" label="订单编号" width="160">
                    <template slot-scope="scope">
                        <span>{{scope.row.num}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订货时间" width="120">
                    <template slot-scope="scope">
                        <span>{{scope.row.creat_date}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="客户名称" width="100">
                    <template slot-scope="scope">
                        <span>{{scope.row.name}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订单金额（元）" width="120">
                    <template slot-scope="scope">
                        <span>{{scope.row.price}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="订单状态" width="80">
                    <template slot-scope="scope">
                        <span>{{scope.row.status}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="联系人" width="100">
                    <template slot-scope="scope">
                        <span>{{scope.row.contactName}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="联系电话" width="120">
                    <template slot-scope="scope">
                        <span>{{scope.row.contactMobile}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="业务员" width="120">
                    <template slot-scope="scope">
                        <span>{{scope.row.saleMan}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="业务员联系电话" width="120">
                    <template slot-scope="scope">
                        <span>{{scope.row.saleMobile}}</span>
                    </template>
                </el-table-column>
                <el-table-column align="center" label="操作" width="400"  fixed="right" class-name="small-padding">
                    <template slot-scope="scope">
                        <el-button type="primary" plain @click="toOrderBill(scope.row)">查看</el-button>
                        <el-button type="primary" plain @click="setAgency(scope.row)">设定中介公司</el-button>
                        <el-button type="primary" plain @click="pause(scope.row)">暂停</el-button>
                        <el-button type="primary" plain @click="toAddProduct(scope.row)">修改</el-button>
                        <el-button type="primary" plain @click="delete(scope.row)">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <el-row type="flex" justify="end" class="pagination-container">
                <el-pagination
                        @size-change="handleSizeChange"
                        @current-change="handleCurrentChange"
                        :current-page="currentPage"
                        :page-sizes="[100, 200, 300, 400]"
                        :page-size="100"
                        layout="total, prev, pager, next, jumper"
                        :total="400">
                </el-pagination>
            </el-row>


            <el-dialog title="设定合作公司" :visible.sync="cooperativeDialog">
                <el-row>
                    <el-col :span="10">
                        <div class="grid-content bg-purple">
                            <el-table
                                    ref="multipleTable"
                                    :data="noCoopData"
                                    tooltip-effect="dark"
                                    style="width: 100%"
                                    @selection-change="handleSelectionChange">
                                <el-table-column
                                        type="selection"
                                        width="55">
                                </el-table-column>
                                <el-table-column
                                        prop="name"
                                        label="未合作"
                                        width="200">
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-col>
                    <el-col :span="4">
                        <div class="grid-content bg-purple-light">
                            <div class="left-icon">
                                <el-button type="primary" circle size="medium" @click="addNotCoop">
                                    <svg-icon icon-class="fangxiang" class="fx-icon"></svg-icon>
                                </el-button>
                            </div>
                            <div class="right-icon">
                                <el-button type="success" circle size="medium" @click="addCoop">
                                    <svg-icon icon-class="fangxiang"></svg-icon>
                                </el-button>
                            </div>

                        </div>
                    </el-col>
                    <el-col :span="10">
                        <div class="grid-content bg-purple">
                            <el-table
                                    ref="multipleTable"
                                    :data="coopData"
                                    tooltip-effect="dark"
                                    style="width: 100%"
                                    @selection-change="handleSelectionChange">
                                <el-table-column
                                        type="selection"
                                        width="55">
                                </el-table-column>
                                <el-table-column
                                        prop="name"
                                        label="已合作"
                                        width="200">
                                </el-table-column>
                            </el-table>
                        </div>
                    </el-col>
                </el-row>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="cancelSet">取 消</el-button>
                    <el-button type="primary" @click="confirmSet">确 定</el-button>
                </div>
            </el-dialog>


        </el-card>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        more: {
          type: '1',
          term: '1',
          effective_at: '',
          status: '',
          product_name: ''
        },
        statusOptions: [
          { name: '待审核', value: '待审核' },
          { name: '待确认', value: '待确认' },
          { name: '待发货', value: '待发货' },
          { name: '待签收', value: '待签收' },
          { name: '已签收', value: '已签收' },
          { name: '强制签收', value: '强制签收' }
        ],
        typeOptions: [
          {
            value: '1',
            label: '全部类型'
          },
          {
            value: '2',
            label: '贷款'
          },
          {
            value: '3',
            label: '基金'
          },
          {
            value: '4',
            label: '定期'
          }
        ],
        defaultType: '1',
        termOptions: [
          {
            value: '0',
            label: '全部期限'
          },
          {
            value: '1',
            label: '5年'
          },
          {
            value: '2',
            label: '10年'
          },
          {
            value: '3',
            label: '20年'
          },
          {
            value: '4',
            label: '30年'
          },
          {
            value: '5',
            label: '30年以上'
          }
        ],
        defaultTerm: '1',
        lists: [
          {
            num: 'HNZZ9854201809270001',
            creat_date: '2018-11-18 10:22',
            effect_date: '2018-11-18 10:22',
            client_name: '芙蓉兴盛郑州金水区001店',
            amount: 7898.50,
            status: '待审核',
            name: '客户名',
            price: '100',
            contactName: '客户名',
            contactMobile: '12345678900',
            saleMan: '程铮',
            saleMobile: '12345678900'
          },
          {
            num: 'HNZZ9854201809270001',
            creat_date: '2018-11-18 10:22',
            effect_date: '2018-11-18 10:22',
            client_name: '芙蓉兴盛郑州金水区001店',
            amount: 7898.50,
            status: '待审核',
            name: '客户名',
            price: '100',
            contactName: '客户名',
            contactMobile: '12345678900',
            saleMan: '程铮',
            saleMobile: '12345678900'
          },
          {
            num: 'HNZZ9854201809270001',
            creat_date: '2018-11-18 10:22',
            effect_date: '2018-11-18 10:22',
            client_name: '芙蓉兴盛郑州金水区001店',
            amount: 7898.50,
            status: '待审核',
            name: '客户名',
            price: '100',
            contactName: '客户名',
            contactMobile: '12345678900',
            saleMan: '程铮',
            saleMobile: '12345678900'
          },
          {
            num: 'HNZZ9854201809270001',
            creat_date: '2018-11-18 10:22',
            effect_date: '2018-11-18 10:22',
            client_name: '芙蓉兴盛郑州金水区001店',
            amount: 7898.50,
            status: '待审核',
            name: '客户名',
            price: '100',
            contactName: '客户名',
            contactMobile: '12345678900',
            saleMan: '程铮',
            saleMobile: '12345678900'
          },
        ],
        currentPage: 1,
        cooperativeDialog: false,
        noCoopData: [{
          name: '21世纪不动产郑州分公司1'
        },{
          name: '21世纪不动产郑州分公司2'
        },{
          name: '21世纪不动产郑州分公司3'
        },{
          name: '21世纪不动产郑州分公司4'
        }],
        coopData: [{
          name: '21世纪不动产郑州分公司5'
        },{
          name: '21世纪不动产郑州分公司6'
        },{
          name: '21世纪不动产郑州分公司7'
        },{
          name: '21世纪不动产郑州分公司8'
        }],
        selectData: null
      }
    },
    methods: {
      showOrderDetail(row) {
        this.$router.push({ path: 'order/detail', name: 'order.detail', params: { id: row.num }})
      },
      handleSizeChange(val) {
        console.log(`每页 ${val} 条`)
      },
      handleCurrentChange(val) {
        console.log(`当前页: ${val}`)
      },
      creatPriceAdjustment() {
        this.$router.push({
          path: 'product/bill',
          name: 'product.bill'
        })
      },
      toAddProduct(row) {
        this.$router.push({
          path: 'product/edit',
          name: 'productEdit',
          params: {
            id: row.id
          }
        })
      },
      toOrderBill(row) {
        this.$router.push({
          path: '/order/list',
          name: 'orderList',
          params: {
            id: row.id
          }
        })
      },
      setAgency() {
        this.cooperativeDialog = true
      },
      pause() {

      },
      delete() {

      },
      cancelSet() {
        this.cooperativeDialog = false
      },
      confirmSet() {
        this.cooperativeDialog = false
      },
      handleSelectionChange(row) {
        this.selectData = row
      },
      addNotCoop() {
        const coopData = _.cloneDeep(this.coopData)
        _.forEach(this.selectData, (value) => {
          _.remove(coopData, (item) => {
            return item.name === value.name
          })
        })
        this.coopData = []
        _.forEach(coopData, (item) => {
          this.coopData.push(item)
        })
        this.noCoopData = this.noCoopData.concat(this.selectData)
      },
      addCoop() {
        const noCoopData = _.cloneDeep(this.noCoopData)
        _.forEach(this.selectData, (value) => {
          _.remove(noCoopData, (item) => {
            return item.name === value.name
          })
        })
        this.noCoopData = []
        _.forEach(noCoopData, (item) => {
          this.noCoopData.push(item)
        })

        this.coopData = this.coopData.concat(this.selectData)
      }
    }
  }

</script>
<style lang="scss" scoped>
    .left-icon{
        margin-top: 20px;
    }
    .right-icon{
        margin-top: 20px;
    }
    .fx-icon{
        transform:rotate(180deg);
        -ms-transform:rotate(180deg); 	/* IE 9 */
        -moz-transform:rotate(180deg); 	/* Firefox */
        -webkit-transform:rotate(180deg); /* Safari 和 Chrome */
        -o-transform:rotate(180deg);
    }
</style>
