const getters = {
  sidebar: state => state.app.sidebar,
  device: state => state.app.device,
  tableHeight: state => state.app.rect.tableHeight,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,
  introduction: state => state.user.introduction,
  status: state => state.user.status,
  roles: state => state.user.roles,
  setting: state => state.user.setting,
  permission_routers: state => state.permission.routers,
  addRouters: state => state.permission.addRouters,
  errorLogs: state => state.errorLog.logs,
  size: state => state.app.size,
  priceAdjustment: state => state.app.priceAdjustment,
  orderList: state => state.app.orderList,
  customerList: state => state.app.customerList,
  viewCustomerDetail: state => state.app.viewCustomerDetail
}
export default getters
