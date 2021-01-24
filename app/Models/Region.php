<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer  $id
 * @property int|null $parent_id
 * @property string   $name
 * @property string   $slug
 *
 * @property Region   $parent
 * @property Region[] $children
 *
 * @method Builder roots()
 *
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class Region extends Model
{
    use HasFactory;

    /**
     * @var array $fillable Fields for mass assigned
     */
    protected $fillable = ['parent_id', 'name', 'slug'];

    public function getPath()
    {
        return ($this->parent ? $this->parent->getPath() . '/' : '') . $this->slug;
    }

    /**
     * Получаем склеенный адрес для подстановки
     * Адрес склеиваем рекурсивно от дочернего к родительскому
     *
     * @return string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getAddress(): string
    {
        return ($this->parent ? $this->parent->getAddress() . ', ' : '') . $this->name;
    }

    /**
     * Связь с родительскими регионами
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * Связь с дочерними регионами
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * Скоуп для получения только родительских регионов
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function scopeRoots(Builder $query)
    {
        return $query->where('parent_id', null);
    }
}
