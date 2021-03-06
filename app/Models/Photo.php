<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Фотографии объявления
 *
 * @property integer $id
 * @property integer $advert_id
 * @property string  $file
 * 
 * @author Виталий Москвин <foreach@mail.ru>
 */
class Photo extends Model
{
    use HasFactory;

    protected $table = 'advert_photos';

    public $timestamps = false;

    protected $fillable = ['file'];

    /**
     * Получить url фото
     * @return string
     * @author Виталий Москвин <foreach@mail.ru>
     */
    public function getUrl(): string
    {
        return Storage::url($this->file);
    }
}
