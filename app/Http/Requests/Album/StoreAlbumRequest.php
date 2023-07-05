<?php

namespace App\Http\Requests\Album;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlbumRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'artist_id' => 'required|integer',
            'image' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'tracks' => 'nullable|array',
            'tracks.*.name' => 'required|string|max:255',
            'tracks.*.duration' => 'nullable|string|max:255',
            'tracks.*.order' => 'required|integer',
        ];
    }
}
