import request from '@supplier/utils/request'

// 商品统计报表
export function reportProduct(listQuery, listMore) {
  return request({
    method: 'post',
    data: {
      query: `
        query productPaginator($paginator: PaginatorInput!, $more: ProductInput) {
          productPaginator(paginator: $paginator, more: $more) {
            items {
              id,
              pictures,
              bar_code,
              name,
              brand_id,
              brand{
                id
                name
              },
              customer_count,
              order_number,
              order_price,
              real_order_number,
              real_order_price
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
        more: listMore
      }
    }
  })
}
// 客户销售统计

export function reportCustomer(listQuery, listMore) {
  return request({
    method: 'post',
    data: {
      query: `
        query customerList($paginator: PaginatorInput!, $more: CustomerPaginationInputQuery) {
          customerList(paginator: $paginator, more: $more) {
            items {
              id
              mobile
              store_info{
               name
              }           
              city
              store_info{
                area_id
              } 
              customerSupplier {
              customer_id
              cooperation_application_id
              cooperationApplication {
                id
                accepted_at
              }
            }
              order_count
              order_number
              order_price
              real_order_number
              real_order_price
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
        more: listMore
      }
    }
  })
}
