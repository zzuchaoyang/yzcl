<?php

use App\GraphQL\Types\Common\OptionType;
use App\GraphQL\Types\PaginatorInputType;
use App\GraphQL\Types\Product\Brand\BrandPriceIncreaseRatioType;
use App\GraphQL\Types\Product\Brand\BrandType;
use App\GraphQL\Types\Product\Input\BaseProductInput;
use App\GraphQL\Types\Product\Input\BrandInput;
use App\GraphQL\Types\Product\Input\BrandPriceIncreaseRatioTypeInput;
use App\GraphQL\Types\Product\Input\ProductInput;
use App\GraphQL\Types\Product\Input\ProductPriceAdjustmentInput;
use App\GraphQL\Types\Product\Product\BaseProductType;
use App\GraphQL\Types\Product\Product\BatchImportProductType;
use App\GraphQL\Types\Product\Product\ProductType;
use App\GraphQL\Types\Product\ProductPriceAdjustment\ProductPriceAdjustmentType;
use App\Models\Product\BaseProduct;

return array_merge([
    'PaginatorInput' => PaginatorInputType::class,
    'Option'         => OptionType::class,
], [
    /*
     * System
     */

    // 地区
    'Area' => \App\GraphQL\Types\System\AreaType::class,
], array(
    //供应商信息
    'Supplier'                              => \App\GraphQL\Types\Supplier\Supplier\SupplierType::class,
    //供应商 - 贷款信息
    'SupplierLoanInfo'                      => \App\GraphQL\Types\Supplier\Supplier\SupplierLoanInfoType::class,
    //供应商 - 基本信息
    'SupplierInfo'                          => \App\GraphQL\Types\Supplier\Supplier\SupplierInfoType::class,
    'SupplierCompanyInfo'                   => \App\GraphQL\Types\Supplier\Supplier\SupplierCompanyInfoType::class,
    'SupplierFinanceInfo'                   => \App\GraphQL\Types\Supplier\Supplier\SupplierFinanceInfoType::class,
    'SupplierCardInfo'                      => \App\GraphQL\Types\Supplier\Supplier\SupplierCardInfoType::class,

    //供应商 - 保存基本信息
    'StoreSupplierMutationInput'                             => \App\GraphQL\Types\Supplier\Supplier\Input\StoreSupplierMutationInputType::class,
    'StoreSupplierMutationInfoInput'                         => \App\GraphQL\Types\Supplier\Supplier\Input\StoreSupplierMutationInfoInputType::class,
    'StoreSupplierMutationInfoCompanyInput'                  => \App\GraphQL\Types\Supplier\Supplier\Input\StoreSupplierMutationInfoCompanyInputType::class,
    'StoreSupplierMutationInfoFinanceInput'                  => \App\GraphQL\Types\Supplier\Supplier\Input\StoreSupplierMutationInfoFinanceInputType::class,
    'StoreSupplierMutationInfoCardInput'                     => \App\GraphQL\Types\Supplier\Supplier\Input\StoreSupplierMutationInfoCardInputType::class,
    //供应商列表请求字段
    'SupplierInputQuery'                    => \App\GraphQL\Types\Supplier\Supplier\Input\SupplierInputQuery::class,
    //供应商 - 部门输入字段类型
    'StoreSupplierOrgMutationInput'         => \App\GraphQL\Types\Supplier\SupplierOrg\Input\StoreSupplierOrgMutationInputType::class,
    //供应商 - 部门输出字段类型
    'SupplierOrg'                           => \App\GraphQL\Types\Supplier\SupplierOrg\SupplierOrgType::class,
    //供应商 - 司机输出字段类型
    'Driver'                                => \App\GraphQL\Types\Supplier\Driver\DriverType::class,
    //供应商 - 创建司机输入字段类型
    'StoreDriverMutationInput'              => \App\GraphQL\Types\Supplier\Driver\Input\StoreDriverMutationInputType::class,
    //供应商 - 司机列表输入字段类型
    'DriverPaginatorInput'                  => \App\GraphQL\Types\Supplier\Driver\Input\DriverPaginatorInputType::class,
), [
    //订单信息
    'Order'                               => \App\GraphQL\Types\Order\Order\OrderType::class,
    //订单接口请求字段
    'orderPaginatorInput'                 => \App\GraphQL\Types\Order\Input\OrderInputQuery::class,
    //订单商品详情
    'OrderProduct'                        => \App\GraphQL\Types\Order\OrderProduct\OrderProductType::class,
    //商品详情请求字段
    'OrderProductPaginatorInput'          => \App\GraphQL\Types\Order\Input\OrderProductPaginatorInput::class,
    //订单创建请求字段
    'StoreOrderMutationOrderProductInput' => \App\GraphQL\Types\Order\Input\StoreOrderMutationOrderProductInputType::class,
    //订单创建请求商品详情字段
    'StoreOrderMutationInput'             => \App\GraphQL\Types\Order\Input\StoreOrderMutationInputType::class,
    //司机操作订单请求参数
    'DriverHandleOrderMutationInput'      => \App\GraphQL\Types\Order\Input\DriverHandleOrderMutationInputType::class,
    //订单位置字段
    'PositionInfo'                        => \App\GraphQL\Types\Order\Order\PositionInfoType::class,
    //位置坐标
    'CoordinateInfo'                      => \App\GraphQL\Types\Order\Order\CoordinateInfoType::class,
    //购物车储存请求
    'ShoppingCardMutationInput'           => \App\GraphQL\Types\Order\Input\ShoppingCardMutationInputType::class,
    //购物车信息
    'ShoppingCard'                        => \App\GraphQL\Types\Order\Order\ShoppingCardType::class,
    //订单日志
    'OrderLog'                            => \App\GraphQL\Types\Order\OrderLog\OrderLogType::class,
], [
    // 客户信息
    'Customer'                     => \App\GraphQL\Types\Customer\Customer\CustomerType::class,
    // 客户店铺信息
    'CustomerStoreInfo'            => \App\GraphQL\Types\Customer\Customer\CustomerStoreInfoType::class,
    // 更新 客户信息输入字段类型
    'UpdateCustomerInput'          => \App\GraphQL\Types\Customer\Input\UpdateCustomerInputType::class,
    // 更新 客户店铺信息
    'UpdateCustomerStoreInfoInput' => \App\GraphQL\Types\Customer\Input\UpdateCustomerStoreInfoInputType::class,
    // 供应商与用户的合作关系
    'CustomerSupplier'             => \App\GraphQL\Types\Customer\CustomerSupplier\CustomerSupplierType::class,
    // 合作关系申请
    'CooperationApplication'       => \App\GraphQL\Types\Customer\CooperationApplication\CooperationApplicationType::class,
    // 客户查询请求字段
    'CustomerPaginationInputQuery' => \App\GraphQL\Types\Customer\Input\CustomerPaginationInputQuery::class,
    // 为合作关系服务的业务员
    'SalesmanForCooperation'       => \App\GraphQL\Types\Customer\SalesmanForCooperation\SalesmanForCooperationType::class,
    //客户位置信息
    'CoordinateInfoInput'=> App\GraphQL\Types\Customer\Input\CoordinateInfoInputType::class,
], [
    //品牌信息
    'Brand'                            => BrandType::class,
    //商品信息
    'Product'                          => ProductType::class,
    //商品基础信息
    'BaseProduct'                      => BaseProductType::class,
    'BatchImportProduct'               => BatchImportProductType::class,
    //商品价格调整信息
    'ProductPriceAdjustment'           => ProductPriceAdjustmentType::class,
    //品牌阶梯价格上浮比例配置输出
    'BrandPriceIncreaseRatio'          => BrandPriceIncreaseRatioType::class,
    //品牌查询请求字段
    'BrandInput'                      => BrandInput::class,
    //商品查询字段
    'ProductInput'                    => ProductInput::class,
    //商品基础信息查询字段
    'BaseProductInput'                => BaseProductInput::class,
    //商品价格调整查询字段
    'ProductPriceAdjustmentInput'     => ProductPriceAdjustmentInput::class,
    //品牌阶梯价格上浮比例配置输入
    'BrandPriceIncreaseRatioInput'=> BrandPriceIncreaseRatioTypeInput::class,
], [
    // 贷款信息
    'Loan'                         => \App\GraphQL\Types\Finance\Loan\LoanType::class,
    // 贷款证件信息（包含了所有的证件）
    'LoanCredentialInfo'           => \App\GraphQL\Types\Finance\Loan\LoanCredentialInfoType::class,
    // [保存]贷款信息
    'StoreLoanInput'               => \App\GraphQL\Types\Finance\Loan\Input\StoreLoanInputType::class,
    // [保存]贷款证件信息
    'StoreLoanCredentialInfoInput' => \App\GraphQL\Types\Finance\Loan\Input\StoreLoanCredentialInfoInputType::class,
], [
    //月业绩统计
    'MonthData'        => \App\GraphQL\Types\Managenment\MonthDataType::class,
    //月业绩统计附加
    'MonthCommission'        => \App\GraphQL\Types\Managenment\MonthCommissionType::class,
    //月业绩统计附加
    'MonthCommissionStatics' => \App\GraphQL\Types\Managenment\MonthCommissionStaticsType::class,
    //首页业绩环比订单信息
    'OrderInfo' => \App\GraphQL\Types\Managenment\OrderInfoType::class,
    //首页地图
    'HomeMap' => \App\GraphQL\Types\Managenment\HomeMapType::class,
], [
    //站内信
   'InnerMessage' => \App\GraphQL\Types\Message\InnerMessageType::class,
]);
