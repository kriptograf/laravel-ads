<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Профиль пользователя
 *
 * @property integer $id
 * @property string  $first_name
 * @property string  $last_name
 * @property string  $location
 * @property string  $photo
 *
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Profile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profile';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'location',
        'photo'
    ];

    /**
     * Возвращает полное имя пользователя
     *
     * @return string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Связь с таблицей пользователя
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
