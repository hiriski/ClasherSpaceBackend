<?php

namespace App\Http\Requests\BaseLayout;

use Illuminate\Foundation\Http\FormRequest;

class RequestParamsGetBaseLayout extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'baseType'              => ['required', 'string', 'in:townhall,builder'],
            'query'                 => ['string', 'nullable'],
            'townHallLevel'         => ['string', 'nullable'],
            'builderHallLevel'      => ['string', 'nullable'],
            'markedAsWarBase'       => ['boolean', 'nullable']
        ];
    }
}
