<?php

namespace App\Actions;

use App\Models\Genre;
use Illuminate\Support\Str;

class SaveGenreAction
{
    public function execute(Genre $genre, array $data): Genre
    {
        if (!isset($data['color'])) {
            $color = dechex(rand(0x000000, 0xFFFFFF));
            $data['color'] = '#' . $color;
        }
        if (!isset($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $genre->fill($data);
        $genre->save();

        return $genre->refresh();
    }
}
