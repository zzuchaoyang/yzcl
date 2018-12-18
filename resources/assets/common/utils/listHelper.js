export const listHelper = {
  /**
   * 把查询的结果集内容，对应的设置到列表参数里面
   * @param listHelper
   * @param query
   * @param paginator
   */
  setList(listHelper, query, paginator) {
    listHelper.items = paginator.items
    listHelper.total = paginator.cursor.total
    listHelper.listLoading = false
    query.limit = paginator.cursor.perPage
    query.page = paginator.cursor.currentPage
  },
  /**
   * 排序参数处理
   *
   * @param query
   * @param sort
   */
  setSort(query, sort) {
    if (!sort.prop || !sort.order) {
      query.sort = null
    } else {
      query.sort = sort.order === 'descending' ? '+' : '-'
      query.sort += sort.prop
    }
  }
}
