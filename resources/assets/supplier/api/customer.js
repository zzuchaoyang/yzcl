import request from '@supplier/utils/request'

// 客户查询
export function GetCustomerLists(paginator, more) {
  return request({
    method: 'post',
    data: {
      query: `
        query customerList($paginator: PaginatorInput!, $more:  CustomerPaginationInputQuery!) {
  customerList(paginator: $paginator, more: $more) {
    items {
      id
      name
      mobile
      avatar
      history_commission
      city
      customerSupplier {
        customer_id
        supplier_id
        cooperation_application_id
        status_alias
        status
        supply_grade
        created_at
        cooperationApplication{
          id
          rejected_at
          accepted_at
          sender_type
        }
      }
      store_info{
        name
        address
        consignee
        mobile
        area_id
        year_turnover
        usable_area
        opened_at
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

// 申请合作
export function SupplierCooperation(customer_id, supplier_id, supply_grade) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierCooperationApplication($customer_id: Int!, $supplier_id: Int!, $supply_grade: String!) {
  supplierCooperationApplication(customer_id: $customer_id, supplier_id: $supplier_id, supply_grade: $supply_grade) {
    supplier_id
    status
  }
}
      `,
      variables: {
        customer_id: customer_id,
        supplier_id: supplier_id,
        supply_grade: supply_grade
      }
    }
  })
}

// 客户状态
export function SupplierActionApplication(action, id, supply_grade) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation supplierActionApplication($action:String!,$cooperation_application_id: Int!, $supply_grade: String) {
  supplierActionApplication(action:$action,cooperation_application_id: $cooperation_application_id,supply_grade: $supply_grade) {
    id
  }
}
      `,
      variables: {
        cooperation_application_id: id,
        action: action,
        supply_grade: supply_grade
      }
    }
  })
}

