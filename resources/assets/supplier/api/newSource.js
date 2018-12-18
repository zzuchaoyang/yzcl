import request from '@supplier/utils/request'
import axios from 'axios'

/**
 * 获取数据中心的楼盘列表
 * @param listQuery
 * @param more
 */
export function getNewCommunityPaginator(listQuery, more) {
  return request({
    method: 'post',
    data: {
      query: `
        query getNewCommunityPaginator($paginator: PaginatorInput!, $more: NewCommunityPaginatorInput) {
          newCommunityPaginator(paginator: $paginator, more: $more) {
            items {
              id
              name
              is_valid
              is_new
              erp_status
              is_web_show
              erp_open_status
              dc_at
              fxs_at
              erp_web_at
              deleted_at
              server {
                name
              }
              city {
                name
              }
              report_amount
              openCompanies {
                id
                name
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
        paginator: listQuery,
        more: {
          with_deleted: more && more.with_deleted,
          keywords: more && more.keywords
        }
      }
    }
  })
}

/**
 * 新增新楼盘
 *
 */
export function storeNewCommunity(communityId, serverId) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeNewCommunity($community_id: Int!, $server_id: Int!) {
          storeNewCommunity(community_id: $community_id, server_id: $server_id)
        }
      `,
      variables: {
        community_id: communityId,
        server_id: serverId
      }
    }
  })
}

/**
 * 网站展示
 *
 */
export function webShowNewCommunity(newCommunityId, field, value) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation SwitchStatusNewCommunity($new_community_id: Int!, $field: String!, $value: Boolean!) {
          switchStatusNewCommunity(new_community_id: $new_community_id, field: $field, value: $value)
        }
      `,
      variables: {
        new_community_id: newCommunityId,
        field: field,
        value: value
      }
    }
  })
}

/**
 * 合作公司
 *
 */
export function updateNewCommunityOpenCompanies(newCommunityId, serverIds) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation updateNewCommunityOpenCompanies($new_community_id: Int!, $server_ids: [Int]) {
          updateNewCommunityOpenCompanies(new_community_id: $new_community_id, server_ids: $server_ids)
        }
      `,
      variables: {
        new_community_id: newCommunityId,
        server_ids: serverIds
      }
    }
  })
}

/**
 * 同步楼盘
 *
 */
export function syncNewCommunityOpenCompanies(newCommunityId) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation syncNewCommunity($new_community_id: Int!) {
          syncNewCommunity(new_community_id: $new_community_id)
        }
      `,
      variables: {
        new_community_id: newCommunityId
      }
    }
  })
}

/**
 * 获取新楼盘列表
 * @param host
 * @returns {AxiosPromise<any>}
 */
export function getNewCommunitiesFromERP(host) {
  return axios.get(host + 'dc/new-community')
}

/**
 * 获取广告列表
 * @param listQuery
 * @returns {AxiosPromise<any>}
 */
export function getAdvertPaginator(listQuery) {
  return request({
    method: 'post',
    data: {
      query: `
        query getFxsAdvertPaginator($paginator: PaginatorInput!) {
          fxsAdvertPaginator(paginator: $paginator) {
            items {
              id
              name
              start_at
              end_at
              price
              type
              user_name
              more
              status
              sort
              size
              image_url
              new_community_id
              newCommunity {
                id
                name
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
        paginator: listQuery,
        more: {
          with_deleted: true
        }
      }
    }
  })
}

/**
 * 保存广告
 *
 */
export function storeFxsAdvert(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeFxsAdvert($data: StoreFxsAdvertMutationInput!) {
          storeFxsAdvert(data: $data) {
            id
            name
          }
        }
      `,
      variables: {
        data: {
          id: data.id,
          name: data.name,
          thumb_url: data.thumb_url,
          premise_id: data.premise_id,
          url: data.url,
          new_community_id: data.new_community_id,
          start_at: data.start_at,
          end_at: data.end_at,
          price: data.price,
          click_at: data.click_at,
          size: data.size,
          type: data.type,
          status: data.status,
          sort: data.sort,
          user_id: data.user_id,
          user_name: data.user_name,
          more: data.more,
          image_url: data.image_url,
          mobile_url: data.mobile_url
        }
      }
    }
  })
}

/**
 * 保存广告
 *
 */
export function deleteFxsAdvert(id) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation deleteFxsAdvert($id: ID!) {
          deleteFxsAdvert(id: $id)
        }
      `,
      variables: {
        id: id
      }
    }
  })
}
