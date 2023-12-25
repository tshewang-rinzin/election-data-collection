<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CandidateRequest extends FormRequest
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
            'dzongkhag_id' => 'required|exists:dzongkhags,id',
            'constituency_id' => 'required|exists:constituencies,id',
            'party_id' => 'required|exists:parties,id',
            'name' => 'required|string|max:255',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
