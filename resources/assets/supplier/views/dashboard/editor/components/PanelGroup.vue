<template>
  <el-row class="panel-group" :gutter="8">
    <el-col :xs="12" :sm="12" :md="12" :lg="6" class="card-panel-col">
      <div class='card-panel'>
        <div @click="toOrderList('approving')" class="link-type">
          <div class="card-panel-icon-wrapper icon-people" :title="panelData.being_approve_order">
            {{panelData.being_approve_order}}
          </div>
          <div class="card-panel-description">
            <div class="card-panel-text">待审核订单</div>
            <span class="card-panel-num">全部待审核的订单数量</span>
          </div>
          <div class="card-panel-right">
            <svg-icon icon-class="right" class-name="card-panel-icon" />
          </div>
        </div>
      </div>
    </el-col>
    <el-col :xs="12" :sm="12" :md="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div @click="toOrderList('shipping')" class="link-type">
          <div class="card-panel-icon-wrapper icon-message" :title="panelData.being_send_order">
            {{panelData.being_send_order}}
          </div>
          <div class="card-panel-description">
            <div class="card-panel-text">待发货订单</div>
            <span class="card-panel-num">全部待发货的订单数量</span>
          </div>
          <div class="card-panel-right">
            <svg-icon icon-class="right" class-name="card-panel-icon" />
          </div>
        </div>
      </div>
    </el-col>
    <el-col :xs="12" :sm="12" :md="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div @click="toCustomerList" class="link-type">
          <div class="card-panel-icon-wrapper icon-money" :title="panelData.being_approve_application">
            {{panelData.being_approve_application}}
          </div>
          <div class="card-panel-description">
            <div class="card-panel-text">待处理合作申请</div>
            <span class="card-panel-num">由门店发起的合作申请</span>
          </div>
          <div class="card-panel-right">
            <svg-icon icon-class="right" class-name="card-panel-icon" />
          </div>
        </div>
      </div>
    </el-col>
    <el-col :xs="12" :sm="12" :md="12" :lg="6" class="card-panel-col">
      <div class="card-panel">
        <div class="card-panel-icon-wrapper icon-shoppingCard" :title="panelData.today_commission_total">
          {{panelData.today_commission_total}}
        </div>
        <div class="card-panel-description">
          <div class="card-panel-text">今日签收金额（元）</div>
          <span class="card-panel-num">今日签收状态的订单总金额</span>
        </div>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  props: {
    panelData: {
      type: Object
    }
  },
  methods: {
    toCustomerList() {
      this.$router.push({ name: 'customerList', params: { status: 'wait' }})
      this.$store.dispatch('customerList', '首页')
    },
    toOrderList(status) {
      this.$router.push({ name: 'orderList', params: { status: status }})
      this.$store.dispatch('orderList', '首页')
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
.panel-group {
  /*margin-top: 18px;*/
  .card-panel-col{
    margin-bottom: 20px;
    &:last-child{
      .card-panel{
        background: #F39800;
        .card-panel-icon-wrapper{
          border-radius: unset;
          width: auto;
          max-width: 90px;
          min-width: 60px;
          *width: 90px;
          height: 46px;
          padding-right: 2px;
          padding-left: 2px;
          display: table;
          background: rgba(255, 255, 255, 0.8);
          color: #F39800;
          margin-top: 26px;
          line-height: 46px;
          /*overflow: hidden;*/
          /*text-overflow: ellipsis;*/
          /*white-space: nowrap;*/
        }
        .card-panel-num{
          color: #fff;
        }
        .card-panel-text{
          color: #fff;
        }
      }
    }
  }
  .card-panel {
    height: 100px;
    cursor: pointer;
    font-size: 12px;
    position: relative;
    overflow: hidden;
    color: #666;
    background: #fff;
    box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
    border: 1px solid #EBECEC;
    &:hover {
      /*border-color: #F5A623;*/
      box-shadow: 0px 0px 3px #F5A623;
      /*.card-panel-icon-wrapper {*/
        /*color: #fff;*/
      /*}*/
      /*.icon-people {*/
         /*background: #40c9c6;*/
      /*}*/
      /*.icon-message {*/
        /*background: #36a3f7;*/
      /*}*/
      /*.icon-money {*/
        /*background: #f4516c;*/
      /*}*/
      /*.icon-shoppingCard {*/
        /*background: #34bfa3*/
      /*}*/
    }
    .icon-people {
      color: #40c9c6;
    }
    .icon-message {
      color: #36a3f7;
    }
    .icon-money {
      color: #f4516c;
    }
    .icon-shoppingCard {
      color: #34bfa3
    }
    .card-panel-icon-wrapper {
      float: left;
      width: 54px;
      height: 54px;
      margin: 20px 8px 0 8px;
      transition: all 0.38s ease-out;
      border-radius: 54px;
      background: #F5A623;
      color: #fff;
      font-size: 24px;
      text-align: center;
      line-height: 54px;
    }
    .card-panel-icon {
      float: left;
      font-size: 48px;
    }
    .card-panel-description {
      float: left;
      font-weight: bold;
      margin: 26px 0;
      .card-panel-text {
        line-height: 18px;
        color: #333;
        font-size: 16px;
        margin-bottom: 10px;
      }
      .card-panel-num {
        font-size: 13px;
        color: #999;
      }
    }
    .card-panel-right{
      float: right;
      padding: 40px 6px;
      .card-panel-icon{
        font-size: 18px;
        color: #96918A;
      }
    }
  }
}
@media only screen and (min-width: 1200px){
  .el-col-lg-6 {
    .card-panel-icon-wrapper{
      max-width: 99px!important;
    }
  }
}
@media only screen and (min-width: 768px){
  .card-panel-icon-wrapper{
    max-width: 190px!important;
  }
}
</style>
