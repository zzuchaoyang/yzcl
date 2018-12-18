import request from '@supplier/utils/request'

// 金融列表
export function LoanPaginator(listQuery) {
  return request({
    method: 'post',
    data: {
      query: `
        query loanPaginator($paginator: PaginatorInput!) {
          loanPaginator(paginator: $paginator) {
            items {
              id
              supplier_id
              supplier{
                id
                name
                mobile
                avatar
              }
              period
              amount
              credential_info{
                yyzz
                sfz
                jhz
                yhkhxkz
                gszc
                mmzm
                grzxbg
                qyzxbg
                dlht
                lsscght
              }
            created_at
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

// 创建、修改贷款
export function StoreLoan(data) {
  /**
   * @description 移除不相关的提交字段
   *
   * @namespace data.jr_organization_name
   */
  // delete data.jr_organization_name

  return request({
    method: 'post',
    data: {
      query: `
        mutation storeLoan ($data: StoreLoanInput) {
          storeLoan(data: $data) {
            id
            supplier_id
            supplier{
              id
              name
            }
          }
        }
        `,
      variables: {
        data: data
      }
    }
  })
}

export function AreaList(ids) {
  return request({
    method: 'post',
    data: {
      query: `
        query areaList($ids: [Int]) {
          areaList(ids: $ids) {
            id
            name
            merger_name
            short_name
            pinyin
          }
        }
      `,
      variables: {
        ids: ids
      }
    }
  })
}
export function AreaList2(parent_id) {
  return request({
    method: 'post',
    data: {
      query: `
        query areaList($parent_id: Int) {
          areaList(parent_id: $parent_id) {
            id
            name
            merger_name
            short_name
            pinyin
          }
        }
      `,
      variables: {
        parent_id: parent_id
      }
    }
  })
}


/**
 * 创建、修改供应商基本信息
 *
 */
export function StoreSupplier(data) {
  return request({
    method: 'post',
    data: {
      query: `
      mutation storeSupplier($data: StoreSupplierMutationInput!) {
        storeSupplier(data: $data) {
          id
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
        }
      }
    `,
      variables: {
        data: {
          id: data.id,
          info: data.info
        }
      }
    }
  })
}

