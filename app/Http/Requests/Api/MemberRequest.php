<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
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
            "licence" => "string|required|unique:ACN_MEMBER,MEM_NUM_LICENCE|max:12",
            "name" => "string|required|max:64",
            "surname" => "string|required|max:64",
            "date_certification" => "date|required",
            "pricing" => "string|required|in:adulte,enfant",
            "password" => "string|required",
            "subdate" => "date|required",
        ];
    }
}
