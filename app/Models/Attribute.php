<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Атрибуты категории для объявлений
 *
 * @property integer $id
 * @property integer $category_id
 * @property string  $name
 * @property string  $type
 * @property boolean $required
 * @property jsonb   $variants
 * @property integer $sort
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class Attribute extends Model
{
    use HasFactory;

    const TYPE_INTEGER = 'integer';
    const TYPE_STRING  = 'string';
    const TYPE_FLOAT   = 'float';

    protected $fillable = ['name', 'type', 'required', 'variants', 'sort', 'category_id'];

    protected $casts = ['variants' => 'array'];

    public static function listTypes()
    {
        return [
            static::TYPE_STRING  => 'String',
            static::TYPE_INTEGER => 'Integer',
            static::TYPE_FLOAT   => 'Float',
        ];
    }

    /**
     * Строка
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isString()
    {
        return static::TYPE_STRING === $this->type;
    }

    /**
     * Целое число
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isInteger()
    {
        return static::TYPE_INTEGER === $this->type;
    }

    /**
     * Число с точкой
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isFloat()
    {
        return static::TYPE_FLOAT === $this->type;
    }

    /**
     * Выпадающий список
     * @return bool
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function isSelect()
    {
        return count($this->variants) > 0;
    }
}
