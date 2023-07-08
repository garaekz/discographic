<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\User;
use App\Models\Album;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->album = Album::factory()->create();
});

it('can create a new album', function () {
    $this->actingAs($this->user)
        ->post(route('albums.store'), [
            'name' => 'Test Album',
            'slug' => 'test-album',
            'description' => 'Test Description',
            'artist_id' => $this->album->artist_id,
            'image' => 'test.jpg',
            'release_date' => '2021-07-05',
            'tracks' => [
                [
                    'name' => 'Test Track',
                    'duration' => '3:00',
                    'order' => 1,
                ],
                [
                    'name' => 'Test Track 2',
                    'duration' => '3:00',
                    'order' => 2,
                ]
            ],
        ])
        ->assertRedirect(route('albums.index'))
        ->assertSessionHas('success', 'Album created successfully');

    $this->assertDatabaseHas('albums', [
        'name' => 'Test Album',
        'slug' => 'test-album',
        'description' => 'Test Description',
        'artist_id' => $this->album->artist_id,
        'image' => 'test.jpg',
        'release_date' => '2021-07-05',
    ]);

    $this->assertDatabaseHas('tracks', [
        'name' => 'Test Track',
        'slug' => 'test-track',
        'duration' => '3:00',
        'order' => 1,
    ]);
});

it('can update an existing album', function () {
    $this->actingAs($this->user)
        ->put(route('albums.update', $this->album), [
            'name' => 'Test Album',
            'slug' => 'test-album',
            'description' => 'Test Description',
            'artist_id' => $this->album->artist_id,
            'image' => 'test.jpg',
            'release_date' => '2021-07-05',
            'tracks' => [
                [
                    'name' => 'Test Track',
                    'duration' => '3:00',
                    'order' => 1,
                ],
                [
                    'name' => 'Test Track 2',
                    'duration' => '3:00',
                    'order' => 2,
                ]
            ],
        ])
        ->assertRedirect(route('albums.index'))
        ->assertSessionHas('success', 'Album updated successfully');

    $this->assertDatabaseHas('albums', [
        'name' => 'Test Album',
        'slug' => 'test-album',
        'description' => 'Test Description',
        'artist_id' => $this->album->artist_id,
        'image' => 'test.jpg',
        'release_date' => '2021-07-05',
    ]);

    $this->assertDatabaseHas('tracks', [
        'name' => 'Test Track',
        'slug' => 'test-track',
        'duration' => '3:00',
        'order' => 1,
    ]);
});

it('can delete an existing album', function () {
    $this->actingAs($this->user)
        ->delete(route('albums.destroy', $this->album))
        ->assertRedirect(route('albums.index'))
        ->assertSessionHas('success', 'Album deleted successfully');

    $this->assertSoftDeleted($this->album);
});

it('can view a list of albums', function () {
    $this->actingAs($this->user)
        ->get(route('albums.index'))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Albums/Index')
            ->has('filters')
            ->has(
                'albums.data',
                1,
                fn ($page) => $page
                ->where('id', $this->album->id)
                ->where('name', $this->album->name)
                ->where('slug', $this->album->slug)
                ->where('description', $this->album->description)
                ->where('artist_id', $this->album->artist_id)
                ->where('image', $this->album->image)
                ->where('release_date', $this->album->release_date)
                ->etc()
            )
        );
});

it('can show a single album', function () {
    $this->actingAs($this->user)
        ->get(route('albums.show', $this->album))
        ->assertInertia(
            fn (Assert $page) => $page
            ->component('Albums/Show')
            ->has(
                'album',
                fn ($page) => $page
                ->where('id', $this->album->id)
                ->where('name', $this->album->name)
                ->where('slug', $this->album->slug)
                ->where('description', $this->album->description)
                ->where('artist_id', $this->album->artist_id)
                ->where('image', $this->album->image)
                ->where('release_date', $this->album->release_date)
                ->etc()
            )
        );
});
