import request from '@supplier/utils/request'

export function graphql(params) {
  return request({
    method: 'post',
    params
  })
}
