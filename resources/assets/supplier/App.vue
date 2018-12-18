<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
  export default{
    name: 'App'
  }
</script>
<style lang="scss">
  @import './styles/index.scss'; // 全局自定义的css样式
  .fn-tr{
    text-align: right;
  }
  .vue-treeselect__control{
    height: 30px!important;
  }
  .vue-treeselect__portal-target{
    top: 21px!important;
    z-index: 3005!important;
  }
  .vue-treeselect div, .vue-treeselect span{
    line-height: 29px;
  }
  .vue-treeselect__label{
    font-size: 12px;
    font-weight: 400;
  }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }
  input[type="number"]{
    -moz-appearance: textfield;
  }
  .brand-level{
    .el-input__suffix{
      display: none;
    }
    .el-input__inner{
      padding: 0 15px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      display: inline-block;
    }
  }

  .set-price-dialog{
    .el-input__suffix{
      display: block !important;
      color: #F39800;
    }
  }

  .price-adjustment-product{
    .el-input__suffix{
      line-height: 28px;
    }
  }

  div.is-required-class{
    &:after{
      content: '*';
      color: #f56c6c;
      padding-left: 3px;
    }
  }

</style>
