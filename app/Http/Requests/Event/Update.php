<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'filled|string|max:255',
            'description' => 'filled|string',
            'image' => 'filled|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'image_url' => 'filled|string|max:255',
            'start_date' => 'filled|date|after:today',
            'end_date' => 'filled|date|after:start_date',
        ];
    }
}
