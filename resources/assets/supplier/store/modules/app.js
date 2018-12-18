import Cookies from 'js-cookie'

const app = {
  state: {
    sidebar: {
      opened: !+Cookies.get('sidebarStatus'),
      withoutAnimation: false
    },
    device: 'desktop',
    language: Cookies.get('language') || 'zh',
    size: Cookies.get('size') || 'medium',
    rect: {},
    priceAdjustment: null,
    orderList: null,
    customerList: null,
    viewCustomerDetail: null
  },
  mutations: {
    TOGGLE_SIDEBAR: state => {
      if (state.sidebar.opened) {
        Cookies.set('sidebarStatus', 1)
      } else {
        Cookies.set('sidebarStatus', 0)
      }
      state.sidebar.opened = !state.sidebar.opened
      state.sidebar.withoutAnimation = false
    },
    CLOSE_SIDEBAR: (state, withoutAnimation) => {
      Cookies.set('sidebarStatus', 1)
      state.sidebar.opened = false
      state.sidebar.withoutAnimation = withoutAnimation
    },
    TOGGLE_DEVICE: (state, device) => {
      state.device = device
    },
    SET_LANGUAGE: (state, language) => {
      state.language = language
      Cookies.set('language', language)
    },
    SET_SIZE: (state, size) => {
      state.size = size
      Cookies.set('size', size)
    },
    SET_RECT: (state, rect) => {
      state.rect = {
        width: rect.width,
        height: rect.height,
        tableHeight: rect.height - 330
      }
    },
    PRICE_ADJUSTMENT: (state, from) => {
      state.priceAdjustment = from
    },
    ORDER_LIST: (state, from) => {
      state.orderList = from
    },
    CUSTOMER_LIST: (state, from) => {
      state.customerList = from
    },
    VIEW_CUSTOMER_DETAIL: (state, from) => {
      state.viewCustomerDetail = from
    }
  },
  actions: {
    toggleSideBar({ commit }) {
      commit('TOGGLE_SIDEBAR')
    },
    closeSideBar({ commit }, { withoutAnimation }) {
      commit('CLOSE_SIDEBAR', withoutAnimation)
    },
    toggleDevice({ commit }, device) {
      commit('TOGGLE_DEVICE', device)
    },
    setLanguage({ commit }, language) {
      commit('SET_LANGUAGE', language)
    },
    setSize({ commit }, size) {
      commit('SET_SIZE', size)
    },
    setRect({ commit }, rect) {
      commit('SET_RECT', rect)
    },
    priceAdjustment({ commit }, from) {
      commit('PRICE_ADJUSTMENT', from)
    },
    orderList({ commit }, from) {
      commit('ORDER_LIST', from)
    },
    customerList({ commit }, from) {
      commit('CUSTOMER_LIST', from)
    },
    viewCustomerDetail({ commit }, from) {
      commit('VIEW_CUSTOMER_DETAIL', from)
    }
  }
}

export default app
