import request from '@supplier/utils/request'

export function getAgentPaginator(listQuery) {
  return request({
    method: 'post',
    data: {
      query: `
        query getAgentPaginator($paginator: PaginatorInput!) {
          agentPaginator(paginator: $paginator) {
            items {
              id
              eid
              server_id
              server {
                id
                name
                abbr
              }
              name
              status
              gender
              worked_at
              mobile
              education
              graduated_from
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

export function getServerPaginator(listQuery) {
  return request({
    method: 'post',
    data: {
      query: `
        query getServerPaginator($paginator: PaginatorInput!) {
          serverPaginator(paginator: $paginator) {
            items {
              id
              name
              abbr
              host
              api_host
              ip
              erp_status {
                version
                branch
                behind
                git_status
                ping
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
