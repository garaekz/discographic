<?php

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\Genre;
use App\Models\User;

it('returns genres table', function () {
    $genres = Genre::factory()->count(3)->create();
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('genres.index'))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Genres/Index')
            ->has(
                'genres.data',
                3,
                fn (Assert $page) => $page
                ->where('id', $genres->first()->id)
                ->where('name', $genres->first()->name)
                ->where('slug', $genres->first()->slug)
                ->etc()
            )
        );
});
