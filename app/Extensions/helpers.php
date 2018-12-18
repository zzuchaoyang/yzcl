<?php

use Carbon\Carbon;
use Zttp\Zttp;

if (!function_exists('carbon')) {
    /**
     * @param $dateString
     *
     * @return Carbon
     */
    function carbon($dateString)
    {
        return new Carbon($dateString);
    }
}

if (!function_exists('salesman')) {
    /**
     * 业务员.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function salesman()
    {
        return auth('supplier_salesman')->user();
    }
}

if (!function_exists('supplier')) {
    /**
     *  供应商.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function supplier()
    {
        return auth('supplier')->user();
    }
}

if (!function_exists('driver')) {
    /**
     * 司机.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function driver()
    {
        return auth('supplier_driver')->user();
    }
}

if (!function_exists('customer')) {
    /**
     * 司机.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function customer()
    {
        return auth('customer')->user();
    }
}

if (!function_exists('user')) {
    /**
     *  根据登录的地址，返回对应的用户类型.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\Supplier\Supplier|\App\Models\Customer\Customer|\App\Models\Supplier\Driver|\App\Models\Supplier\Salesman
     */
    function user()
    {
        return supplier() ?? driver() ?? salesman() ?? customer() ?? null;
    }
}

if (!function_exists('beary_message')) {
    /**
     * 是否同步网站.
     *
     * @param $message
     */
    function beary_message($message)
    {
        $url = 'https://hook.bearychat.com/=bw8Hb/incoming/ec2c40967cc8e69b165452b7a95baaba';
        Zttp::withHeaders(['Content-Type' => 'application/json'])->post($url, [
            'text'        => $message,
            'markdown'    => true,
            'attachments' => [
                [
                    'color' => '#ffa500',
                ],
            ],
        ]);
    }
}

if (!function_exists('field_alias')) {
    /**
     * 根据约定的字段名路径返回对应的字段的别名.
     *
     *      例如:
     *              field_alias('customer.customer.name')  返回   客户名称
     *              field_alias('customer.customer')   返回 对应的数组
     *              field_alias('customer.customer.business_type#long-term') 返回 次租
     *
     * @param $src
     *
     * @return mixed|null
     */
    function field_alias($src)
    {
        // 获取整个别称文件内容
        $srcArr = explode('/', str_replace('.', '/', $src));
        $src    = config_path('fields-alias/').implode('/', $srcArr).'.php';
        if (is_file($src)) {
            return array_except((require $src), 'field_content_alias');
        }

        $field = array_pop($srcArr);

        $src = config_path('fields-alias/').implode('/', $srcArr).'.php';
        if (!is_file($src)) {
            return $field;
        }

        $fieldAliasArr = (require $src);

        // 判断是否获取某个字段内个值的别称
        if (false !== strpos($field, '#')) {
            $content = explode('#', $field)[1];
            $field   = explode('#', $field)[0];
            if (isset($fieldAliasArr['field_content_alias']) && isset($fieldAliasArr['field_content_alias'][$field]) && isset($fieldAliasArr['field_content_alias'][$field][$content])) {
                return $fieldAliasArr['field_content_alias'][$field][$content];
            }

            return $content;
        }

        // 获取某个字段的别称
        if (isset($fieldAliasArr[$field])) {
            return $fieldAliasArr[$field];
        }

        return $field;
    }
}

if (!function_exists('generateUuid')) {
    /**
     * 生成编码.
     *
     * @param $prefix
     * @param $id
     * @return string
     */
    function generateUuid($prefix, $id)
    {
        return $prefix.now()->format('ymd').str_pad($id, 3, 0, STR_PAD_LEFT);
    }
}

if (!function_exists('deep_merge')) {
    /**
     * 深度递归合并
     *
     * @param $arr1
     * @param $arr2
     * @return mixed
     */
    function deep_merge($arr1, $arr2)
    {
        if (is_string($arr1)) {
            return $arr2;
        }

        foreach ($arr2 as $key => $value) {
            $arr1[$key] = is_array($value) ? deep_merge(isset($arr1[$key]) ? $arr1[$key] : [], $value) : $value;
        }

        return $arr1;
    }
}
