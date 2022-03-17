<?php

namespace App\Http\Requests\Committee;

use App\Models\Committee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
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
            'email' => 'required|email|exists:users,email',
            'position' => 'filled|string|max:255',
            'access_level' => ['filled','integer',Rule::in([Committee::ACCESS_READ,Committee::ACCESS_WRITE,Committee::ACCESS_ADMIN])],
        ];
    }
}
