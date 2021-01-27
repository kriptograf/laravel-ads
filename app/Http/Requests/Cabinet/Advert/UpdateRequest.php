<?php

namespace App\Http\Requests\Cabinet\Advert;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Запрос редактирования объявления
 *
 * @property \App\Models\Category $category
 * @property \App\Models\Region $region
 * @todo Для динамических атрибутов добавить вменяемые сообщения валидации
 * @author Виталий Москвин <foreach@mail.ru>
 */
class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $items = [];

        return array_merge([
            'title'   => 'required|string',
            'content' => 'required|string',
            'price'   => 'required|integer',
            'address' => 'required|string'
        ], $items);
    }
}
