<?php

namespace App\Models\Message;

use App\Models\BaseModel;
use App\Models\AbleTrait\ListTrait;
use Illuminate\Notifications\Notifiable;
use MatrixLab\LaravelAdvancedSearch\AdvancedSearchTrait;
use MatrixLab\LaravelAdvancedSearch\WithAndSelectForGraphQLGeneratorTrait;

/**
 * App\Models\Message\InnerMessage.
 *
 * @property int $id
 * @property string|null $type 类型
 * @property int $sender_id 发送人id
 * @property string|null $sender_type 发送人类型
 * @property int $receiver_id 接收人id
 * @property string|null $receiver_type 接收人类型
 * @property int $relation_id 关联id
 * @property string|null $relation_type 关联类型
 * @property string|null $content 信息
 * @property int $is_read 是否已读
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage searchKeyword($key)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereRelationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message\InnerMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InnerMessage extends BaseModel
{
    use ListTrait;
    use Notifiable;
    use AdvancedSearchTrait;
    use WithAndSelectForGraphQLGeneratorTrait;

    protected $fillable = [
        'type',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'relation_id',
        'relation_type',
        'content',
        'is_read',
        'is_top'
    ];

    protected $indexs = [
        '*',
    ];

    protected $casts = [

    ];

    public static function systemSend($type, $sender_id, $sender_type, $receiver_id, $receiver_type, $relation_id, $relation_type, $content)
    {
        $info = [
            'type'          => $type,
            'sender_id'     => $sender_id,
            'sender_type'   => $sender_type,
            'receiver_id'   => $receiver_id,
            'receiver_type' => $receiver_type,
            'relation_id'   => $relation_id,
            'relation_type' => $relation_type,
            'content'       => $content,
        ];
        self::create($info);
    }

    public static function read($inner_message_id)
    {
        self::where('id',$inner_message_id)->update([
            'is_read' => 1,
        ]);
    }

    protected $allColumns = [
        'id',
        'type', // 类型
        'sender_id', // 发送人id
        'sender_type', // 发送人类型
        'receiver_id', // 接收人id
        'receiver_type', // 接收人类型
        'relation_id', // 关联id
        'relation_type', // 关联类型
        'content', // 信息
        'is_read', // 是否已读
        'is_top',
        'created_at',
        'updated_at',
    ];
}
