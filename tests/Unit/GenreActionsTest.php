<?php

namespace Tests\Unit;

use App\Actions\SaveGenreAction;
use App\Models\Genre;

it('saves a new genre', function () {
    $data = [
        'name' => 'Genre Name',
    ];
    $genre = (new SaveGenreAction())->execute(new Genre(), $data);
    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => $genre->name,
        'slug' => $genre->slug,
        'color' => $genre->color,
    ]);
});

it('updates an existing genre', function () {
    $genre = Genre::factory()->create();
    $data = [
        'name' => 'Genre Name',
        'color' => '#000000',
    ];
    $genre = (new SaveGenreAction())->execute($genre, $data);
    $this->assertDatabaseHas('genres', [
        'id' => $genre->id,
        'name' => $genre->name,
        'slug' => $genre->slug,
        'color' => $genre->color,
    ]);
});
