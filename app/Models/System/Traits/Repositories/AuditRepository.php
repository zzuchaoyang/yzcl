<?php

namespace App\Models\System\Traits\Repositories;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

trait AuditRepository
{

    /**
     * 生成日志详情
     *
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    private function generateEventDetail()
    {
        try {
            // 处理映射字段
            $fields_alias_src = constant($this->auditable_type.'::FIELDS_ALIAS_SRC');
        } catch (ErrorException $exception) {
            $fields_alias_src = '';
        }

        $events = collect();

        $this->palette($events, $this->new_values, $fields_alias_src);

        return $events;
    }

    private function palette($events, $data, $fields_alias_src)
    {
        foreach ($data as $key => $value) {

            if ($subValue = $this->getNested($value)) {

                $sub_fields_alias_src = $fields_alias_src;

                if (!is_int($key)) {
                    $sub_fields_alias_src = str_before($fields_alias_src, '#').'.'.$key."#";
                }

                $this->palette($events, $subValue, $sub_fields_alias_src);

            } else {

                $sub_fields_alias_src = str_replace('#.', '#', $fields_alias_src.'.'.$key);

                $events->push([
                    'event'       => array_get(self::EVENTS, $this->event),
                    'field'       => $key,
                    'field_alias' => field_alias($sub_fields_alias_src),
                    'form'        => $this->old_values ? $this->combSpecialField($key, $this->old_values[$key], $sub_fields_alias_src) : '<空>',
                    'to'          => $this->combSpecialField($key, $value, $sub_fields_alias_src),
                ]);
            }
        }
    }

    private function getNested($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $value = json_decode($value, true);

        if (!is_array($value)) {
            return null;
        }

        if (isset($value[0]) && !is_array($value[0])) {
            return null;
        }

        return $value;
    }

    /**
     * 处理特殊字段
     *
     * @param $key
     * @param $value
     * @param $fields_alias_src
     *
     * @return \Illuminate\Support\Collection|mixed|null|\Tightenco\Collect\Support\Collection
     * @throws \ReflectionException
     */
    private function combSpecialField($key, $value, $fields_alias_src)
    {
        // 处理状态字段
        if (in_array($key, ['status', 'gender', 'is_admin', 'is_married']) && in_array($value, [0, 1, false, true])) {

            if (is_bool($value)) {
                $value = $value ? 1 : 0;
            }

            return field_alias("{$fields_alias_src}"."#".$value);
        }

        return $this->getRelationsNestedUnder($key, $value);
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     * @throws \ReflectionException
     */
    private function getRelationsNestedUnder($key, $value)
    {
        if (!ends_with($key, '_id')) {
            return $value;
        }

        /** @var Model $model */
        $model = $this->auditable_type;

        $method = camel_case(str_before($key, '_id'));

        if (!(new ReflectionClass($model))->hasMethod($method)) {
            return $value;
        }

        $res = $model::findOrFail($this->auditable_id)->$method()->getModel()->find($value);

        if (!$res) {
            $value;
        }

        if ($name = $res->name) {
            return $name;
        }

        return $res->id;
    }

}
