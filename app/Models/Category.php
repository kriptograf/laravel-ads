<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Category
 *
 * @property integer $id
 * @property string  $name
 * @property string  $slug
 * @property integer $parent_id
 *
 * @property int $depth
 * @property Category $parent
 * @property Category[] $children
 * @property Attribute[] $attributes
 *
 * @package App\Models
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class Category extends Model
{
    use HasFactory, NodeTrait;

    /**
     * @var array $fillable Fields for mass assigned
     */
    protected $fillable = ['parent_id', 'name', 'slug'];

    public function getPath()
    {
        return implode('/', array_merge($this->ancestors()->defaultOrder()->pluck('slug')->toArray(), [$this->slug]));
    }

    /**
     * Связь с атрибутами категории
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    /**
     * Получить атрибуты родительских категорий
     *
     * @return array
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getParentAttributes()
    {
        return $this->parent ? $this->parent->getAllAttributes() : [];
    }

    /**
     * Все атрибуты категории включая родительские
     *
     * @return \App\Models\Attribute[]
     *
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getAllAttributes()
    {
        return array_merge($this->getParentAttributes(), $this->attributes()->orderBy('sort')->getModels());
    }
}
