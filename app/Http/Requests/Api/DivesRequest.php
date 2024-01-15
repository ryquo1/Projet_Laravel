<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class DivesRequest extends FormRequest
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
            "date" => "date|required",
            "period" => "integer|numeric|required",
            "min_registered" => "integer|numeric",
            "max_registered" => "integer|numeric",
            "observation" => "observation|max:4096",
            "boat" => "integer|numeric",
            "site" => "integer|numeric",
            "surface_security" => "integer|numeric",
            "leader" => "integer|numeric",
            "pilot" => "integer|numeric",
        ];
    }
}
