import Vue from 'vue'
import Router from 'vue-router'

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router)

/* Layout */
import Layout from '@supplier/views/layout/layout'

/** note: submenu only apppear when children.length>=1
 *   detail see  https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 **/

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirct in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']     will control the page roles (you can set multiple roles)
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar,
    noCache: true                if true ,the page will no be cached(default is false)
  }
 **/
export const constantRouterMap = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        component: () => import('@supplier/views/redirect/index')
      }
    ]
  },
  {
    path: '/login',
    component: () => import('@supplier/views/login/index'),
    hidden: true
  },
  {
    path: '/company',
    component: () => import('@supplier/views/personal/information'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@supplier/views/errorPage/404'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@supplier/views/errorPage/401'),
    hidden: true
  },
  {
    path: '',
    component: Layout,
    redirect: '/dashboard',
    name: '首页',
    hidden: false,
    children: [
      {
        path: 'dashboard',
        name: 'dashboard',
        component: () => import('@supplier/views/dashboard/index'),
        meta: {
          title: '首页',
          icon: 'shouye',
          noCache: true
        }
      }]
  },
  {
    path: '/print/:id(\\d+)',
    name: 'print',
    component: () => import('@supplier/views/product/printPriceAdjustmentBill'),
    hidden: true
  }
]

export default new Router({
  // mode: 'history', // 后端支持可开 目前 这个域名有很多其他的请求，不能开启
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

export const asyncRouterMap = [
  {
    path: '/order',
    component: Layout,
    redirect: '/order/list',
    name: 'Order',
    alwaysShow: true, // 一直显示根路由
    meta: { title: '订单', icon: 'dingdan2', roles: ['admin'] },
    children: [
      {
        path: 'list',
        name: 'orderList',
        component: () => import('@supplier/views/order/orderList'),
        meta: {
          title: '订单明细'
        }
      },
      {
        path: 'detail/:id(\\d+)',
        name: 'order.detail',
        component: () => import('@supplier/views/order/orderDetail'),
        hidden: true,
        meta: {
          title: '订单详情'
        }
      }
    ]
  },
  {
    path: '/product',
    component: Layout,
    redirect: '/product/input',
    name: 'Product',
    alwaysShow: true,
    meta: { title: '商品', icon: 'shangpin2', roles: ['admin'] },
    children: [
      {
        path: 'input',
        name: 'productInput',
        component: () => import('@supplier/views/product/productInput'),
        meta: {
          title: '商品录入'
        }
      },
      {
        path: 'query',
        name: 'product.query',
        component: () => import('@supplier/views/product/productQuery'),
        meta: {
          title: '商品查询'
        }
      },
      {
        path: 'price/adjustment',
        name: 'productPriceAdjustment',
        component: () => import('@supplier/views/product/productPriceAdjustment'),
        meta: {
          title: '商品调价'
        }
      },
      {
        path: 'creat/bill',
        name: 'productCreatBill',
        hidden: true,
        component: () => import('@supplier/views/product/createPriceAdjustmentBill'),
        meta: {
          title: '创建商品调价单'
        }
      },
      {
        path: 'view/bill/:id(\\d+)',
        name: 'productViewBill',
        hidden: true,
        component: () => import('@supplier/views/product/createPriceAdjustmentBill'),
        meta: {
          title: '查看商品调价单'
        }
      },
      {
        path: 'promotion',
        name: 'product.promotion',
        hidden: true,
        component: () => import('@supplier/views/product/promotionManagement'),
        meta: {
          title: '促销管理'
        }
      },
      {
        path: '/product/create/:id(\\d+)',
        name: 'productCreate',
        component: () => import('@supplier/views/product/productCreate'),
        meta: {
          title: '添加商品'
        },
        hidden: true
      },
      {
        path: '/product/edit/:id(\\d+)',
        name: 'productEdit',
        component: () => import('@supplier/views/product/productEdit'),
        meta: {
          title: '修改商品信息'
        },
        hidden: true
      },
      {
        path: '/product/detail/:id(\\d+)',
        name: 'product.detail',
        component: () => import('@supplier/views/product/productDetail'),
        meta: {
          title: '商品详情'
        },
        hidden: true
      }
    ]
  },
  {
    path: '/customer',
    component: Layout,
    redirect: '/customer/list',
    name: 'Customer',
    alwaysShow: true,
    meta: { title: '客户', icon: 'kehu', roles: ['admin'] },
    children: [
      {
        path: 'list',
        name: 'customerList',
        component: () => import('@supplier/views/customer/customerList'),
        meta: {
          title: '客户管理'
        }
      },
      {
        path: 'detail/:id(\\d+)',
        name: 'customer.detail',
        component: () => import('@supplier/views/customer/customerDetail'),
        hidden: true,
        meta: {
          title: '客户详情'
        }
      }
    ]
  },
  {
    path: '/person',
    component: Layout,
    redirect: '/person/salesman',
    name: 'Person',
    alwaysShow: true, // 一直显示根路由
    meta: { title: '人事', icon: 'renshi', roles: ['admin'] },
    children: [
      // {
      //   path: 'salesman',
      //   name: 'person.salesman',
      //   component: () => import('@supplier/views/person/salesman'),
      //   meta: {
      //     title: '业务员管理'
      //   }
      // },
      {
        path: 'driver',
        name: 'personDriver',
        component: () => import('@supplier/views/person/driver'),
        meta: {
          title: '司机管理'
        }
      }
    ]
  },
  {
    path: '/report',
    component: Layout,
    redirect: '/report/list',
    name: 'Report',
    meta: { title: '报表', icon: 'baobiao', roles: ['admin'] },
    children: [
      {
        path: 'product',
        name: 'reportProduct',
        component: () => import('@supplier/views/report/reportProduct'),
        meta: {
          title: '商品销售统计报表'
        }
      },
      {
        path: 'customer',
        name: 'reportCustomer',
        component: () => import('@supplier/views/report/reportCustomer'),
        meta: {
          title: '客户销售统计报表'
        }
      }
    ]
  },
  {
    path: '/personal',
    component: Layout,
    redirect: '/personal/company',
    name: 'Personal',
    meta: { title: '设置', icon: 'shezhi', roles: ['admin'] },
    children: [
      {
        path: 'company',
        name: 'informationCompany',
        component: () => import('@supplier/views/personal/information'),
        meta: {
          title: '基本信息'
        }
      },
      {
        path: 'finance',
        name: 'personal.finance',
        component: () => import('@supplier/views/personal/finance'),
        meta: {
          title: '金融贷款'
        }
      }
    ]
  },
  { path: '*', redirect: '/404', hidden: true }
]
