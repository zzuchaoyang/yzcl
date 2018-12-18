<template>
  <el-menu class="navbar" mode="horizontal">
    <hamburger class="hamburger-container" :toggleClick="toggleSideBar" :isActive="sidebar.opened"></hamburger>

    <breadcrumb class="breadcrumb-container"></breadcrumb>

    <div class="right-menu">
      <error-log class="errLog-container right-menu-item"></error-log>

      <el-tooltip effect="dark" content="全屏" placement="bottom">
        <screenfull class="screenfull right-menu-item"></screenfull>
      </el-tooltip>
      <el-tooltip content="布局" effect="dark" placement="bottom">
        <size-select class="screenfull right-menu-item"/>
      </el-tooltip>
      <!--<el-tooltip content="帮助" effect="dark" placement="bottom">-->
        <!--<div class="right-menu-item">-->
          <!--<svg-icon icon-class="help" class-name="card-panel-icon" />-->
        <!--</div>-->
      <!--</el-tooltip>-->
      <!--<el-tooltip content="消息" effect="dark" placement="bottom">-->
        <!--<div class="right-menu-item">-->
          <!--<el-badge is-dot class="item">-->
            <!--<svg-icon icon-class="news" class-name="card-panel-icon" />-->
          <!--</el-badge>-->
        <!--</div>-->
      <!--</el-tooltip>-->
      <!--<el-tooltip content="消息" effect="dark" placement="bottom">-->
        <!--<div>-->
          <!--<svg-icon icon-class="user" class-name="card-panel-icon" />-->
        <!--</div>-->
      <!--</el-tooltip>-->

      <!--<el-tooltip effect="dark" content="换肤" placement="bottom">-->
        <!--<theme-picker class="theme-switch right-menu-item"></theme-picker>-->
      <!--</el-tooltip>-->

      <el-dropdown class="avatar-container right-menu-item" trigger="click">
        <div class="avatar-wrapper">
          <svg-icon icon-class="user" class-name="card-panel-icon" />
          <span>{{name}}</span>
          <!--<img class="user-avatar" :src="avatar+'?imageView2/1/w/80/h/80'">-->
          <!--<i class="el-icon-caret-bottom"></i>-->
        </div>
        <el-dropdown-menu slot="dropdown">
          <router-link to="/personal/company">
            <el-dropdown-item>
              基本信息
            </el-dropdown-item>
          </router-link>
          <el-dropdown-item divided>
            <span @click="logout" style="display:block;">退出登录</span>
          </el-dropdown-item>
        </el-dropdown-menu>
      </el-dropdown>
    </div>
  </el-menu>
</template>

<script>
  import { mapGetters } from 'vuex'
  import Breadcrumb from '@common/components/Breadcrumb'
  import Hamburger from '@common/components/Hamburger'
  import ErrorLog from '@common/components/ErrorLog'
  import Screenfull from '@common/components/Screenfull'
  import SizeSelect from '@common/components/SizeSelect'
  import LangSelect from '@common/components/LangSelect'
  import ThemePicker from '@common/components/ThemePicker'

  export default {
    components: {
      Breadcrumb,
      Hamburger,
      ErrorLog,
      Screenfull,
      SizeSelect,
      LangSelect,
      ThemePicker
    },
    computed: {
      ...mapGetters([
        'sidebar',
        'name',
        'avatar'
      ])
    },
    methods: {
      toggleSideBar() {
        this.$store.dispatch('toggleSideBar')
      },
      logout() {
        this.$store.dispatch('LogOut').then(() => {
          location.reload()// In order to re-instantiate the vue-router object to avoid bugs
        })
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
  .navbar {
    height: 50px;
    /*line-height: 50px;*/
    border-radius: 0 !important;
    .hamburger-container {
      line-height: 58px;
      height: 50px;
      float: left;
      padding: 0 10px;
    }
    .breadcrumb-container{
      float: left;
    }
    .errLog-container {
      display: inline-block;
      vertical-align: top;
    }
    .right-menu {
      float: right;
      height: 100%;
      padding-top: 15px;
      &:focus{
        outline: none;
      }
      .right-menu-item {
        display: inline-block;
        margin: 0 8px;
        vertical-align: middle;
      }
      .screenfull {
        height: 20px;
      }
      .international{
        vertical-align: top;
      }
      .card-panel-icon{
        font-size: 18px;
        cursor: pointer;
      }
      .theme-switch {
        vertical-align: 15px;
      }
      .avatar-container {
        /*height: 50px;*/
        margin-right: 30px;
        .avatar-wrapper {
          cursor: pointer;
          /*margin-top: 5px;*/
          position: relative;
          .card-panel-icon{
            font-size: 24px;
            vertical-align: middle;
          }
          .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
          }
          .el-icon-caret-bottom {
            position: absolute;
            right: -20px;
            top: 25px;
            font-size: 12px;
          }
        }
      }
    }
  }
</style>
