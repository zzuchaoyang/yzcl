<template>
  <el-steps direction="vertical">
    <el-step icon="slot" class="steps-con"  v-for="(item,index) in data" :key="index">
      <div class="steps-icon" slot="icon">
        <svg-icon icon-class="record" class="svg-font"/>
      </div>
      <div class="record-con"  slot="title">
        <el-row>
          <el-col :span="24"><div class="grid-content bg-purple">{{item.user}}</div></el-col>
        </el-row>
      </div>
      <div class="record-con"  slot="description">
        <el-table
                :data="item.event_detail"
                border
                height="auto"
                :header-cell-style="{background:'#f1f1f1'}"
                style="width: 99%;max-height: 250px;overflow-y: auto">
          <el-table-column
                  prop="event"
                  label="属性"
                  width="180">
          </el-table-column>
          <el-table-column
                  prop="field_alias"
                  label="更改类型">
          </el-table-column>
          <el-table-column
                  prop="form"
                  label="值更改自"
                  width="180">
          </el-table-column>
          <el-table-column
                  prop="to"
                  label="值更改至">
          </el-table-column>
        </el-table>
      </div>
    </el-step>
  </el-steps>
</template>

<script>
/**
  Auth: SSP
  Created: 2018/11/20
*/
export default {
  name: 'journalSteps',
  props: {
    data: {
      type: Array,
      required: true
    }
  }
}
</script>

<style lang="scss" rel="stylesheet/scss" scoped>
  .steps-con{
    /deep/ .el-step__icon{
      border-radius: 60px;
      overflow: hidden;
    }
    /deep/ .el-step__main{
      margin-bottom: 20px;
    }
    .steps-icon{
      background: green;
      width: 50px;
      height: 55px;
      text-align: center;
      line-height: 55px;
    }
    .svg-icon{
      font-size: 14px;
      color: #fff;
    }
    &:last-of-type /deep/ .el-step__description,
    /deep/ .el-step__description{
      padding-right: unset;
    }
    /*/deep/ .el-step__description{*/
    /*padding-right: 0;*/
    /*}*/
    &:last-of-type /deep/ .el-step__line{
      display: block;
    }
  }
</style>
