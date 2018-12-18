<?php
/*
|--------------------------------------------------------------------------
| Graphql 接口定义
|--------------------------------------------------------------------------
|
| 请根据功能模块，自行定义数组，数组内需要有明确注释
| 查询的接口类一定要以 Query 结尾
| 更新的接口类一定要以 Mutation 结尾
| 不接受其他定义类型
|
| 键名不能重复，否则会报错的
|
| 规范开发，美好你我他
|
*/

use App\GraphQL\Mutations\Finance\Loan\StoreLoanMutation;
use App\GraphQL\Mutations\Product\Brand\DeleteBrandMutation;
use App\GraphQL\Mutations\Product\Brand\StoreBrandMutation;
use App\GraphQL\Mutations\Product\Product\BatchImportProductMutation;
use App\GraphQL\Mutations\Product\Product\DeleteProductMutation;
use App\GraphQL\Mutations\Product\Product\StoreProductMutation;
use App\GraphQL\Mutations\Product\Product\StoreProductPriceAdjustmentMutation;
use App\GraphQL\Queries\Finance\LoanPaginatorQuery;
use App\GraphQL\Queries\Product\BaseProductQuery;
use App\GraphQL\Queries\Product\BrandListQuery;
use App\GraphQL\Queries\Product\ProductPaginatorQuery;
use App\GraphQL\Queries\Product\ProductPriceAdjustmentQuery;

