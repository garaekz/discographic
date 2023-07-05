<?php

namespace App\Actions;

use App\Models\Album;
use Illuminate\Support\Str;

class SaveAlbumAction
{
    public function execute(Album $album, array $data): Album
    {
        if (isset($data['name'])) $data['slug'] = Str::slug($data['name']);
        $album->fill($data);
        $album->save();

        if (isset($data['tracks']) && is_array($data['tracks'])) {
            $album->tracks()->delete();

            foreach ($data['tracks'] as $track) {
                $album->tracks()->create([
                    'name' => $track['name'],
                    'slug' => Str::slug($track['name']),
                    'duration' => $track['duration'],
                    'order' => $track['order'],
                ]);
            }
        }

        return $album->refresh();
    }
}
