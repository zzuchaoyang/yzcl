<?php

namespace App\GraphQL\Queries\Managenment;

use App\GraphQL\Queries\BaseQuery;
use App\Models\Customer\CooperationApplication;
use App\Models\Customer\CustomerSupplier;
use App\Models\Order\Order;
use App\Models\Supplier\Supplier;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use GraphQL;

class HomeCommissionStaticsQuery extends BaseQuery
{
    protected $attributes = [
        'name'        => 'homeCommissionStatics',
        'description' => '首页业绩统计',
    ];

    public function type()
    {
        return GraphQL::type('MonthData');
    }

    public function authenticated($root, $args, $currentUser)
    {
        return user() instanceof Supplier;
    }

    public function args()
    {
        return [

        ];
    }

    public function resolve($root, $args, $context, $info)
    {
        $fields                = $info->getFieldSelection(5);
        $currentMothCommission = [];
        $lastMothCommission    = [];
        $beingApproveOrder     = $beingSendOrder   = $beingApproveApplication = $todayCommissionTotal = 0;
        $todayOrderInfo        = $lastOrderInfo    = $monthOrderInfo          = $lastMonthOrderInfo   = [
            'count'      => 0,
            'commission' => 0,
            'ratio'      => 0,
            'is_grow'    => true,
        ];
        //本月开始
        $currentMonthStart = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), 1, date('Y')));
        //本月结束
        $currentMonthEnd   = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('t'), date('Y')));
        //上月开始
        $lastMonthStart    = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 1, 1, date('Y')));
        //上月结束
        $lastMonthEnd      = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), 0, date('Y')));
        //上上月开始
        $twoMonthStart    = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 2, 1, date('Y')));
        //上上月结束
        $twoMonthEnd      = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m') - 1, 0, date('Y')));

        if (array_has($fields, 'current_month')) {
            $currentMothCommission = $this->getMonthCommission($currentMonthStart, $currentMonthEnd);
        }

        if (array_has($fields, 'last_month')) {
            $lastMothCommission = $this->getMonthCommission($lastMonthStart, $lastMonthEnd);
        }

        if (array_has($fields, 'being_approve_order') || array_has($fields, 'being_send_order')) {
            $orders = Order::select('id', 'status')->whereIn('status', [Order::APPROVING, Order::SHIPPING])->get();
            //待审核订单
            $beingApproveOrder = count($orders->where('status', Order::APPROVING));
            //待发货订单
            $beingSendOrder = count($orders->where('status', Order::SHIPPING));
        }

        if (array_has($fields, 'being_approve_application')) {
            //待审核的申请
            $beingApproveApplication = CooperationApplication::where('sender_type', 'customer')->where('receiver_id',
                user()->id)->where(function ($q) {
                    $q->whereHas('customerSupplier', function ($q) {
                        $q->where('status', CustomerSupplier::STATUS_WAIT);
                    });
                })->count();
        }

        if (array_has($fields, 'today_commission_total')) {
            $todayCommissionTotal = Order::whereBetween('signed_at',
                [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->whereIn('status',
                [Order::SIGNED, Order::INVALID_SIGNED, Order::FORCE_SIGNED])->sum('send_price');
        }

        if (array_has($fields, 'today_order_info') || array_has($fields, 'last_order_info')) {
            //当天,昨天,前天  订单
            $todayCount   = Order::whereBetween('order_at', [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay(),
            ])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();
            $lastDayCount = Order::whereBetween('order_at', [
                Carbon::yesterday()->startOfDay(),
                Carbon::yesterday()->endOfDay(),
            ])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();
            $twoDayCount  = Order::whereBetween('order_at', [
                Carbon::parse('2 days ago')->startOfDay(),
                Carbon::parse('2 days ago')->endOfDay(),
            ])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();

            $todayRatio = $lastDayCount->price != 0 ? (bcsub($todayCount->price,$lastDayCount->price,2) /
                $lastDayCount->price) : 0;
            $todayOrderInfo = [
                'count'      => $todayCount->count,
                'commission' => sprintf('%.2f',$todayCount->price),
                'ratio'      => sprintf('%.2f',$todayRatio * 100),
                'is_grow'    => ($todayRatio >= 0) ? true : false,
            ];
            $lastRatio = $twoDayCount->price != 0 ? (bcsub($lastDayCount->price,$twoDayCount->price,2) /
                $twoDayCount->price) : 0;
            $lastOrderInfo  = [
                'count'      => $lastDayCount->count,
                'commission' => sprintf('%.2f',$lastDayCount->price),
                'ratio'      => sprintf('%.2f',$lastRatio * 100),
                'is_grow'    => ($lastRatio >= 0) ? true : false,
            ];
        }

        if (array_has($fields, 'month_order_info') || array_has($fields, 'last_month_order_info')) {
            //当月,上月,前月  订单
            $monthCount     = Order::whereBetween('order_at',
                [$currentMonthStart, $currentMonthEnd])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();
            $lastMonthCount = Order::whereBetween('order_at',
                [$lastMonthStart, $lastMonthEnd])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();
            $twoMonthCount  = Order::whereBetween('order_at',
                [$twoMonthStart, $twoMonthEnd])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->selectRaw('sum(price) as price,count(id) as count')->first();

            $monthRatio = $lastMonthCount->price != 0 ? (bcsub($monthCount->price,$lastMonthCount->price,2) / $lastMonthCount->price) : 0;
            $monthOrderInfo = [
                'count'      => $monthCount->count,
                'commission' => sprintf('%.2f',$monthCount->price),
                'ratio'      => sprintf('%.2f',$monthRatio * 100),
                'is_grow'    => ($monthRatio >= 0) ? true : false,
            ];
            $lastMonthRatio = $twoMonthCount->price != 0 ? (bcsub($lastMonthCount->price,$twoMonthCount->price,2) / $twoMonthCount->price) : 0;
            $lastMonthOrderInfo = [
                'count'      => $lastMonthCount->count,
                'commission' => sprintf('%.2f',$lastMonthCount->price),
                'ratio'      => sprintf('%.2f',$lastMonthRatio * 100),
                'is_grow'    => ($lastMonthRatio >= 0) ? true : false,
            ];
        }

        return collect([
            'current_month'              => $currentMothCommission,
            'last_month'                 => $lastMothCommission,
            'being_approve_order'        => $beingApproveOrder,
            'being_send_order'           => $beingSendOrder,
            'being_approve_application'  => $beingApproveApplication,
            'today_commission_total'     => $todayCommissionTotal,
            'today_order_info'           => $todayOrderInfo,
            'last_order_info'            => $lastOrderInfo,
            'month_order_info'           => $monthOrderInfo,
            'last_month_order_info'      => $lastMonthOrderInfo,
        ]);
    }

    public function getMonthCommission($currentMonthStart, $currentMonthEnd)
    {
        $orders           = Order::whereBetween('order_at', [$currentMonthStart, $currentMonthEnd])->whereNotIn('status',[Order::UNSUPPLY,Order::CANCELLED])->get();
        $carbonMonthStart = Carbon::parse($currentMonthStart);
        $carbonMonthEnd   = Carbon::parse($currentMonthEnd);

        $diffInDays = $carbonMonthStart->diffInDays($carbonMonthEnd);
        for ($i = 0; $i <= $diffInDays; ++$i) {
            $trendDays[$carbonMonthStart->toDateString()] = 0;
            $carbonMonthStart->addDay();
        }
        foreach ($orders as $order) {
            $order_order_at   = Carbon::parse($order->order_at);
            if (array_key_exists($order_order_at->format('Y-m-d'), $trendDays)) {
                $trendDays[$order_order_at->format('Y-m-d')] += $order->price;
            } else {
                $trendDays[$order_order_at->format('Y-m-d')] += 0;
            }
        }

        $data = collect();

        foreach ($trendDays as $key => $trendDay) {
            $day = ltrim(Carbon::parse($key)->format('d'), 0).'日';
            $num = sprintf('%.2f',$trendDay);

            $data->push([
                'day' => $day,
                'num' => $num,
            ]);
        }

        return $data;
    }
}
