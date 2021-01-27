<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User
 *
 * @property integer $id
 * @property string  $name
 * @property string  $email
 * @property string  $email_verified_at
 * @property string  $password
 * @property string  $remember_token
 * @property string  $created_at
 * @property string  $updated_at
 * @property string  $phone
 * @property boolean $phone_verified
 * @property string  $phone_verify_token
 * @property Carbon  $phone_verify_token_expire
 *
 * @property Profile $profile
 *
 * @method User findOrFail(int $id)
 *
 * @package App\Models
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Атрибуты для массового присваивания
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Атрибуты, которые следует приводить к собственным типам.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verify_token_expire' => 'datetime',
    ];

    /**
     * Верификация пользователя вручную
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function setVerified()
    {
        $this->email_verified_at = $this->freshTimestamp();
        return $this->save();
    }

    /**
     * Проверка верифецирован пользователь или нет
     *
     * @return bool
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function hasVerified()
    {
        return $this->hasVerifiedEmail();
    }

    /**
     * Поиск пользователя по email
     *
     * @param $email
     *
     * @return mixed
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public static function findByEmail($email)
    {
        return self::where('email', $email)->first();
    }

    /**
     * Поиск и фильтрация пользователей в админке
     *
     * @param Request $request
     *
     * @return mixed
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public static function search(Request $request)
    {
        $query = self::orderBy('id', 'desc');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }

        // -- Пробуем фильтровать роли
        if (!empty($value = $request->get('role'))) {
            $query->role([$value]);
        }

        return $query->paginate(20);
    }

    /**
     * Проверка, что телефон подтвержден
     *
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isPhoneVerified()
    {
        return $this->phone && $this->phone_verified;
    }

    /**
     * Верификация телефона
     *
     * @param string $token
     * @param Carbon $now
     *
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function verifyPhone($token, Carbon $now)
    {
        if ($token !== $this->phone_verify_token) {
            throw new \DomainException('Incorrect verify token.');
        }

        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
        }

        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    /**
     * Запрос на верификацию телефона
     *
     * @param Carbon $now
     *
     * @return string
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function requestPhoneVerification(Carbon $now)
    {
        if (empty($this->phone)) {
            throw new \DomainException('Phone number is empty.');
        }

        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Token is already requested.');
        }

        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(300);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    /**
     * Сброс верефикации телефона
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function clearPhoneVerification()
    {
        $this->unverifyPhone();
    }

    /**
     * Сбросить флаг верифицированного телефона
     * @throws \Throwable
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function unverifyPhone()
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    /**
     * Проверка, что профиль пользователя запонен и телефон верифицирован
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function hasFilledProfile()
    {
        return $this->profile->first_name && $this->profile->last_name && $this->profile->location && $this->isPhoneVerified();
    }

    /**
     * Проверка , что объявление находится в избранном
     *
     * @param integer $id
     *
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function hasInFavorites($id): bool
    {
        return $this->favorites()->where('id', $id)->exists();
    }

    /**
     * Добавить объявление в избранное
     *
     * @param integer $advertId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function addToFavorites($advertId)
    {
        if ($this->hasInFavorites($advertId)) {
            throw new \DomainException('This advert is already added to favorite');
        }

        $this->favorites()->attach($advertId);
    }

    /**
     * Удалить объявление из избранного
     *
     * @param integer $advertId
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function removeFromFavorites($advertId)
    {
        $this->favorites()->detach($advertId);
    }

    /**
     * Связь с таблицей профиля
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    /**
     * Связь с избранными объявлениями
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function favorites()
    {
        return $this->belongsToMany(Advert::class, 'favorites', 'user_id', 'advert_id');
    }
}
