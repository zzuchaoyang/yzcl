import request from '@supplier/utils/request'

// 验证码
export function SendSms(mobile) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation sendVerifySms($mobile: String!){
        sendVerifySms(mobile: $mobile)
      }   
      `,
      variables: {
        mobile: mobile
      }
    }
  })
}

// 供应商注册
export function SupplierRegister(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierRegister($mobile: String!,$password: String!,$code: String!,$invitation_code: String){
        supplierRegister(mobile: $mobile,password: $password,code: $code,invitation_code: $invitation_code)
      }   
      `,
      variables: {
        mobile: data.mobile,
        password: data.password,
        code: data.code,
        invitation_code: data.invitation_code
      }
    }
  })
}
// 门店注册
export function CustomerRegister(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation customerRegister($mobile: String!,$password: String!,$code: String!,$invitation_code: String){
        customerRegister(mobile: $mobile,password: $password,code: $code,invitation_code: $invitation_code)
      }   
      `,
      variables: {
        mobile: data.mobile,
        password: data.password,
        code: data.code,
        invitation_code: data.invitation_code
      }
    }
  })
}
// 供应商登录
export function login(mobile, password, remember_me) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierLogin($mobile: String!, $password: String!,$remember_me: Boolean) {
          login:supplierLogin(mobile: $mobile, password: $password,remember_me: $remember_me)
        }
      `,
      variables: {
        mobile: mobile,
        password: password,
        remember_me: remember_me
      }
    }
  })
}
// 供应商重置密码
export function SupplierResetPassword(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierResetPassword($mobile: String!, $password: String!,$code: String!) {
          supplierResetPassword(mobile: $mobile, password: $password,code: $code)
        }
      `,
      variables: {
        mobile: data.mobile,
        password: data.password,
        code: data.code
      }
    }
  })
}
// 门店登录
export function customerLogin(mobile, password, remember_me) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation customerLogin($mobile: String!, $password: String!, $remember_me: Boolean) {
          login:customerLogin(mobile: $mobile, password: $password, remember_me: $remember_me)
        }
      `,
      variables: {
        mobile: mobile,
        password: password,
        remember_me: remember_me
      }
    }
  })
}
// 门店重置密码
export function CustomerResetPassword(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation customerResetPassword($mobile: String!, $password: String!,$code: String!) {
          customerResetPassword(mobile: $mobile, password: $password,code: $code)
        }
      `,
      variables: {
        mobile: data.mobile,
        password: data.password,
        code: data.code
      }
    }
  })
}
// 供应商登出
export function logout(token) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierLogout($token: String!){
          supplierLogout(token: $token)
        }
      `,
      variables: {
        token: token
      }
    }
  })
}
export function getInfo(token) {
  return request({
    method: 'post',
    data: {
      query: `
        query supplierInfo {
          userInfo:supplierInfo {
            id
            name
            mobile
            avatar
            info{
              company{
                gsmc
                gsxz
                frdb
                frlxdh
                gscz
                gsyx
                ywlxr
                ywlxdh
                gsdz
                xxzs
                gsjs
                parent_ids
                area_id
              }
              finance{
                kpmc
                shxydm
                zcdz
                lxdh
                khyh
                yhzh
              }
              cards{
                name
                link
                created_at
              }
            }
            loan_info{
              min
              max
            }
          }
        }
      `
    }
  })
}
