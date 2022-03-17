<?php

namespace App\Http\Requests\Vote;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'event_id' => 'required|uuid',
            'voter_id' => ['required','exists:voters,id',Rule::unique('votes')->where(function ($query) {
                return $query->where('event_id', $this->event_id)->where('voter_id', $this->voter_id);
            })],
            'images' => 'filled|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'image_urls' => 'required_without:images|array',
            'image_urls.*' => 'url',
            'votes'=> 'required|json',
            'is_valid' => 'filled|boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'event_id' => $this->route('event'),
            'voter_id' => User::with('voters')->findOrFail(auth()->id())->voters()->where('event_id', $this->route('event'))->firstOrFail()->id,
        ]);
    }
}
