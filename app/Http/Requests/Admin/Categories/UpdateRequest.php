<?php

namespace App\Http\Requests\Admin\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

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
        return [
            'name'      => 'required|string|max:255|unique:regions,name,' . $this->category->id. ',id,parent_id,' . ($this->parent_id ?: 'NULL'),
            'slug'      => 'required|string|max:255|unique:regions,slug,' . $this->category->id. ',id,parent_id,' . ($this->parent_id ?: 'NULL'),
            'parent_id' => 'nullable|exists:regions,id'
        ];
    }

    /**
     * @author Виталий Москвин <foreach@mail.ru>
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }
}
