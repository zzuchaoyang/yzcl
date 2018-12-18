<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDeepMerge()
    {
        $a = [
            'name' => 'colo',
            'age'  => 20,
            'info' => [
                'amount' => 100,
                'min'    => 39,
                'max'    => 59,
                'other'  => '111',
                'sub'    => [
                    'name' => 'sub',
                    'age'  => '11',
                ],
                'sub2'   => 'sub2',
                'sub4'   => [
                    'name' => 'sub4',
                ],
            ],
        ];

        $b = [
            'name' => 'cool',
            'info' => [
                'min'  => 20,
                'sub'  => [
                    'name' => '111',
                ],
                'sub2' => [
                    'name' => 'sub2',
                ],
                'sub3' => [
                    'name' => 'sub3',
                ],
                'sub4' => 'sub4',
            ],
        ];

        $expectC = [
            'name' => 'cool',
            'age'  => 20,
            'info' => [
                'amount' => 100,
                'min'    => 20,
                'max'    => 59,
                'other'  => '111',
                'sub'    => [
                    'name' => '111',
                    'age'  => '11',
                ],
                'sub2'   => [
                    'name' => 'sub2',
                ],
                'sub3'   => [
                    'name' => 'sub3',
                ],
                'sub4'   => 'sub4',
            ],
        ];

        $c = deep_merge($a, $b);

        $this->assertTrue($expectC == $c);
    }
}
