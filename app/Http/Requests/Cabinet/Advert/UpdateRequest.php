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

        // -- Добавляем валидацию динамических атрибутов объявления
        /*foreach ($this->category->getAllAttributes() as $attribute) {
            $rules = [
                $attribute->required ? 'required' : 'nullable',
            ];
            if ($attribute->isInteger()) {
                $rules[] = 'integer';
            } elseif ($attribute->isFloat()) {
                $rules[] = 'numeric';
            } else {
                $rules[] = 'string';
                $rules[] = 'max:255';
            }

            if ($attribute->isSelect()) {
                $rules[] = Rule::in($attribute->variants);
            }

            $items['attributes.' . $attribute->id] = $rules;

        }*/

        return array_merge([
            'title'   => 'required|string',
            'content' => 'required|string',
            'price'   => 'required|integer',
            'address' => 'required|string'
        ], $items);
    }
}
