<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchClanRequest extends FormRequest
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
            'name'              => ['required', 'string'],
            'locationId'        => ['nullable', 'integer'],
            'minMembers'        => ['nullable', 'integer'],
            'maxMembers'        => ['nullable', 'integer'],
            'minClanPoints'     => ['nullable', 'integer'],
            'minClanLevel'      => ['nullable', 'integer'],
            'limit'             => ['nullable', 'integer'],
            'after'             => ['nullable', 'integer'],
            'before'            => ['nullable', 'integer'],
            'labelIds'          => ['nullable', 'string']
        ];
    }
}
