<?php

namespace App\GraphQL\Mutations\Order;

use App\Exceptions\GeneralContinueException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Message\InnerMessage;
use App\Models\Order\OrderLog;
use App\Models\Supplier\Supplier;
use DB;
use GraphQL\Type\Definition\Type;
use App\Models\Order\Order;
use App\Models\Supplier\Driver;
use StateMachine;
use GraphQL;

class DriverHandleOrderMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'driverHandleOrder',
        'description' => '司机对于订单的操作',
    ];

    //角色能操作的状态转换
    private $roleTransitions = [
        Driver::class   => [
            'driver_service', //司机送达
            'driver_confirmed', //司机开始配送
            'driver_delivering', //司机配送中
        ],
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'data' => [
                'name'        => 'data',
                'type'        => GraphQL::type('DriverHandleOrderMutationInput'),
                'description' => '订单信息',
                'rules'       => ['required'],
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        $user = user();

        return  $user instanceof Driver;
    }

    public function resolve($root, $args)
    {
        $order        = Order::findOrFail($args['data']['id']);
        $transition   = $args['data']['transition'];
        $stateMachine = StateMachine::get($order, 'delivered');
        //当前订单的位置信息
        $positionInfo= [
            'current_position' => array_get($args['data'], 'position'),
            'position'         => [
                'latitude'  => array_get($args['data'], 'current_latitude'),
                'longitude' => array_get($args['data'], 'current_longitude'),
            ],
        ];
        $order->position_info = $positionInfo;

        //状态转换的类型必须在配置项内，不然不允许操作
        if (!array_key_exists($transition, config('state-machine.delivered.transitions'))) {
            throw new GeneralContinueException('你当前不能操作订单');
        }

        // 判断用户是否有该操作的能力
        if (!in_array($transition, $this->roleTransitions[get_class(user())])) {
            throw new GeneralContinueException('你当前没有权限操作此订单');
        }

        // 检查订单的操作条件是否有异常
        $this->checkTransitionCondition($transition, $order, $stateMachine);

        // 订单更新状态
        return $this->handleOrder($order, $stateMachine, $transition);
    }

    private function checkTransitionCondition($transition, Order $order, $stateMachine): void
    {
        /**
         * 当用户进行订单的业务操作的时候，前端会传递过来对应的业务名称。下面会针对业务名称进行数据判断，以防出现异常数据.
         *
         * 1. 第一步，业务操作时，订单的状态变更是否被允许
         * 2. 第二部，业务操作时，订单的参数是否允许
         */

        //获取用户当前想进行的状态转换
        $statusTransitions    = config('state-machine.delivered.transitions')[$transition]['to'];
        $status               = array_get($order->sendStatus, $statusTransitions);
        $currentStatus        = array_get($order->sendStatus, $order->send_status);

        // 状态是否允许流转
        if (!$stateMachine->can($transition)) {
            throw new GeneralContinueException('订单当前状态为'.$currentStatus.',不允许改变为'.$status.',或者请刷新重试');
        }

        // 司机开始接单配送
        if ($transition == 'driver_confirmed') {
            // 开始配送时间为当前
            $order->send_start_at     = now();
        }
        // 司机发货到达
        elseif ($transition == 'driver_service') {
            if ($order->send_at) {
                throw new GeneralContinueException('此订单还已经点击送达了');
            }
            // 货物送达
            $order->send_at     = now();
        }
    }

    private function handleOrder(Order $order, $stateMachine, $transitions)
    {
        return DB::transaction(function () use ($order, $stateMachine, $transitions) {
            //记录消息
            $this->sendInfo($order, $transitions);
            $stateMachine->apply($transitions);

            return $order->save();
        });
    }

    /**
     * @param $order
     * @param $transitions
     */
    protected function sendInfo($order, $transitions)
    {
        //订单配送日志
        $positionInfo         = $order->position_info;
        $logInfo['order_id']  = $order->id;
        $logInfo['position']  = $positionInfo['current_position'];
        $logInfo['latitude']  = $positionInfo['position']['latitude'];
        $logInfo['longitude'] = $positionInfo['position']['longitude'];

        //记录消息
        $contentHead = '您的订单: '.$order->order_number;
        $contentFoot = '['.array_get(Supplier::find($order->supplier_id)->info, 'company.gsmc').']';

        if ($transitions == 'driver_service') {
            $operation   = 'order_service';
            $contentNeck = ',已确认送达, 请注意查收';
            //记录日志
            $logInfo['content'] = '您的订单已经送达,送达地址 : '.$positionInfo['current_position'].',请注意签收';
        } elseif ($transitions == 'driver_confirmed') {
            $operation   = 'order_send_confirmed';
            $contentNeck = ',已确认配送, 请注意查收';
            //记录日志
            $logInfo['content'] = '您的订单司机已经确认发货,发货位置 : '.$positionInfo['current_position'];
        } else {
            $operation   = 'order_sending';
            $contentNeck = ',正在配送中, 请您耐心等待';
            //记录日志
            $logInfo['content'] = '您的订单现在到达'.$positionInfo['current_position'];
        }
        $content   = $contentHead.$contentNeck.$contentFoot;
        $this->sendInnerMessage($order, $operation, $content, $logInfo);
    }

    protected function sendInnerMessage($order, $operation, $content, $logInfo)
    {
        InnerMessage::systemSend($operation, user()->id, 'supplier', $order->customer_id, 'customer', $order->id, 'order', $content);
        OrderLog::create($logInfo);
    }
}
