import request from '@supplier/utils/request'

export function fetchList(params) {
  return request({
    method: 'get',
    params
  })
}
