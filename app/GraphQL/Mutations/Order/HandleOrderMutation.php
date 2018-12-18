<?php

namespace App\GraphQL\Mutations\Order;

use App\Exceptions\GeneralContinueException;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\Message\InnerMessage;
use App\Models\Order\OrderLog;
use DB;
use GraphQL\Type\Definition\Type;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Supplier\Supplier;
use StateMachine;

class HandleOrderMutation extends BaseMutation
{
    protected $attributes = [
        'name'        => 'handleOrder',
        'description' => '订单的操作',
    ];

    //每个角色能操作的状态转换
    private $roleTransitions = [
        Customer::class => [
            'customer_cancel', //取消订单
            'supplier_shiping', //客户确认订单
            'customer_signed', //客户无误签收
            'error_signed', //客户有误签收
        ],
        Supplier::class => [
            'supplier_reject', //供应商无法供货
            'supplier_change_order', //供货商修改完订单修改
            'supplier_shiping', //供应商同意供货
            'supplier_shipped', //供应商点击发货
            'system_signed', //供应商点击强制签收
        ],
    ];

    public function type()
    {
        return Type::boolean();
    }

    public function args()
    {
        return [
            'id'          => [
                'name'        => 'id',
                'type'        => Type::nonNull(Type::int()),
                'description' => '订单id',
                'rules'       => ['exists:orders,id'],
            ],
            'transition' => [
                'name'        => 'transition',
                'type'        => Type::string(),
                'description' => '此订单需要执行的操作',
                'rules'       => ['required'],
            ],
            'car_number'  => [
                'name'        => 'car_number',
                'type'        => Type::string(),
                'description' => '订单发货时车牌号',
            ],
            'driver_id'   => [
                'name'        => 'driver_id',
                'type'        => Type::int(),
                'description' => '订单发货的司机id',
                'rules'       => ['exists:drivers,id'],
            ],
        ];
    }

    public function authenticated($root, $args, $currentUser)
    {
        $user = user();

        return $user instanceof Supplier || $user instanceof Customer;
    }

    public function resolve($root, $args)
    {
        $order        = Order::findOrFail($args['id']);
        $transition   = $args['transition'];
        $carNumber    = array_get($args, 'car_number');
        $driverId     = array_get($args, 'driver_id');
        $stateMachine = StateMachine::get($order, 'order');

        //状态转换的类型必须在配置项内，不然不允许操作
        if (!array_key_exists($transition, config('state-machine.order.transitions'))) {
            throw new GeneralContinueException('你当前不能操作订单');
        }

        // 判断用户是否有该操作的能力
        if (!in_array($transition, $this->roleTransitions[get_class(user())])) {
            throw new GeneralContinueException('你当前没有权限操作此订单');
        }

        //如果订单被修改了,且是供货商的审核,等待客户的确认
        if ($order->is_edit && $transition == 'supplier_shiping' && user() instanceof Supplier) {
            $transition = 'supplier_change_order';
        }

        // 检查订单的操作条件是否有异常
        $this->checkTransitionCondition($transition, $order, $carNumber, $driverId, $stateMachine);

        // 订单更新状态
        return $this->handleOrder($order, $stateMachine, $transition);
    }

    /**
     * 更新订单状态
     *
     * @param Order $order
     * @param $transitions
     * @param $stateMachine
     *
     * @return bool
     *
     * @throws \SM\SMException
     */
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
     * 检查订单的操作条件是否有异常.
     *
     * @param $transition
     * @param $order
     * @param $carNumber
     * @param $driverId
     * @param $stateMachine
     *
     * @throws GeneralContinueException
     * @throws \SM\SMException
     */
    private function checkTransitionCondition($transition, Order $order, $carNumber, $driverId, $stateMachine): void
    {
        /**
         * 当用户进行订单的业务操作的时候，前端会传递过来对应的业务名称。下面会针对业务名称进行数据判断，以防出现异常数据.
         *
         * 1. 第一步，业务操作时，订单的状态变更是否被允许
         * 2. 第二部，业务操作时，订单的参数是否允许
         */

        //获取用户当前想进行的状态转换
        $statusTransitions = config('state-machine.order.transitions')[$transition]['to'];
        $statusZhcn        = array_get($order->allStatus, $statusTransitions);
        $currentStatusZhcn = array_get($order->allStatus, $order->status);
        $logInfo           = null;

        // 状态是否允许流转
        if (!$stateMachine->can($transition)) {
            throw new GeneralContinueException('订单当前状态为'.$currentStatusZhcn.',不允许改变为'.$statusZhcn.',或者请刷新重试');
        }

        // 系统强制签收
        if ($transition == 'system_signed') {
            if (!$order->canForceSigned()) {
                throw new GeneralContinueException('此订单还不允许强制签收');
            }
            $logInfo['content']  = '您的订单已经被强制签收';
            $order->signed_at    = now();
        }
        // 供应商发货
        elseif ($transition == 'supplier_shipped') {
            if ($carNumber && $driverId) {
                $order->driver_id     = $driverId;
                $order->car_number    = $carNumber;
                $order->send_status   = Order::WAITING;
                $order->send_start_at = now();
                $logInfo['content']   = '您的订单已分配司机，正在等待配送';
            } else {
                throw new GeneralContinueException('必须选择司机和车牌号');
            }
        }
        //客户签收操作
        elseif ($transition == 'customer_signed' || $transition == 'error_signed') {
            $order->signed_at    = now();
            $logInfo['content']  = '订单已经签收';
        }
        if ($logInfo) {
            $logInfo['order_id'] = $order->id;
        }
        $order->logInfo      =  $logInfo;
    }

    protected function sendInfo($order, $transitions)
    {
        //记录订单配送日志
        if ($order->logInfo) {
            OrderLog::create($order->logInfo);
        }
        unset($order->logInfo);

        //记录消息
        $contentHead = '您的订单: '.$order->order_number;
        $contentNeck = '';
        $contentFoot = '['.array_get(user()->info, 'company.gsmc').']';
        $operation   = '';

        if ($transitions == 'supplier_change_order' && $order->is_edit) {
            $operation   = 'order_edit';
            $contentNeck = ',采购数量被修改';
        } elseif ($transitions == 'supplier_reject') {
            $operation   = 'order_over';
            $contentNeck = ',暂时无法供货';
        } elseif ($transitions == 'supplier_shipped') {
            $operation   = 'order_send';
            $contentNeck = ',已分配送货司机';
        } elseif ($transitions == 'system_signed') {
            $operation   = 'order_signed';
            $contentNeck = ',已强制签收';
        }
        if ($operation) {
            $content   = $contentHead.$contentNeck.$contentFoot;
            $this->sendInnerMessage($order, $operation, $content);
        }
    }

    protected function sendInnerMessage($order, $operation, $content)
    {
        InnerMessage::systemSend($operation, user()->id, 'supplier', $order->customer_id, 'customer', $order->id, 'order', $content);
    }
}
