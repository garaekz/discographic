<?php

use App\Models\Genre;

it('returns genres table', function () {
    $genres = Genre::factory()->count(3)->create();

    $response = $this->get('/genres');

    $response->assertStatus(200)
        ->assertJson($genres->toArray());
});
