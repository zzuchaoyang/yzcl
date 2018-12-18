import request from '@supplier/utils/request'

// 组织架构添加
export function AddOrg(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeSupplierOrg($data: StoreSupplierOrgMutationInput!){
      storeSupplierOrg(data: $data) {
          id
          rank
          type
          category
          name
          status
          rank
          parent_id
          is_tip
          id_card
          mobile
          children{
            id
            rank
            type
            category
            name
            status
            rank
            parent_id
            is_tip
            id_card
            mobile
            children{
              id
              rank
              type
              category
              name
              status
              rank
              parent_id
              is_tip
              id_card
              mobile
              children{
                id
                rank
                type
                category
                name
                status
                rank
                parent_id
                is_tip
                id_card
                mobile
              }
            }
          }
        }
      } 
      `,
      variables: {
        data: {
          id: data.id,
          rank: data.rank,
          type: data.type,
          name: data.name,
          is_tip: data.is_tip,
          status: data.status,
          parent_id: data.parent_id,
          category: data.category,
          mobile: data.mobile,
          id_card: data.id_card
        }
      }
    }
  })
}

// 组织架构列表
export function GetOrgList(is_inline, category) {
  return request({
    method: 'post',
    data: {
      query: `
        query supplierOrgList($is_inline: Boolean, $category: String) {
  supplierOrgList(is_inline: $is_inline, category: $category) {
    id
    name
    rank
    parent_id
    is_tip
    status
    type
    mobile
    id_card
    children {
      id
      name
      rank
      parent_id
      is_tip
      status
      type
      mobile
      id_card
      parent{
        id
        name
      }
      children {
        id
        name
        rank
        parent_id
        is_tip
        status
        type
        mobile
        id_card
        parent{
          id
          name
          parent{
            id
            name
          }
        }
        children {
          id
          name
          rank
          parent_id
          is_tip
          status
          type
          mobile
          id_card
          parent{
            id
            name
            parent{
              id
              name
              parent{
                id
                name
              }
            }
          }
        }
      }
    }
  }
}
      `,
      variables: {
        is_inline: is_inline,
        category: category
      }
    }
  })
}

// 司机添加
export function AddDriver(data) {
  return request({
    method: 'post',
    data: {
      query: `
        mutation storeDriver($data: StoreDriverMutationInput!) {
      storeDriver(data: $data) {
        id
        name
        mobile
        status
        id_card
        license
      }
    }
      `,
      variables: {
        data: data
      }
    }
  })
}

// 司机列表
export function GetDriverList(paginator, more) {
  return request({
    method: 'post',
    data: {
      query: `
        query driverPaginator($paginator: PaginatorInput!, $more: DriverPaginatorInput) {
      driverPaginator(paginator: $paginator, more: $more) {
        items {
          id
          name
          supplier_org_id
          id_card
          mobile
          license
          hiredate_on
          status
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
        paginator: paginator,
        more: more
      }
    }
  })
}

// 司机列表 不分页
export function DriverList(more) {
  return request({
    method: 'post',
    data: {
      query: `
        query driverList($more: DriverPaginatorInput) {
  driverList(more: $more) {
    id
    name
    supplier_org_id
    id_card
    mobile
    license
    hiredate_on
    status
  }
}
      `,
      variables: {
        more: more
      }
    }
  })
}