return array_merge([
    'options'       => \App\GraphQL\Queries\Common\OptionsQuery::class,
    'sendVerifySms' => \App\GraphQL\Mutations\Fx\SendVerifySmsMutation::class,
], [
    /*
     * System
     */
    // 地区搜索
    'areaList' => \App\GraphQL\Queries\System\AreaListQuery::class,
], [
    /*
     * 供应商登录、注册、登出
     */
    // 注册
    'supplierRegister'               => \App\GraphQL\Mutations\Supplier\Auth\SupplierRegisterMutation::class,
    // 登录
    'supplierLogin'                  => \App\GraphQL\Mutations\Supplier\Auth\SupplierLoginMutation::class,
    // 登出
    'supplierLogout'                 => \App\GraphQL\Mutations\Supplier\Auth\SupplierLogoutMutation::class,
    // 重置密码
    'supplierResetPassword'          => \App\GraphQL\Mutations\Supplier\Auth\SupplierResetPasswordMutation::class,
    // 用户信息
    'supplierInfo'                   => \App\GraphQL\Queries\Supplier\Auth\SupplierInfoQuery::class,
    //供应商列表
    'supplierList'                   => \App\GraphQL\Queries\Supplier\SupplierListQuery::class,
    //供应商操作合作关系
    'supplierActionApplication'      => \App\GraphQL\Mutations\Supplier\Application\SupplierActionApplicationMutation::class,
    //客户申请与供应商合作
    'supplierCooperationApplication' => \App\GraphQL\Mutations\Supplier\Application\SupplierCooperationApplicationMutation::class,
], [
    /*
     * 供应商 部门
     */
    // 创建/更新 - 供应商 部门
    'storeSupplierOrg' => \App\GraphQL\Mutations\Supplier\SupplierOrg\StoreSupplierOrgMutation::class,
    // 更新供应商基本信息
    'storeSupplier'        => \App\GraphQL\Mutations\Supplier\Supplier\StoreSupplierMutation::class,
    // 供应商 部门 列表
    'supplierOrgList'  => \App\GraphQL\Queries\Supplier\SupplierOrg\SupplierOrgListQuery::class,
], [
    /*
     * 供应商 司机
     */
    // 用户信息
    'driverInfo'          => \App\GraphQL\Queries\Supplier\Driver\Auth\DriverInfoQuery::class,
    // 供应商 司机 登录
    'driverLogin'         => \App\GraphQL\Mutations\Supplier\Driver\Auth\DriverLoginMutation::class,
    // 供应商 司机 登出
    'driverLogout'        => \App\GraphQL\Mutations\Supplier\Driver\Auth\DriverLogoutMutation::class,
    // 供应商 司机  重置密码
    'driverResetPassword' => \App\GraphQL\Mutations\Supplier\Driver\Auth\DriverResetPasswordMutation::class,
    // 供应商 司机 修改手机号
    'updateDriverMobile'  => \App\GraphQL\Mutations\Supplier\Driver\UpdateDriverMobileMutation::class,
    // 供应商 司机 修改密码
    'updateDriverPassword' => \App\GraphQL\Mutations\Supplier\Driver\UpdateDriverPasswordMutation::class,
    // 创建 供应商 司机
    'storeDriver'         => \App\GraphQL\Mutations\Supplier\Driver\StoreDriverMutation::class,
    // 创建 供应商 司机基本信息
    'updateDriver'        => \App\GraphQL\Mutations\Supplier\Driver\UpdateDriverMutation::class,
    // 司机 分页 列表
    'driverPaginator'     => \App\GraphQL\Queries\Supplier\Driver\DriverPaginatorQuery::class,
    // 司机 列表
    'driverList'          => \App\GraphQL\Queries\Supplier\Driver\DriverListQuery::class,
], [
    /*
     * 客户登录注册
     */
    'customerInfo'                   => \App\GraphQL\Queries\Customer\Auth\CustomerInfoQuery::class,
    // 注册
    'customerRegister'               => \App\GraphQL\Mutations\Customer\Auth\CustomerRegisterMutation::class,
    // 登录
    'customerLogin'                  => \App\GraphQL\Mutations\Customer\Auth\CustomerLoginMutation::class,
    // 登出
    'customerLogout'                 => \App\GraphQL\Mutations\Customer\Auth\CustomerLogoutMutation::class,
    // 重置密码
    'customerResetPassword'          => \App\GraphQL\Mutations\Customer\Auth\CustomerResetPasswordMutation::class,
    // 修改手机号
    'updateCustomerMobile'           => \App\GraphQL\Mutations\Customer\UpdateCustomerMobileMutation::class,
    // 修改密码
    'updateCustomerPassword'         => \App\GraphQL\Mutations\Customer\UpdateCustomerPasswordMutation::class,
    // 更新客户基本信息
    'updateCustomer'                 => \App\GraphQL\Mutations\Customer\UpdateCustomerMutation::class,
    // 客户列表
    'customerList'                   => \App\GraphQL\Queries\Customer\CustomerListQuery::class,
    //客户申请与供应商合作
    'customerCooperationApplication' => \App\GraphQL\Mutations\Customer\Application\CustomerCooperationApplicationMutation::class,
], [
    //订单列表查询
    'orderPaginator'        => \App\GraphQL\Queries\Order\OrderPaginatorQuery::class,
    //订单详情查询
    'orderProductPaginator' => \App\GraphQL\Queries\Order\OrderProductPaginatorQuery::class,
    //订单创建
    'storeOrder'            => \App\GraphQL\Mutations\Order\StoreOrderMutation::class,
    //门店和供应商订单的操作
    'handleOrder'           => \App\GraphQL\Mutations\Order\HandleOrderMutation::class,
    //司机操作订单
    'driverHandleOrder'     => \App\GraphQL\Mutations\Order\DriverHandleOrderMutation::class,
    //购物车储存
    'shoppingCart'          => \App\GraphQL\Mutations\Order\ShoppingCartMutation::class,
    //订单日志
    'orderLogPaginator'     => \App\GraphQL\Queries\Order\OrderLogPaginatorQuery::class,
], [
    // 创建、更新品牌
    'storeBrand'                     => StoreBrandMutation::class,
    //删除品牌
    'deleteBrand'                    => DeleteBrandMutation::class,
    //创建、更新商品
    'storeProduct'                   => StoreProductMutation::class,
    //批量导入商品
    'batchImportProduct'             => BatchImportProductMutation::class,
    //创建、更新商品调价单
    'storeProductPriceAdjustment'    => StoreProductPriceAdjustmentMutation::class,
    //删除商品
    'deleteProduct'                  => DeleteProductMutation::class,
    //查询品牌
    'brandList'                      => BrandListQuery::class,
    //商品列表
    'productPaginator'               => ProductPaginatorQuery::class,
    //商品基础信息表
    'baseProductQuery'               => BaseProductQuery::class,
    //调价单列表
    'productPriceAdjustmentPaginator'=> ProductPriceAdjustmentQuery::class,
], [
    // 贷款保存更新
    'storeLoan'     => StoreLoanMutation::class,
    // 贷款列表
    'loanPaginator'    => LoanPaginatorQuery::class,
], [
    //首页月业绩统计
    'homeCommissionStatics' => \App\GraphQL\Queries\Managenment\HomeCommissionStaticsQuery::class,
    //首页地图
    'homeMap' => \App\GraphQL\Queries\Managenment\HomeMapQuery::class,
], [
    //站内信列表
    'innerMessageList' => \App\GraphQL\Queries\Message\InnerMessageListQuery::class,
]);
