<template>
  <div class="tree-level">
    <div class="level-top-title level-title">
      <div class="level-title-left">
        {{ brandTitle }}
      </div>
      <div class="level-title-right">
        <el-button type="text" @click.stop="addLevel(null, '一级')">+添加一级{{ brandType }}</el-button>
      </div>
    </div>
    <div class="tree-item"
         :class="{'item-one': item._level === 1, 'item-two': item._level === 2, 'last-level': item._level === 3, 'active': item._level === 3&&(clickIndex === index || expendLastTreeId === item.id)}"
         v-for="(item, index) in formatData"
         :style="showRow(item)"
         :key="item.id"
         @click.stop="toggleExpanded(index, item)">
        <div class="left-label">
          <span v-if="item._level === 1" class="tree-ctrl">
            <svg-icon icon-class="downarrow" class="down-arrow"/>
          </span>
          <span class="tree-ctrl text-overflow" :class="{'pdl10': item._level===1 }">{{ item.name }}</span>
        </div>
      <span class="item-rt" v-if="(item._level<3 && brandType!=='部门' && !item.is_last_stage && item.status === '正常')||(item._level<3 && brandType==='部门' && !item.is_tip && item.status)" @click.stop="addLevel(item, numArray[item._level] + '级')">+添加{{ numArray[item._level] }}级{{ brandType }}</span>
    </div>
  </div>
</template>

<script>
import treeToArray from '../../common/components/TreeTable/eval'
export default {
  props: {
    treeData: Array,
    // clickItem: {
    //   type: Function,
    //   default() {}
    // },
    addLevel: {
      type: Function,
      default() {}
    },
    brandTitle: {
      type: String,
      default: '品牌管理'
    },
    brandType: {
      type: String,
      default: '品牌'
    },
    expendLastTreeId: Number
  },
  data() {
    return {
      numArray: ['一', '二', '三', '四', '五'],
      clickIndex: -1
    }
  },
  computed: {
    // 格式化数据源
    formatData: function() {
      let tmp
      if (!Array.isArray(this.treeData)) {
        tmp = [this.treeData]
      } else {
        tmp = this.treeData
      }
      const func = this.evalFunc || treeToArray
      const args = this.evalArgs ? Array.concat([tmp, this.expandAll], this.evalArgs) : [tmp, this.expandAll]
      return func.apply(null, args)
    }
  },
  mounted() {
    this.showRow()
  },
  methods: {
    showRow: function(row) {
      if (row) {
        const show = (row.parent ? (row.parent._expanded && row.parent._show) : true)
        row._show = show
        return show ? 'animation:treeTableShow 1s;-webkit-animation:treeTableShow 1s;' : 'display:none;'
      }
    },
    // 切换下级是否展开
    toggleExpanded: function(index, item) {
      this.clickIndex = index
      this.$emit('expandGetItem', item)
      this.$emit('clickItem', item)
      if (item._level === 3) {
        return false
      }
      const record = this.formatData[index]
      record._expanded = !record._expanded
    },
    // 图标显示
    iconShow(record) {
      return (record.children && record.children.length > 0)
    }
  }
}

</script>
<style scoped lang="scss">
  .level-title {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    margin-left: -15px;
    margin-right: -15px;
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 10px;
    cursor: pointer;
    .level-title-left{
      /*padding-left: 22px;*/
    }
  }
  .tree-item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 10px 15px;
    margin: 0 -15px;
  }
  .item-one {
    background-color: rgba(59, 52, 44, 0.7);
    color: #fff;
    padding: 16px 15px;
  }
  .item-two {
    background-color: #fff;
    color: #F39800;
  }
  .item-rt {
    color: #F39800;
    font-size: 12px;
  }
  .last-level{
    display: inline-block;
    color: #999;
    font-size: 12px;
    border-radius: 4px;
    box-sizing: border-box;
    border: 1px solid rgba(144,147,153,.2);
    background-color: rgba(144,147,153,.1);
    padding: 4px 7px;
    margin: 4px 8px 4px 0;
  }
  .last-level.active{
    background: #F39800;
    color: #fff;
  }
  .down-arrow{
    font-size: 14px;
  }
  .pdl10{
    padding-left: 10px;
  }
  .level-title-right button{
    border: none;
  }
  .text-overflow{
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: inline-block;
    max-width: 150px;
  }
  .left-label{
    display: flex;
    align-items: center;
  }
</style>
