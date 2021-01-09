<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
 * @property \App\Models\User     $user
 * @property \App\Models\Region   $region
 * @property \App\Models\Photo[]  $photos
 * @property \App\Models\Category $category
 * @property \App\Models\Value[]  $values
 *
 * @method Builder forUser(User $user)
 * @method Builder forCategory(Category $category)
 * @method Builder forRegion(Region $region)
 * @method Builder active()
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

    /**
     * Присвоить статус на модерации
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function sendToModeration()
    {
        if (!$this->isDraft()) {
            throw new \DomainException(__('Advert is not draft'));
        }

        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    /**
     * Акцептим объявление
     *
     * @param \Carbon\Carbon $date
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function moderate(Carbon $date)
    {
        if (!$this->isModeration()) {
            throw new \DomainException(__('Advert is not sent moderation'));
        }

        $this->update([
            'published_at' => $date,
            'expires_at' => $date->copy()->addDays(30),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Отказать в поубликации объявления и указать причину отказа
     *
     * @param string $reason
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function reject(string $reason)
    {
        if (!$this->isModeration()) {
            throw new \DomainException(__('Advert is not sent moderation'));
        }

        $this->update([
            'reject_reason' => $reason,
            'status' => self::STATUS_DRAFT,
        ]);
    }

    /**
     * Закрыть объявление
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function close()
    {
        $this->update(['status' => self::STATUS_CLOSED]);
    }

    /**
     * Получаем значение дополнительного атрибута
     *
     * @param integer $id
     *
     * @return null|string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getValue($id)
    {
        foreach ($this->values as $value) {
            if ($value->attribute_id === $id) {
                return $value->value;
            }
        }

        return null;
    }

    /**
     * Скоуп для получения только активных объявлений
     *
     * @param Builder $query
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeActive(Builder $query)
    {
        $query->where('status', Advert::STATUS_ACTIVE);
    }

    /**
     * Скоуп для получения объявлений пользователя
     *
     * @param Builder $query
     * @param User    $user
     *
     * @return Builder
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Скоуп для получения объявлений для категории
     *
     * @param Builder  $query
     * @param Category $category
     *
     * @return Builder
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeForCategory(Builder $query, Category $category)
    {
        return $query->where('category_id', array_merge(
            [$category->id],
            $category->descendants()->pluck('id')->toArray()
        ));
    }

    /**
     * Скоуп для получения объявлений для региона
     *
     * @param Builder $query
     * @param Region  $region
     *
     * @return Builder
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeForRegion(Builder $query, Region $region)
    {
        $ids = [$region->id];
        $childrenIds = $ids;
        while ($childrenIds = Region::whereIn('parent_id', $childrenIds)->pluck('id')->toArray()) {
            $ids = array_merge($ids, $childrenIds);
        }

        return $query->whereIn('region_id', $ids);
    }

    /**
     * **********************************************************************
     * *******************  Relations ***************************************
     * **********************************************************************
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function values()
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }
}
