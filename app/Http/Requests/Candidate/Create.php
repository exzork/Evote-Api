<?php

namespace App\Http\Requests\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
            'leader_email' => 'required|email|exists:users,email',
            'vice_leader_email' => 'required|email|exists:users,email',
            'description' => 'required|string',
            'image' => 'filled|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'image_url' => 'required_without:image|url',
        ];
    }
}
