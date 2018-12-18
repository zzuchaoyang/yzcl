import Vue from 'vue'
import { Message, MessageBox } from 'element-ui'

/**
 * 操作信息提醒
 * @type {{success(*=): void, error(*=): void}}
 */
export const notify = {
  success(message) {
    Vue.prototype.$notify({
      title: '成功',
      message: message || '操作成功',
      type: 'success',
      duration: 2000
    })
  },
  warning(message) {
    Vue.prototype.$notify({
      title: '提示',
      message: message || '提醒',
      type: 'warning',
      duration: 5000
    })
  },
  error(message) {
    Vue.prototype.$notify({
      title: '失败',
      message: message || '操作失败',
      type: 'error',
      duration: 2000
    })
  }
}

/**
 * 系统消息提示
 * @type {{success(*=): void, error(*=): void}}
 */
export const message = {
  success(message) {
    Message({
      message: message || '成功',
      type: 'success',
      duration: 5 * 1000
    })
  },
  error(message) {
    Message({
      message: message || '出错误啦',
      type: 'error',
      duration: 5 * 1000
    })
  }
}

/**
 * 确认弹出框
 * @type {{confirm(*=, *=, *=, *=, *=): void, confirmDelete(*=, *=, *=, *=): void}}
 */
export const messageBox = {
  /**
   * 常规确认弹窗
   * @param message
   * @param messageTitle
   * @param confirmCallback
   * @param cancelCallback
   * @param finallyCallback
   */
  confirm(
    message, messageTitle, confirmCallback, cancelCallback, finallyCallback) {
    MessageBox.confirm(message, messageTitle, {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      type: 'warning'
    }).then(confirmCallback, cancelCallback).finally(finallyCallback)
  },
  /**
   * 常规提示弹窗仅有确定按钮
   * @param message
   * @param messageTitle
   * @param confirmCallback
   * @param cancelCallback
   * @param finallyCallback
   */
  selfConfirm(
    message, messageTitle, confirmCallback, cancelCallback, finallyCallback) {
    MessageBox.confirm(message, messageTitle, {
      confirmButtonText: '确定',
      type: 'warning',
      showCancelButton: false
    }).then(confirmCallback, cancelCallback).finally(finallyCallback)
  },
  /**
   * 确认删除弹窗
   * @param message
   * @param confirmCallback
   * @param cancelCallback
   * @param finallyCallback
   */
  confirmDelete(message, confirmCallback, cancelCallback, finallyCallback) {
    MessageBox.confirm(message, '删除操作', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      type: 'error'
    }).then(confirmCallback, cancelCallback).finally(finallyCallback)
  }
}

export function getUrlKey(name) {
  return decodeURIComponent(
    (new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(
      location.href) || [,''])[1].replace(/\+/g, '%20')) || null
}
