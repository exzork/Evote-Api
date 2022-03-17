<?php

namespace App\Http\Requests\Voter;

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
            'event_id' => 'required|uuid|exists:events,id',
            'email' => 'required|email|unique:voters,email,NULL,id,event_id,' . $this->get('event_id'),
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'event_id' => $this->route('event'),
        ]);
    }
}
