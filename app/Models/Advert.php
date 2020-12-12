<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $region_id
 * @property string  $title
 * @property integer $price
 * @property string  $address
 * @property string  $content
 * @property string  $status
 * @property string  $reject_reason
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $published_at
 * @property Carbon  $expires_at
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Advert extends Model
{
    use HasFactory;

    /** Статусы объявления */
    const STATUS_DRAFT = 'draft';
    const STATUS_MODERATION = 'moderation';
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';

    /** @var array Переопределим какие поля не должны массово присваиваться */
    protected $guarded = ['id'];

    /** @var array Подскажем модели как преобразовывать поля бд */
    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Черновик
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isDraft()
    {
        return $this->status === static::STATUS_DRAFT;
    }

    /**
     * На модерации
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isModeration()
    {
        return $this->status === static::STATUS_MODERATION;
    }

    /**
     * Опубликовано
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isActive()
    {
        return $this->status === static::STATUS_ACTIVE;
    }

    /**
     * Закрыто
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isClosed()
    {
        return $this->status === static::STATUS_CLOSED;
    }
}
