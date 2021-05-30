<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class Banner
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $region_id
 * @property string  $name
 * @property integer $views
 * @property integer $limit
 * @property integer $clicks
 * @property integer $cost
 * @property string  $url
 * @property string  $format
 * @property string  $file
 * @property string  $status
 * @property string  $published_at
 * @property string  $created_at
 * @property string  $updated_at
 *
 * @property \App\Models\User     $user
 * @property \App\Models\Region   $region
 * @property \App\Models\Category $category
 *
 * @method Builder forUser(User $user)
 * @method Builder active()
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Banner extends Model
{
    use HasFactory;

    /** Статусы баннеров */
    public const STATUS_DRAFT      = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_MODERATED  = 'moderated';
    public const STATUS_ORDERED    = 'ordered';
    public const STATUS_ACTIVE     = 'active';
    public const STATUS_CLOSED     = 'close';

    /** @var string */
    protected $table = 'banners';

    /** @var array */
    protected $guarded = ['id'];

    /** @var array */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Список статусов
     *
     * @return array
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT      => 'Draft',
            self::STATUS_MODERATION => 'On Moderation',
            self::STATUS_MODERATED  => 'moderated',
            self::STATUS_ORDERED    => 'Payment',
            self::STATUS_ACTIVE     => 'Active',
            self::STATUS_CLOSED     => 'Closed',
        ];
    }

    /**
     * Проверка, что баннер можно редактировать
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function canBeChanged()
    {
        return $this->isDraft();
    }

    /**
     * Проверка, что баннер можно удалять
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function canBeRemoved()
    {
        return !$this->isActive();
    }

    /**
     * Список форматов баннеров
     *
     * @return array
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public static function formatsList()
    {
        return [
            '240x400',
        ];
    }

    /**
     * Отправить на модерацию
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function sendToModeration()
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Banner is not draft');
        }

        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    /**
     * Отмена модерации
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function cancelModeration()
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Banner is not sent to moderation');
        }

        $this->update([
            'status' => self::STATUS_DRAFT,
        ]);
    }

    /**
     * Смена статуса баннера на - Проверен
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function moderate()
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Banner is not sent to moderation');
        }

        $this->update([
            'status' => self::STATUS_MODERATED,
        ]);
    }

    /**
     * Отказ в принятии баннера с указанием причины
     *
     * @param string $reason
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function reject($reason)
    {
        $this->update([
            'status'        => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    /**
     * Переход к оплате баннера
     *
     * @param int $cost
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function order(int $cost)
    {
        if (!$this->isModerated()) {
            throw new \DomainException('Banner is not moderated');
        }

        $this->update([
            'cost'   => $cost,
            'status' => self::STATUS_ORDERED,
        ]);
    }

    /**
     * Активация баннера после оплаты
     *
     * @param Carbon $date
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function pay(Carbon $date)
    {
        if (!$this->isOrdered()) {
            throw new \DomainException('Banner is not ordered');
        }

        $this->update([
            'published_at' => $date,
            'status'       => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Получить ширину баннера
     *
     * @return mixed
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getWidth()
    {
        return explode('x', $this->format)[0];
    }

    /**
     * Получить высоту баннера
     *
     * @return mixed
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getHeight()
    {
        return explode('x', $this->format)[1];
    }

    /**
     * Получим src баннера для подстановки в html img
     *
     * @return string
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getSrc(): string
    {
        return Storage::url($this->file);
    }

    /**
     * Увеличиваем просмотры
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function view()
    {
        $this->assertIsActive();

        $this->views++;
        if ($this->views >= $this->limit) {
            $this->status = self::STATUS_CLOSED;
        }

        $this->save();
    }

    /**
     * Увеличиваем клики
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function click()
    {
        $this->assertIsActive();

        $this->clicks++;

        $this->save();
    }

    /**
     * Проверка, что баннер имеет статус черновик
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * Проверка, что баннер имеет статус на модерации
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    /**
     * Проверка, что баннер имеет статус проверен
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isModerated(): bool
    {
        return $this->status === self::STATUS_MODERATED;
    }

    /**
     * Проверка, что баннер имеет статус оплачен
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isOrdered(): bool
    {
        return $this->status === self::STATUS_ORDERED;
    }

    /**
     * Проверка, что баннер имеет статус активен
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Проверка что баннер имеет статус закрыт
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Связь с пользователем
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Связь с категорией
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Связь с регионом
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
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
     * Скоуп для получения активных объявлений
     *
     * @param Builder $query
     *
     * @return Builder
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    private function assertIsActive(): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('Banner is not active.');
        }
    }
}
