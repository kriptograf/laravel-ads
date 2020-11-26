<?php

namespace App\Http\Requests\Admin\Attributes;

use App\Models\Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class CreateRequest
 *
 * @package App\Http\Requests\Admin\Attributes
 * @author  Виталий Москвин <foreach@mail.ru>
 */
class CreateRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'type'     => ['required', 'string', 'max:255', Rule::in(array_keys(Attribute::listTypes()))],
            'required' => 'required|integer',
            'variants' => 'nullable|string',
            'sort'     => 'required|integer',
        ];
    }
}
