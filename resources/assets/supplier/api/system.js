import request from '@supplier/utils/request'

export function getServers() {
  return request({
    method: 'post',
    data: {
      query: `
        query getServers {
          servers {
            id
            name
            abbr
            host
          }
        }
      `
    }
  })
}

export function getUserPaginator(listQuery) {
  return request({
    method: 'post',
    data: {
      query: `
        query getUserPaginator($paginator: PaginatorInput!) {
          userPaginator(paginator: $paginator) {
            items {
              id
              name
              mobile
              avatar
              default_role_id
              role {
                id
                name
                alias
              }
            }
            cursor {
              total
              perPage
              currentPage
              hasPages
            }
          }
        }
      `,
      variables: {
        paginator: listQuery
      }
    }
  })
}
/**
 * 获取角色列表
 *
 */
export function getRoles() {
  return request({
    method: 'post',
    data: {
      query: `
        query roles{
          roles {
              id
              name
              alias
          }
        }
    `
    }
  })
}

/**
 * 保存用户
 *
 */
export function storeUser(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeUser($data: StoreUserMutationInputType!) {
          storeUser(data: $data) {
            id
            name
          }
        }
    `,
      variables: {
        data: {
          id: data.id,
          name: data.name,
          password: data.password,
          mobile: data.mobile,
          avatar: data.avatar,
          default_role_id: data.default_role_id
        }
      }
    }
  })
}

