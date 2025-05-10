<?php

namespace App\Http\Requests\BaseLayout;

use Illuminate\Foundation\Http\FormRequest;

class StoreBaseLayoutRequest extends FormRequest
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
            'link'                  => ['string', 'required'],
            'name'                  => ['string', 'max:255', 'nullable'],
            'description'           => ['string', 'nullable'],
            'townHallLevel'         => ['integer', 'nullable'],
            'builderHallLevel'      => ['integer', 'nullable'],
            'baseType'              => ['required', 'string', 'in:townhall,builder'],
            'isWarBase'       => ['boolean', 'nullable'],
            'imageUrls'             => ['array', 'nullable'],
            'imageUrls.*'           => ['required', 'string'],

            // category
            'categoryIds'           => ['array', 'nullable'],
            'categoryIds.*'         => ['required', 'integer'],

            // tag
            'tagIds'                => ['array', 'nullable'],
            'tagIds.*'              => ['required', 'integer'],
        ];
    }
}
