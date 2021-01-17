<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Значения дополнительных атрибутов объявления
 *
 * @property integer $advert_id
 * @property integer $attribute_id
 * @property string  $value
 * 
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class Value extends Model
{
    use HasFactory;

    protected $table = 'advert_values';

    /** @var null Поскольку у нас составной ключ, объявим первичный ключ null */
    protected $primaryKey = null;

    /** @var bool  Поскольку у нас составной ключ, отключим автоинкремент */
    public $incrementing = false;

    /** @var bool Сохранение даты нам не требуется */
    public $timestamps = false;

    protected $fillable = ['attribute_id', 'value'];
}
