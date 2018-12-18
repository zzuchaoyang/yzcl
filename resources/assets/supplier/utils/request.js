import axios from 'axios'
import { Message, MessageBox } from 'element-ui'
import store from '@supplier/store'
import { getToken } from '@common/utils/auth'

// 创建axios实例
const service = axios.create({
  baseURL: '/graphql', // api的base_url
  timeout: 15000 // 请求超时时间
})

const pending = []
const cancelToken = axios.CancelToken
const removePending = (config) => {
  for (let p in pending) {
    if (pending[p].u === config.url + '&' + config.method) { // 当当前请求在数组中存在时执行函数体
      pending[p].f() // 执行取消操作
      pending.splice(p, 1) // 把这条记录从数组中移除
    }
  }
}

// request拦截器
service.interceptors.request.use(config => {
  if (store.getters.token) {
    config.headers['authorization'] = 'Bearer ' + getToken() // 让每个请求携带登录token
  }
  removePending(config) // 在一个ajax发送前执行一下取消操作
  config.cancelToken = new cancelToken((c) => {
    // 这里的ajax标识我是用请求地址&请求方式拼接的字符串，当然你可以选择其他的一些方式
    pending.push({u: config.url + '&' + config.method, f: c})
  })
  return config
}, error => {
  // Do something with request error
  // console.log(error) // for debug
  Promise.reject(error)
})

// respone拦截器
service.interceptors.response.use(
  response => {
    removePending(response.config)  //  在一个ajax响应后再执行一下取消操作，把已经完成的请求从pending中移除
  /**
  * code为非20000是抛错 可结合自己业务进行修改
  */
    const res = response.data
    if (res.code >= 30000) {
      // Message({
      //   message: res.message ? res.message : '出错啦',
      //   type: 'error',
      //   duration: 5 * 1000
      // })

      // 50008:非法的token; 50012:其他客户端登录了;  50014:Token 过期了;
      if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
        MessageBox.confirm('你已被登出，可以取消继续留在该页面，或者重新登录', '确定登出', {
          confirmButtonText: '重新登录',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          store.dispatch('FedLogOut').then(() => {
            location.reload()// 为了重新实例化vue-router对象 避免bug
          })
        })
      }

      if (res.code >= 30000 && res.code < 40000) {
        if (res.errors[0].message !== 'validation') {
          const errorMessage = res.errors[0].message
          MessageBox.confirm(errorMessage, '哎呦', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            showCancelButton: false,
            type: 'warning'
          }).then(() => {
          }, () => {
            // location.reload()// 为了重新实例化vue-router对象 避免bug
          })
        } else {
          const errorMessage = _.concat(Object.values(res.errors[0].validation)).join(';')
          MessageBox.confirm(errorMessage, '哎呦', {
            confirmButtonText: '确定',
            cancelButtonText: '刷新',
            type: 'warning'
          }).then(() => {
          }, () => {
            location.reload()// 为了重新实例化vue-router对象 避免bug
          })
        }
      }

      return Promise.reject('error')
    } else {
      return response.data
    }

    // 我司通过返回错误码来确定错误提示
    // return response
  },
  error => {
    Message({
      message: error.message || '请求有点频繁了,请稍后',
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
