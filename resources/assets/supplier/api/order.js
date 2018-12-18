import request from '@supplier/utils/request'

// 订单列表查询
export function GetOrderLists(paginator, more) {
  return request({
    method: 'post',
    data: {
      query: `
        query orderPaginator($paginator: PaginatorInput!, $more: orderPaginatorInput!) {
  orderPaginator(paginator: $paginator, more: $more) {
    items {
      id
      customer{
        id
        name
        mobile
        store_info{
          name
          address
        }
      }
      order_number
      price
      number
      send_price
      send_number
      customer_id
      order_at
      status
      status_alias
      can_force_signed
      send_at
    }
    cursor{
      total
      perPage
      currentPage
      hasPages
    }
  }
}
      `,
      variables: {
        paginator: paginator,
        more: more
      }
    }
  })
}

// 订单详情查询

export function GetOrderDetail(paginator, more) {
  return request({
    method: 'post',
    data: {
      query: `
        query orderProductPaginator($paginator: PaginatorInput!, $more: OrderProductPaginatorInput!) {
  orderProductPaginator(paginator: $paginator, more: $more) {
    items {
      id
      order_id
      product_id
      product_number
      product_price
      product_total_price
      send_number
      send_price
      send_total_price
      product{
        id
        name
        bar_code
        order_unit
        spec_number
        spec_unit
        unit
        single_num
        product_specifications
        brand_id
        brand{
          id
          name
        }
        pictures
      }
    }
    cursor{
      total
      perPage
      currentPage
      hasPages
    }
  }
}
      `,
      variables: {
        paginator: paginator,
        more: more
      }
    }
  })
}

// 修改订单商品数据
export function storeOrder(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeOrder($data: StoreOrderMutationInput!) {
  storeOrder(data: $data) {
    id
    supplier_id
  }
}
      `,
      variables: {
        data: data
      }
    }
  })
}

// 订单操作
export function handleOrder(id, transition, car_number, driver_id) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation handleOrder($id: Int!, $transition:String, $car_number:String,$driver_id: Int) {
  handleOrder(id: $id, transition:$transition,car_number:$car_number,driver_id:$driver_id)
}
      `,
      variables: {
        id: id,
        transition: transition,
        car_number: car_number,
        driver_id: driver_id
      }
    }
  })
}

// 订单跟踪
export function getOrderLog(paginator, order_id) {
  return request({
    method: 'post',
    data: {
      query: `
        query orderLogPaginator($paginator: PaginatorInput!, $order_id: Int) {
  orderLogPaginator(paginator: $paginator, order_id: $order_id) {
    items{
      id
      order_id
      content
      created_at
    }
  }
}
      `,
      variables: {
        paginator: paginator,
        order_id: order_id
      }
    }
  })
}
