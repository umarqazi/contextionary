<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteMeaning extends FormRequest
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
            'meaning' => 'required',
            'grammer' => 'required',
            'audience' => 'required',
            'part_of_speech' => 'required',
            'spelling' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'grammer.required' => 'This must be checked in order for voting',
            'audience.required'  => 'This must be checked in order for voting',
            'part_of_speech.required'  => 'This must be checked in order for voting',
            'spelling.required'  => 'This must be checked in order for voting',
            'meaning.required'  => 'Please select the meaning for voting',
        ];
    }
}
