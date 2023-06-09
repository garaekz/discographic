<?php

namespace App\Http\Requests\Artist;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArtistRequest extends FormRequest
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
            'name' => 'sometimes|nullable|string|max:255|unique:artists,name,' . $this->artist->id,
            'image' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'links' => 'nullable|array',
            'links.*.name' => 'required|string|max:255',
            'links.*.url' => 'required|url|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'required|exists:genres,id',
        ];
    }
}
