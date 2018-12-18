<template>
    <div>
        <!--品牌管理-->
        <div class="level-top-title level-title">
            <div class="level-title-left">
                {{ brandTitle }}
            </div>
            <div class="level-title-right" @click.stop="addBrandOne">
                <el-button type="text" @click.stop="addBrandOne('一级')">+添加一级{{ brandType }}</el-button>
            </div>
        </div>
        <div class="next-level-box" v-for="(itemOne, indexOne) in data" :key="'one' + indexOne">
            <!--1级品牌title展示-->
            <div class="level-top-title level-title level-title-one" @click="clickItem(itemOne)">
                <div class="level-title-left" v-if="brandType=='部门'">
                    {{itemOne.name}}
                </div>
                <div class="level-title-left" v-if="brandType!='部门'">
                    {{itemOne.label}}
                </div>
                <div class="level-title-right" @click.stop="addBrandOne">
                    <el-button type="text" @click.stop="addBrandOne('二级',itemOne)"> +添加二级{{ brandType }}</el-button>
                </div>
            </div>
            <!--1级品牌content展示-->
            <div class="level-content" v-if="itemOne.isShow">
                <div class="next-level-box" v-for="(itemTwo, indexTwo) in itemOne.children" :key="'two' + indexTwo" v-if="!itemOne.isEndLevel">
                    <!--2级品牌展示-->
                    <div class="level-top-title level-title level-title-two" @click="clickItem(itemTwo)">
                        <div class="level-title-left" v-if="brandType=='部门'">
                            {{itemTwo.name}}
                        </div>
                        <div class="level-title-left" v-if="brandType!='部门'">
                            {{itemTwo.label}}
                        </div>
                        <div class="level-title-right" @click.stop="addBrandOne">
                            <el-button type="text" @click.stop="addBrandOne('三级',itemTwo)"> +添加三级{{ brandType }}</el-button>
                        </div>
                    </div>

                    <div class="level-content" v-if="itemTwo.isShow">
                        <div class="next-level-box" v-for="(itemThree, indexThree) in itemTwo.children" :key="'three' + indexThree" v-if="!itemTwo.isEndLevel">
                            <!--3级品牌展示-->
                            <div class="level-top-title level-title level-title-three" @click="clickItem(itemThree)">
                                <div class="level-title-left" v-if="brandType=='部门'">
                                    {{itemThree.name}}
                                </div>
                                <div class="level-title-left" v-if="brandType!='部门'">
                                    {{itemThree.label}}
                                </div>
                                <div class="level-title-right" @click.stop="addBrandOne" v-if="brandType!='部门'">
                                    <el-button type="text" @click.stop="addBrandOne('四级',itemThree)"> +添加四级{{ brandType }}</el-button>
                                </div>
                            </div>

                            <div class="level-content" v-if="itemThree.isShow">
                                <!--4级品牌展示-->
                                <div :class="{'children-brand' : itemThree.children && itemThree.children.length}" >
                                    <el-tag v-for="(itemFour, indexFour) in itemThree.children" :key="'four' + indexFour" type="info">
                                        {{itemFour.label}}
                                    </el-tag>
                                </div>
                            </div>
                        </div>
                        <div :class="{'children-brand' : itemTwo.children && itemTwo.children.length}" v-else>
                            <el-tag v-for="(itemThree, indexThree) in itemTwo.children" :key="'three' + indexThree"  type="info">
                                {{itemTwo.label}}
                            </el-tag>
                        </div>
                    </div>

                </div>
                <!--此品牌是末级-->
                <div :class="{'children-brand' : itemOne.children && itemOne.children.length}" v-else>
                    <el-tag v-for="(itemTwo, indexTwo) in itemOne.children" :key="'two' + indexTwo" type="info">
                        {{itemTwo.label}}
                    </el-tag>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
  export default {
    name: 'treeFolding',
    data() {
      return {
      }
    },
    props: {
      data: Array,
      clickItem: {
        type: Function,
        default() {}
      },
      addBrandOne: {
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
      }
    },
    methods: {
    }
  }
</script>

<style lang="scss" scoped>
    .level-top-title {
        padding: 14px 0;
    .level-title-left {

    }
    .level-title-right {
    }
    }
    .level-title {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-left: -15px;
        margin-right: -15px;
        padding-left: 15px;
        padding-right: 15px;
        cursor: pointer;
    }
    .next-level-box{
        .el-tag--mini{
            margin: 3px;
        }
    }
    .level-title-one{
        background-color: rgba(59, 52, 44,.7);
        color: #fff;
    }
    .level-title-two{
        color: #F39800;
    }
    .level-title-three{

    }
    .children-brand{
        padding-bottom: 15px;
    }
</style>
