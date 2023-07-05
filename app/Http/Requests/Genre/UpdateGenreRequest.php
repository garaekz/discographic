<?php

namespace App\Http\Requests\Genre;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGenreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|nullable|string|max:255|unique:genres,name,' . $this->genre->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255|unique:genres,slug,' . $this->genre->id,
        ];
    }
}
