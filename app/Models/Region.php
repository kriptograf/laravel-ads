<?php

namespace App\Models;

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
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class Region extends Model
{
    use HasFactory;

    /**
     * @var array $fillable Fields for mass assigned
     */
    protected $fillable = ['parent_id', 'name', 'slug'];

    /**
     * Relation to parent region
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * Relation to children region
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
