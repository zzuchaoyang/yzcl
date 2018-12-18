<?php

namespace App\Models\System;

use App\Models\BaseModel;
use DB;

/**
 * App\Models\System\Area
 *
 * @property int $id
 * @property int $parent_id 父级ID
 * @property int $level 层级
 * @property int $area_code 行政代码
 * @property int $zip_code 邮政编码
 * @property string $city_code 区号
 * @property string $name 名称
 * @property string $short_name 简称
 * @property string $merger_name 组合名
 * @property string $pinyin 拼音
 * @property float $lng 经度
 * @property float $lat 纬度
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereAreaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereCityCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereLng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereMergerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area wherePinyin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\System\Area whereZipCode($value)
 * @mixin \Eloquent
 */
class Area extends BaseModel
{
    /**
     * china_area_mysql - 20160731版本
     *
     * https://github.com/kakuilan/china_area_mysql/releases
     */

    const NAME_TERRITORY = '直辖区';
    const NAME_MUNICIPAL = '市辖区';
    const NAME_COUNTY    = '直辖县';

    protected $fillable = [
        'name',
        'parten_id',
        'level',
        'area_code',
        'zip_code',
        'city_code',
        'short_name',
        'merger_name',
        'pinyin',
        'lng',
        'lat',
    ];

    /**
     * Relation to the parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_id');
    }

    /**
     * Relation to children.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    /**
     * 父级链
     *
     * @param       $id
     * @param array $areas
     *
     * @return array
     */
    public static function ancestorsAndSelf($id, $areas = array())
    {
        $area = self::find($id);

        array_unshift($areas,$area);

        if ($area && $area->parent_id) {
            $areas = self::ancestorsAndSelf($area->parent_id, $areas);
        }

        return $areas;
    }

    /**
     * 子级链
     *
     * @param       $ids
     * @param bool  $self
     * @param array $areas
     *
     * @return array
     */
    public static function descendantsAndSelf($ids, $self = false, $areas = array())
    {
        $ids = !is_array($ids) ? array($ids) : $ids;

        if ($self) {
            $areas = array_merge($areas, self::find($ids)->toArray());
        }

        $area = self::whereIn('parent_id', $ids)->get();

        $areas = array_merge($areas, $area->toArray());

        if ($area->isNotEmpty()) {
            $areas = self::descendantsAndSelf($area->pluck('id')->toArray(), false, $areas);
        }

        return $areas;
    }

    protected $allColumns = [
        'id',
        'parent_id', // 父级ID
        'level', // 层级
        'area_code', // 行政代码
        'zip_code', // 邮政编码
        'city_code', // 区号
        'name', // 名称
        'short_name', // 简称
        'merger_name', // 组合名
        'pinyin', // 拼音
        'lng', // 经度
        'lat', // 维度
    ];
}
