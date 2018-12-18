<template>
    <div class="app-container" :style="{height: this.$store.getters.tableHeight + 200 + 'px'}">
        <div class="order-detail">
            <el-card>
                <el-row>
                    <div class="title">订单信息</div>
                </el-row>
                <el-row class="row-space">
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">客户名称</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].customer.store_info?list.items[0].customer.store_info.name:'暂无'}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">订单编号</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].order_number}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">订货时间</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].order_at}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
                <el-row class="row-space">
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">订货数量合计</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].number}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">订单金额（元）</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].price}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">发货数量合计</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].send_number}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">发货金额（元）</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].send_price}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
                <el-row class="row-space">
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">客户联系人</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].customer.name}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">客户联系电话</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].customer.mobile}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">客户地址</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].customer.store_info?list.items[0].customer.store_info.address:'暂无'}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                </el-row>
                <el-row class="row-space">
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-row>
                            <el-col :span="8">
                                <span class="label">订单状态</span>
                            </el-col>
                            <el-col :span="16">
                                <span class="text" v-if="list.items[0]">{{list.items[0].status_alias}}</span>
                            </el-col>
                        </el-row>
                    </el-col>
                    <el-col :xs="24" :sm="12" :md="6">
                        <el-button
                                v-if="list.items[0] && list.items[0].status === 'signing'"
                                type="primary"
                                @click="orderTrack(list.items[0])">订单跟踪</el-button>
                    </el-col>
                </el-row>
            </el-card>
            <el-card>
                <el-row>
                    <div class="title">订货明细</div>
                </el-row>
                <el-row>
                    <el-col :xs="24" :sm="12" class="row-space">
                        <span class="sort-title">排序</span>
                        <el-button-group>
                            <el-button
                                    :type="sort_type == 'product_number'?'primary':'info'"
                                    @click="sortType('product_number')">
                                订货数量
                                <svg-icon icon-class="down" v-if="isSortDown.product_number" />
                                <svg-icon icon-class="up" v-if="!isSortDown.product_number" />
                            </el-button>
                            <el-button
                                    :type="sort_type == 'product_total_price'?'primary':'info'"
                                    @click="sortType('product_total_price')">订货金额
                                <svg-icon icon-class="down" v-if="isSortDown.product_total_price" />
                                <svg-icon icon-class="up" v-if="!isSortDown.product_total_price" />
                            </el-button>
                        </el-button-group>
                    </el-col>
                    <el-col :xs="24" :sm="12" align="right" class="row-space">
                        <el-button type="primary" @click="handleDownload">导出Excel表格</el-button>
                    </el-col>
                </el-row>
                <el-table :data="productLists.items"
                          v-loading="productLists.listLoading"
                          element-loading-text="加载中"
                          fit
                          class="order-table row-space"
                          show-summary
                          :height="this.$store.getters.tableHeight"
                          highlight-current-row>
                    <el-table-column
                            label="序号"
                            type="index"
                            width="50"
                            align="center">
                    </el-table-column>
                    <el-table-column align="center" label="商品图片">
                        <template slot-scope="scope">
                            <div v-viewer v-if="scope.row.product.pictures">
                                <img class="el-table-viewer-img"
                                     v-for="(item, index) in scope.row.product.pictures"
                                     :src="item"
                                     :key="index"
                                     v-show="index === 0"/>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="商品条码" width="160">
                        <template slot-scope="scope">
                            <span class="text-warning">{{scope.row.product.bar_code}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="商品名称" width="130">
                        <template slot-scope="scope">
                            <span>{{scope.row.product.name}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="商品品牌" width="120">
                        <template slot-scope="scope">
                            <span v-if="scope.row.product.brand">{{scope.row.product.brand.name}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订货单位">
                        <template slot-scope="scope">
                            <span>{{scope.row.product.order_unit}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订货规格">
                        <template slot-scope="scope">
                            <span>{{scope.row.product.product_specifications}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="商品订货价（元）" width="120">
                        <template slot-scope="scope">
                            <span>{{scope.row.product_price}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订货数量" prop="product_number">
                        <template slot-scope="scope">
                            <span>{{scope.row.product_number}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="订单金额（元）" width="110" prop="product_total_price">
                        <template slot-scope="scope">
                            <span>{{scope.row.product_total_price}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="发货数量" width="150" prop="send_number">
                        <template slot-scope="scope">
                            <template  v-if="scope.row.edit">
                                <el-input class="edit-input" type="number" v-model="scope.row.send_number" @change="autoCompute(scope.row)" @keyup.enter="autoCompute(scope.row)"></el-input>
                                <span class="cancel-btn" @click="cancelEdit(scope.row)"><i class="el-icon-refresh"></i>取消</span>
                            </template>
                            <span v-else>{{scope.row.send_number}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="发货金额（元）" width="110" prop="send_total_price">
                        <template slot-scope="scope">
                            <template v-if="scope.row.edit">
                                <el-input v-model="scope.row.send_total_price" disabled></el-input>
                            </template>
                            <span v-else>{{scope.row.send_total_price}}</span>
                        </template>
                    </el-table-column>
                    <el-table-column align="center" label="操作" width="150"
                                     class-name="small-padding" fixed="right" v-if="list.items[0]&&list.items[0].status == 'approving'">
                        <template slot-scope="scope">
                            <el-button type="primary" plain @click="confirmEdit(scope.row)" icon="el-icon-circle-check-outline" v-if="scope.row.edit">保存</el-button>
                            <el-button type="primary" plain @click='scope.row.edit=!scope.row.edit' icon="el-icon-edit" v-if="!scope.row.edit">修改</el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-row class="row-space">
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'approving'">
                        <el-button type="primary" @click="dialogApprove = true">审核</el-button>
                        <el-button type="info" @click="dialogReject = true">无法供货</el-button>
                    </el-col>
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'confirming'">
                        <el-button type="info" disabled>请等待客户确认发货数量</el-button>
                    </el-col>
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'unsupply'">
                        <el-button type="info" disabled>无法供货</el-button>
                    </el-col>
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'shipping'">
                        <el-button type="primary" @click="deliver">发货</el-button>
                    </el-col>
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'signing' && !list.items[0].can_force_signed">
                        <el-button type="info" disabled>请等待客户确认签收</el-button>
                    </el-col>
                    <el-col :span="24" align="center" v-if="list.items[0]&&list.items[0].status == 'signing' && list.items[0].can_force_signed">
                        <el-button type="primary" @click="forceSigned">强制签收</el-button>
                    </el-col>
                </el-row>
            </el-card>
            <el-dialog
                    v-el-drag-dialog
                    title="订单审核"
                    :visible.sync="dialogApprove"
                    width="350px"
                    class="order-dialog">
                <div class="text-center">
                    <span>是否确认订单发货数量无误？</span>
                </div>
                <div class="row-space text-center">
                    <span class="label">如需修改，请取消审核</span>
                </div>
                <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="approve">确定</el-button>
                <el-button type="info" @click="dialogApprove = false">取消</el-button>
            </span>
            </el-dialog>
            <el-dialog
                    v-el-drag-dialog
                    title="订单审核"
                    :visible.sync="dialogReject"
                    width="350px"
                    class="order-dialog">
                <div class="text-center">
                    <span>是否确认该订单无法供货？</span>
                </div>
                <div class="row-space text-center">
                    <span class="label">如正常供货，请点击取消</span>
                </div>
                <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="reject">确定</el-button>
                <el-button type="info" @click="dialogReject = false">取消</el-button>
            </span>
            </el-dialog>
            <el-dialog
                    v-el-drag-dialog
                    title="发货派单"
                    :visible.sync="dialogDeliver"
                    width="500px"
                    class="order-dialog">
                <el-form ref="deliverForm" class="order-dialog-form" :model="deliver_data" :rules="rules" label-width="110px">
                    <el-form-item label="订单编号">
                        <span>{{ deliver_data.order_number }}</span>
                    </el-form-item>
                    <el-form-item label="订货时间">
                        <span>{{ deliver_data.order_at }}</span>
                    </el-form-item>
                    <el-form-item label="客户地址">
                        <span>{{ deliver_data.address }}</span>
                    </el-form-item>
                    <el-form-item label="送货司机" prop="driver_id">
                        <el-select v-model="deliver_data.driver_id"
                                   @change="autoCarNumber(deliver_data.driver_id)"
                                   filterable
                                   clearable
                                   placeholder="请选择送货司机">
                            <el-option
                                    v-for="item in driverList"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="送货车牌" prop="car_number">
                        <el-input v-model="deliver_data.car_number" placeholder="请输入送货车牌"></el-input>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="deliver_save">确认发货</el-button>
                <el-button type="info" @click="dialogDeliver = false">取消</el-button>
            </span>
            </el-dialog>
            <el-dialog
                    v-el-drag-dialog
                    title="订单跟踪"
                    :visible.sync="dialogTrack"
                    width="500px"
                    class="order-dialog order-log-dialog">
                <div class="steps-box">
                    <el-steps direction="vertical" :active="1">
                        <el-step
                                v-for="(item, index) in orderLogs"
                                :key="item.id"
                                :status=" index!==0? 'wait' : 'finish'"
                                :title="item.content" :description="item.created_at">
                            <span slot="icon" class="status-icon"></span>
                        </el-step>
                    </el-steps>
                </div>
            </el-dialog>
        </div>
    </div>
</template>

<script>
  import { GetOrderLists, GetOrderDetail, storeOrder, handleOrder, getOrderLog } from '../../api/order'
  import { DriverList } from '../../api/driver'
  import { listHelper } from '@common/utils/listHelper'
  import 'viewerjs/dist/viewer.css'
  import Viewer from 'v-viewer'
  import Vue from 'vue'
  Vue.use(Viewer)
  import { notify } from '@common/utils/helper'
  import elDragDialog from '@supplier/directive/el-dragDialog'

  export default {
    directives: { elDragDialog },
    data() {
      return {
        sort_type: 'product_number',
        isSortDown: {
          product_number: true,
          product_total_price: true
        },
        dialogApprove: false,
        dialogReject: false,
        dialogDeliver: false,
        dialogTrack: false,
        deliver_data: {
          order_number: '',
          order_at: '',
          address: '',
          driver_id: '',
          car_number: ''
        },
        rules: {
          driver_id: [
            { required: true, message: '请选择送货司机' }
          ],
          car_number: [
            { required: true, message: '请输入送货车牌' }
          ]
        },
        editable: false,
        downloadLoading: false,
        list: {
          items: [],
          total: null
        },
        productLists: {
          items: [],
          total: null,
          listLoading: true
        },
        listQuery: {
          page: 1,
          limit: 20,
          sort: '-id'
        },
        productQuery: {
          page: 1,
          sort: 'product_number'
        },
        driverList: [],
        filename: '订货明细',
        orderLogs: []
      }
    },
    created() {
      this._getOrderLists()
    },
    methods: {
      _getOrderLists() {
        const params = {
          id: this.$route.params.id
        }
        GetOrderLists(this.listQuery, params).then(response => {
          listHelper.setList(this.list, this.listQuery, response.data.orderPaginator)
          this._getOrderProduct()
        })
      },
      _getOrderProduct() {
        this.productQuery.sort = (this.isSortDown[this.sort_type] ? '-' : '+') + this.sort_type
        this.productLists.listLoading = true
        GetOrderDetail(this.productQuery, { order_id: this.$route.params.id }).then(response => {
          listHelper.setList(this.productLists, this.productQuery, response.data.orderProductPaginator)
          const items = this.productLists.items
          this.productLists.items = items.map(v => {
            this.$set(v, 'edit', false) // https://vuejs.org/v2/guide/reactivity.html
            v.sendNumber = v.send_number //  will be used when user click the cancel botton
            v.sendTotalPrice = v.send_total_price //  will be used when user click the cancel botton
            return v
          })
        }).finally(() => {
          this.productLists.listLoading = false
        })
      },
      sortType(type) {
        if (this.sort_type === type) {
          this.isSortDown[type] = !this.isSortDown[type]
        } else {
          this.isSortDown[type] = true
          this.isSortDown[this.sort_type] = true
          this.sort_type = type
        }
        this._getOrderProduct()
      },
      approve() {
        this._handleOrder(this.list.items[0].id, 'supplier_shiping')
      },
      reject() {
        this._handleOrder(this.list.items[0].id, 'supplier_reject')
      },
      forceSigned() {
        this._handleOrder(this.list.items[0].id, 'system_signed')
      },
      _handleOrder(id, status, car_number, driver_id) {
        handleOrder(id, status, car_number, driver_id).then((response) => {
          notify.success('操作成功')
          this._getOrderLists()
        }).finally(() => {
          if (status === 'supplier_shiping') {
            this.dialogApprove = false
          } else if (status === 'supplier_reject') {
            this.dialogReject = false
          } else if (status === 'supplier_shipped') {
            this.dialogDeliver = false
          }
        })
      },
      deliver() {
        const more = {
          status: '已开启'
        }
        DriverList(more).then(response => {
          this.driverList = response.data.driverList
        }).finally(() => {
          this.deliver_data.driver_id = ''
          this.deliver_data.car_number = ''
          this.deliver_data.order_number = this.list.items[0].order_number
          this.deliver_data.order_at = this.list.items[0].order_at
          this.deliver_data.address = this.list.items[0].customer.store_info ? this.list.items[0].customer.store_info.address : '暂无'
          if (!this.driverList) {
            this.driverList = []
          }
          this.dialogDeliver = true
        })
      },
      deliver_save() {
        this.$refs.deliverForm.validate((valid) => {
          if (valid) {
            this._handleOrder(this.list.items[0].id, 'supplier_shipped', this.deliver_data.car_number, this.deliver_data.driver_id)
          } else {
            notify.warning('请完善配送信息')
          }
        })
      },
      update() {
        this.editable = false
      },
      cancelEdit(row) {
        row.edit = false
        row.send_number = row.sendNumber
        row.send_total_price = row.sendTotalPrice
      },
      confirmEdit(row) {
        if (parseFloat(row.sendNumber) === parseFloat(row.send_number)) {
          row.edit = false
          return false
        }
        const paramsList = []
        this._.each(this.productLists.items, (o) => {
          paramsList.push({ id: o.id, product_id: o.product_id, product_number: o.product_number, send_number: o.send_number })
        })
        const params = {
          id: row.order_id,
          order_products: paramsList
        }
        storeOrder(params).then((response) => {
          row.sendNumber = row.send_number
          row.sendTotalPrice = row.send_total_price
          const orderparams = {
            id: this.$route.params.id
          }
          GetOrderLists(this.listQuery, orderparams).then(response => {
            listHelper.setList(this.list, this.listQuery, response.data.orderPaginator)
            notify.success('操作成功')
          })
        }).catch(() => {
          row.send_number = row.sendNumber
          row.send_total_price = row.sendTotalPrice
        }).finally(() => {
          row.edit = false
        })
      },
      handleDownload() {
        this.downloadLoading = true
        import('@supplier/vendor/Export2Excel').then(excel => {
          const tHeader = ['商品条码', '商品名称', '商品品牌', '订货单位', '订货规格', '商品订货价(元)', '订货数量', '订单金额(元)', '发货数量', '发货金额(元)']
          const filterVal = ['bar_code', 'name', 'brand', 'order_unit', 'spec_number', 'product_price', 'product_number', 'product_total_price', 'send_number', 'send_total_price']
          const list = this.productLists.items
          const data = this.formatJson(filterVal, list)
          excel.export_json_to_excel({
            header: tHeader,
            data,
            filename: this.filename,
            autoWidth: this.autoWidth
          })
          this.downloadLoading = false
        })
      },
      formatJson(filterVal, jsonData) {
        return jsonData.map(v => filterVal.map(j => {
          if (j === 'bar_code') {
            return v.product[j]
          } else if (j === 'name') {
            return v.product[j]
          } else if (j === 'brand') {
            return v.product[j] ? v.product[j].name : ''
          } else if (j === 'order_unit') {
            return v.product[j]
          } else if (j === 'spec_number') {
            return v.product.product_specifications
          } else {
            return v[j]
          }
        }))
      },
      autoCompute(data) {
        const reg = /^([1-9]\d*|[0]{1,1})$/
        if (!reg.test(data.send_number)) {
          notify.warning('请输入整数')
          data.send_number = data.sendNumber
          return
        }
        if (parseFloat(data.send_number) > parseFloat(data.product_number)) {
          notify.warning('发货数量不能大于订货数量')
          data.send_number = data.sendNumber
          data.send_total_price = data.sendTotalPrice
          return false
        }
        data.send_total_price = parseFloat(data.send_price) * data.send_number
      },
      autoCarNumber(item) {
        if (item) {
          this._.findIndex(this.driverList, (o) => { return o.id === item })
          this.deliver_data.car_number = this.driverList[this._.findIndex(this.driverList, (o) => { return o.id === item })].license
        } else {
          this.deliver_data.car_number = ''
        }
      },
      orderTrack(order) {
        this.dialogTrack = true
        getOrderLog({ page: 1, sort: '-created_at' }, order.id).then((response) => {
          this.orderLogs = response.data.orderLogPaginator.items
        })
      }
    }
  }

</script>
<style lang="scss" scoped>
    .order-detail{
        height: 100%;
        overflow-y: auto;
    }
    .title {
        font-size: 14px;
        color: #F39800;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
    }
    .label{
        font-size: 13px;
        color: #999;
    }
    .text{
        word-break: break-all;
        font-size: 13px;
        color: #333;
    }
    .sort-title{
        font-size: 12px;
    }
    .row-space{
        margin-top: 15px;
    }
    .img-box{
        width: 100px;
        height: 100px;
        margin-right: 10px;
        position: relative;
        img{
            width: 100%;
        }
        .mask {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: 120px;
            background: rgba(0, 0, 0, 0.6);
            /*opacity: 0;*/
            display: none;
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
                display: inline-block;
            }
        }
    }
    .edit-input{
        display: inline-block;
        width: 85px;
    }
    .cancel-btn{
        cursor: pointer;
    }
    .steps-box{
        padding: 0 25px;
        .status-icon{
            display: block;
            width: 14px;
            height: 14px;
            background: #F39800;
            border-radius: 14px;
        }
        .is-wait .status-icon{
            background: #ddd;
        }
    }
</style>
