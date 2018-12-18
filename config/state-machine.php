<?php

use App\Fx\StateMachine\Order\OrderStateMachine;

return [
    'order' => [
        // class of your domain object
        'class'         => \App\Models\Order\Order::class,

        // name of the graph (default is "default")
        'graph'         => 'order',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'status',

        // list of all possible states
        'states'        => [
            'approving',    // 待审核
            'unsupply', // 无法供货
            'confirming', // 待确认
            'cancelled', // 取消订货
            'shipping', // 待发货
            'signing', // 待签收
            'force_signed', // 强制签收
            'signed', // 已签收（无误）
            'invalid_signed', // 已签收（有误）
        ],

        // list of all possible transitions
        'transitions'   => [
            // 供应商无法供货
            'supplier_reject'       => [
                'from' => ['approving'],
                'to'   => 'unsupply',
            ],
            // 供应商调整订单内容，需要客户确认
            'supplier_change_order' => [
                'from' => ['approving'],
                'to'   => 'confirming',
            ],
            // 客户取消订货
            'customer_cancel'       => [
                'from' => ['confirming'],
                'to'   => 'cancelled',
            ],
            // 供应商直接确认, 或者是门店确认修改后的订单 ,准备发货
            'supplier_shiping'         => [
                'from' => ['approving', 'confirming'],
                'to'   => 'shipping',
            ],
            // 供应商发货，待客户签收
            'supplier_shipped'      => [
                'from' => ['shipping'],
                'to'   => 'signing',
            ],
            // 系统强制签收,司机确认送达后72小时
            'system_signed'      => [
                'from' => ['signing'],
                'to'   => 'force_signed',
            ],
            // 无误签收(供货商发货与客户需求一致)
            'customer_signed'      => [
                'from' => ['signing'],
                'to'   => 'signed',
            ],
            // 有误签收(供货商发货与客户需求不一致)
            'error_signed'      => [
                'from' => ['signing'],
                'to'   => 'invalid_signed',
            ],
        ],

        // list of all callbacks
        'callbacks'     => [
            // will be called when testing a transition
            'guard'  => [
                'guard_on_customer_submit' => [
                    // call the callback on a specific transition
                    'on'   => 'submit_changes',
                    // will call the method of this class
                    'do'   => ['MyClass', 'handle'],
                    // arguments for the callback
                    'args' => ['object'],
                ],
                'guard_on_approving'       => [
                    // call the callback on a specific transition
                    'on'  => 'approve',
                    // will check the ability on the gate or the class policy
                    'can' => 'approve',
                ],
            ],

            // will be called before applying a transition
            'before' => [],

            // will be called after applying a transition
            'after'  => [
                [
                    'on' => 'customer_submit',
                    'do' => [OrderStateMachine::class, 'afterCustomerSubmit'],
                ],
            ],
        ],
    ],
    'delivered' => [
        // class of your domain object
        'class'         => \App\Models\Order\Order::class,

        // name of the graph (default is "default")
        'graph'         => 'delivered',

        // property of your object holding the actual state (default is "state")
        'property_path' => 'send_status',

        // list of all possible states
        'states'        => [
            'waiting',    // 待配送
            'delivering', // 待送达
            'delivered',  // 已完成
        ],

        // list of all possible transitions
        'transitions'   => [
            // 司机确认开始配送
            'driver_confirmed'      => [
                'from' => ['waiting'],
                'to'   => 'delivering',
            ],
            // 司机送达，待客户签收
            'driver_service'      => [
                'from' => ['delivering'],
                'to'   => 'delivered',
            ],
            //司机配送途中
            'driver_delivering'      => [
                'from' => ['delivering'],
                'to'   => 'delivering',
            ],
        ],
        // list of all callbacks
        'callbacks'     => [
            // will be called when testing a transition
            'guard'  => [
                'guard_on_customer_submit' => [
                    // call the callback on a specific transition
                    'on'   => 'submit_changes',
                    // will call the method of this class
                    'do'   => ['MyClass', 'handle'],
                    // arguments for the callback
                    'args' => ['object'],
                ],
                'guard_on_approving'       => [
                    // call the callback on a specific transition
                    'on'  => 'approve',
                    // will check the ability on the gate or the class policy
                    'can' => 'approve',
                ],
            ],

            // will be called before applying a transition
            'before' => [],

            // will be called after applying a transition
            'after'  => [
                [
                    'on' => 'driver_confirmed',
                    'do' => [OrderStateMachine::class, 'afterDriverConfirmed'],
                ],
            ],
        ],
    ],
];
