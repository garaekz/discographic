<?php

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\Artist;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->artist = Artist::factory()->create();
});

it('renders the index page', function () {
    $this->actingAs($this->user)
        ->get(route('artists.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Artists/Index')
            ->has('filters')
            ->has(
                'artists.data',
                1,
                fn ($page) => $page
                ->where('id', $this->artist->id)
                ->where('name', $this->artist->name)
                ->where('slug', $this->artist->slug)
                ->etc()
            )
        );
});

it('creates a new artist', function () {
    $artist = Artist::factory()->make();

    $this->actingAs($this->user)
        ->post(route('artists.store'), $artist->toArray())
        ->assertRedirect(route('artists.index'))
        ->assertSessionHas('success', 'Artist created successfully');

    $this->assertDatabaseHas('artists', [
        'name' => $artist->name,
        'slug' => $artist->slug,
    ]);
});

it('shows an artist', function () {
    $this->actingAs($this->user)
        ->get(route('artists.show', $this->artist->id))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Artists/Show')
            ->has('artist')
            ->where('artist.id', $this->artist->id)
            ->where('artist.name', $this->artist->name)
            ->where('artist.slug', $this->artist->slug)
            ->etc()
        );
});

it('updates an artist', function () {
    $artist = Artist::factory()->make();

    $this->actingAs($this->user)
        ->put(route('artists.update', $this->artist->id), $artist->toArray())
        ->assertRedirect(route('artists.index'))
        ->assertSessionHas('success', 'Artist updated successfully');

    $this->assertDatabaseHas('artists', [
        'id' => $this->artist->id,
        'name' => $artist->name,
        'slug' => $artist->slug,
    ]);
});

it('deletes an artist', function () {
    $this->actingAs($this->user)
        ->delete(route('artists.destroy', $this->artist->id))
        ->assertRedirect(route('artists.index'))
        ->assertSessionHas('success', 'Artist deleted successfully');

    $this->assertSoftDeleted('artists', [
        'id' => $this->artist->id,
    ]);
});
