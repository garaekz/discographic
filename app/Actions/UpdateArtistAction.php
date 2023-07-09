<?php

namespace App\Actions;

use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateArtistAction
{
    public function execute(Artist $artist, array $data): Artist
    {
        if ($artist->image && isset($data['image'])) {
            Storage::disk('public')->delete($artist->image);
        }

        $artist->fill($data);

        if (!isset($data['slug'])) {
            $artist->slug = Str::slug($artist->name);
        }

        if (isset($data['bio'])) {
            $cleanBio = strip_tags($data['bio']);
            $artist->excerpt =  Str::limit($cleanBio, 80);
        }

        if (isset($data['image'])) {
            $path = $data['image']->store('images/artists', 'public');
            $artist->image = $path;
        }

        $artist->save();

        if (isset($data['genres'])) {
            $genres = Genre::whereIn('id', $data['genres'])->get();
            $artist->genres()->sync($genres);
        }

        return $artist;
    }
}
