/**
 * Bootstrap Table Chinese translation
 * Author: Zhixin Wen<wenzhixin2010@gmail.com>
 */
(function ($) {
  'use strict';

  $.fn.bootstrapTable.locales['zh-CN'] = {
    formatLoadingMessage: function () {
      var loadinghtml = '<div class="loading"><div class="loading-content" ><img class="fa" src="../../img/loading-spinning-bubbles.svg" alt="Loading" /><br /><span class="text">数据加载中…</span></div ></div >';
      return loadinghtml;
    },
    formatRecordsPerPage: function (pageNumber) {
      return '每页显示 ' + pageNumber + ' 条记录';
    },
    formatShowingRows: function (pageFrom, pageTo, totalRows) {
      return '显示第 ' + pageFrom + ' 到第 ' + pageTo + ' 条记录 ' + totalRows + ' 条记录 ';
    },
    formatSearch: function () {
      return '搜索...';
    },
    formatNoMatches: function () {
      return '没有找到匹配的记录';
    },
    formatPaginationSwitch: function () {
      return '隐藏/显示分页';
    },
    formatRefresh: function () {
      return '刷新';
    },
    formatToggle: function () {
      return '切换';
    },
    formatColumns: function () {
      return '列表';
    },
    formatExport: function () {
      return '导出数据';
    },
    formatAllRows: function () {
      return '全部';
    },
    formatClearFilters: function () {
      return '清空过滤';
    }
  };

  $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales['zh-CN']);

})(jQuery);