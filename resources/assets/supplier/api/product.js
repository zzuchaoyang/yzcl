import request from '@supplier/utils/request'

// 商品列表字段
const productFields = `
id,
pictures,
bar_code,
name,
brand_id,
brand{
  id
  name
}
manufacturer,
order_unit,
spec_unit,
spec_number
single_num,
trade_price,
one_price,
status
`
// 商品列表
export function productPaginatorList(listQuery, listMore) {
  return request({
    method: 'post',
    data: {
      query: `
        query productPaginator($paginator: PaginatorInput!, $more: ProductInput) {
          productPaginator(paginator: $paginator, more: $more) {
            items {
        ` + productFields + `
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
// 商品详情
export function productPaginatorDetail(listQuery, listMore) {
  return request({
    method: 'post',
    data: {
      query: `
        query productPaginator($paginator: PaginatorInput!, $more: ProductInput) {
          productPaginator(paginator: $paginator, more: $more) {
            items {
            supplier_id
            make_place,
            manufacturer,
            unit,
            retail_price,
            quality_period,
            single_num,
            two_price,
            three_price,
            four_price,
            five_price,
            check_pictures,
        ` + productFields + `
            }
            cursor {
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

// 创建、编辑商品
export function storeProduct(data) {
  return request({
    method: 'post',
    data: {
      query: `
      mutation storeProduct($data: ProductInput) {
        storeProduct(data: $data) {
          id    
        }
      }
    `,
      variables: {
        data: data
      }
    }
  })
}

// 创建/修改品牌
export function storeBrand(data) {
  return request({
    method: 'POST',
    data: {
      query: `mutation storeBrand($data: BrandInput) {
                storeBrand(data: $data) {
                  id
                  name
                  logo_pic
                  parent_brand{
                    id
                    name
                  }
                  level
                  manufacturer
                  quantity
                  is_last_stage
                  status
                  price_increase_ratio{
                    one_increase_ratio
                    two_increase_ratio
                    three_increase_ratio
                    four_increase_ratio
                    five_increase_ratio
                  }
                }
              }`,
      variables: {
        data: data
      }
    }
  })
}

// 品牌列表
export function brandList(more) {
  return request({
    method: 'POST',
    data: {
      query: `query brandList($more:  BrandInput) {
                brandList(more: $more) {
                  id
                  supplier_id
                  name
                  logo_pic
                  parent_brand{
                    id
                    name
                  }
                  level
                  manufacturer
                  quantity
                  is_last_stage
                  status
                  price_increase_ratio{
                    one_increase_ratio
                    two_increase_ratio
                    three_increase_ratio
                    four_increase_ratio
                    five_increase_ratio
                  }
                }
              }`,
      variables: {
        more: more
      }
    }
  })
}

// 创建商品调价单
export function storeProductPriceAdjustment(data) {
  return request({
    method: 'POST',
    data: {
      query: `mutation storeProductPriceAdjustment($data: ProductPriceAdjustmentInput) {
                storeProductPriceAdjustment(data: $data) {
                  id
                  code
                }
              }
              `,
      variables: {
        data: data
      }
    }
  })
}

// 商品调价单列表
export function productPriceAdjustmentPaginator(paginator, more) {
  return request({
    method: 'POST',
    data: {
      query: `query productPriceAdjustmentPaginator($paginator: PaginatorInput!, $more: ProductPriceAdjustmentInput!) {
                productPriceAdjustmentPaginator(paginator: $paginator, more: $more) {
                  items {
                    id
                    code
                    created_at
                    effective_at
                    number
                    status
                    products {
                      id
                      pictures
                      bar_code
                      name
                      brand{
                        id
                        name
                      }
                      brand_id
                      manufacturer
                      order_unit
                      spec_unit
                      spec_number
                      single_num
                      trade_price
                      one_price
                      two_price
                      three_price
                      four_price
                      five_price
                      retail_price
                      status
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
        paginator: paginator,
        more: more
      }
    }
  })
}
// 商品基础表查询
export function baseProduct(more) {
  return request({
    method: 'POST',
    data: {
      query: `query baseProductQuery($more: BaseProductInput) {
                baseProductQuery( more: $more) {
                  id
                  name
                  bar_code
                  unit
                  retail_price
                  product_category_code
                }
              }
              `,
      variables: {
        more: more
      }
    }
  })
}
// 批量导入商品
export function batchImport(data) {
  return request({
    method: 'POST',
    data: {
      query: `mutation batchImportProduct($data: [ProductInput]) {
                batchImportProduct(data: $data) {
                  code
                  format
                }
              }
              `,
      variables: {
        data: data
      }
    }
  })
}

