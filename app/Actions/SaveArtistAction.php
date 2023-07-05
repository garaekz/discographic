<?php

namespace App\Actions;

use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Support\Str;

class SaveArtistAction
{
    public function execute(Artist $artist, array $data): Artist
    {
        $artist->slug = Str::slug($data['name']);
        $artist->fill($data);
        $artist->save();

        if (isset($data['genres'])) {
            $genres = Genre::whereIn('id', $data['genres'])->get();
            $artist->genres()->sync($genres);
        }

        return $artist;
    }
}
