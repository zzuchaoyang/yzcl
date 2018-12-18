import request from '@supplier/utils/request'

export function getOptions(category, type) {
  return request({
    method: 'post',
    data: {
      query: `
        query options($category: String, $type: String) {
          options(category: $category, type: $type) {
            name
            value
          }
        }
      `,
      variables: {
        category: category,
        type: type
      }
    }
  })
}
// 首页
export function HomeCommissionStatics(search) {
  return request({
    method: 'post',
    data: {
      query: `
       query homeMap($customer_name: String) {
          homeMap(customer_name: $customer_name) {
            type
            name
            latitude
            longitude
          }
          homeCommissionStatics {
            current_month {
              day
              num
            }
            last_month {
              day
              num
            }
            being_approve_order
            being_send_order
            being_approve_application
            today_commission_total
            today_order_info {
              count
              commission
              ratio
              is_grow
            }
            last_order_info {
              count
              commission
              ratio
              is_grow
            }
            month_order_info {
              count
              commission
              ratio
              is_grow
            }
            last_month_order_info {
              count
              commission
              ratio
              is_grow
            }
          }
        }`,
      variables: {
        customer_name: search.customer_name
      }
    }
  })
}
// 获取区域列表
export function areaList(searchMore) {
  return request({
    method: 'post',
    data: {
      query: `
        query areaList($name: String, $parent_id: Int, $level: Int, $offset: Int, $limit: Int ) {
          areaList(name: $name, parent_id: $parent_id, level: $level, offset: $offset, limit: $limit) {
            id
            name
            merger_name
            short_name
            pinyin
          }
        }
      `,
      variables: searchMore
    }
  })
}
