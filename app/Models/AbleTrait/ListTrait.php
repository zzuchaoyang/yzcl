<?php


namespace App\Models\AbleTrait;

use Illuminate\Pagination\LengthAwarePaginator;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Trait ListTrait
 * @package App\Models\AbleTrait
 */
trait ListTrait
{
    /**
     * @param $conditions
     * @param ResolveInfo $info
     * @return LengthAwarePaginator
     */
    public static function getGraphQLPaginator($conditions, ResolveInfo $info)
    {
        $fields = $info->getFieldSelection(5);

        // 如果要返回分页
        if (array_has($fields, 'cursor.total')) {
            // 如果有查询内容的话
            if (array_has($fields, 'items')) {
                return static::getList($conditions, ...static::getWithAndSelect($info));
            } else {
                // 如果没有查询内容，则认为是只获取分页
                return new LengthAwarePaginator([], static::getCount($conditions), 10);
            }
        } else {
            // 返回简单分页
            return static::getSimpleList($conditions, ...static::getWithAndSelect($info));
        }
    }
}
