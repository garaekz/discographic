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

it('creates a new genre', function () {
    $user = User::factory()->create();
    $this->actingAs($user)
        ->post(route('genres.store'), [
            'name' => 'Genre Name',
            'slug' => 'genre-name',
        ])
        ->assertRedirect(route('genres.index'))
        ->assertSessionHas('success', 'Genre created successfully');
    $this->assertDatabaseHas('genres', [
        'name' => 'Genre Name',
        'slug' => 'genre-name',
    ]);
});

it('shows a genre', function () {
    $genre = Genre::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user)
        ->get(route('genres.show', $genre->id))
        ->assertOk()
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Genres/Show')
            ->has(
                'genre',
                fn (Assert $page) => $page
                ->where('id', $genre->id)
                ->where('name', $genre->name)
                ->where('slug', $genre->slug)
                ->etc()
            )
        );
});

it('updates a genre', function () {
    $genre = Genre::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user)
        ->put(route('genres.update', $genre->id), [
            'name' => 'New Genre Name',
            'slug' => 'new-genre-name',
        ])
        ->assertRedirect(route('genres.index'))
        ->assertSessionHas('success', 'Genre updated successfully');
    $this->assertDatabaseHas('genres', [
        'name' => 'New Genre Name',
        'slug' => 'new-genre-name',
    ]);
});

it('deletes a genre', function () {
    $genre = Genre::factory()->create();
    $user = User::factory()->create();
    $this->actingAs($user)
        ->delete(route('genres.destroy', $genre->id))
        ->assertRedirect(route('genres.index'))
        ->assertSessionHas('success', 'Genre deleted successfully');
    $this->assertSoftDeleted('genres', [
        'id' => $genre->id,
    ]);
});
