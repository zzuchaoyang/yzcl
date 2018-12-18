<template>
    <div class="menu-wrapper">
        <div style="height: 82px;background-color: #25201A;" v-if="!isCollapse">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAPIAAABQCAYAAAAjvChBAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQwIDc5LjE2MDQ1MSwgMjAxNy8wNS8wNi0wMTowODoyMSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkVENDM4MDc5RjFFQzExRThBMjc2QzJFMjczODU0MkQ4IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkVENDM4MDdBRjFFQzExRThBMjc2QzJFMjczODU0MkQ4Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RUQ0MzgwNzdGMUVDMTFFOEEyNzZDMkUyNzM4NTQyRDgiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RUQ0MzgwNzhGMUVDMTFFOEEyNzZDMkUyNzM4NTQyRDgiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6rrCzZAAAZaklEQVR42uxdB5hb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"
                 alt="" style="width: 121px; height: 37px; position: absolute; left: 26px; top: 23px;">
        </div>
        <template v-for="item in routes" v-if="!item.hidden&&item.children">
            <router-link v-if="hasOneShowingChildren(item.children) && !item.children[0].children&&!item.alwaysShow"
                         :to="item.path+'/'+item.children[0].path"
                         :key="item.children[0].name">
                <el-menu-item :index="item.path+'/'+item.children[0].path"
                              :class="{'submenu-title-noDropdown':!isNest}">
                    <svg-icon v-if="item.children[0].meta&&item.children[0].meta.icon"
                              :icon-class="item.children[0].meta.icon"></svg-icon>
                    <span v-if="item.children[0].meta&&item.children[0].meta.title" slot="title">{{(item.children[0].meta.title)}}</span>
                </el-menu-item>
            </router-link>

            <el-submenu v-else :index="item.name||item.path" :key="item.name">
                <template slot="title">
                    <svg-icon v-if="item.meta&&item.meta.icon" :icon-class="item.meta.icon"></svg-icon>
                    <span v-if="item.meta&&item.meta.title" slot="title">{{(item.meta.title)}}</span>
                </template>

                <template v-for="child in item.children" v-if="!child.hidden">
                    <sidebar-item :is-nest="true" class="nest-menu" v-if="child.children&&child.children.length>0"
                                  :routes="[child]" :key="child.path"></sidebar-item>

                    <router-link v-else :to="item.path+'/'+child.path" :key="child.name">
                        <el-menu-item :index="item.path+'/'+child.path">
                            <svg-icon v-if="child.meta&&child.meta.icon" :icon-class="child.meta.icon"></svg-icon>
                            <span v-if="child.meta&&child.meta.title" slot="title">{{(child.meta.title)}}</span>
                        </el-menu-item>
                    </router-link>
                </template>
            </el-submenu>

        </template>
    </div>
</template>

<script>
  import { mapGetters } from 'vuex'
  export default {
    name: 'SidebarItem',
    props: {
      routes: {
        type: Array
      },
      isNest: {
        type: Boolean,
        default: false
      }
    },
    computed: {
      ...mapGetters([
        'permission_routers',
        'sidebar'
      ]),
      isCollapse() {
        return !this.sidebar.opened
      }
    },
    methods: {
      hasOneShowingChildren(children) {
        const showingChildren = children.filter(item => {
          return !item.hidden
        })
        if (showingChildren.length === 1) {
          return true
        }
        return false
      }
    }
  }
</script>

