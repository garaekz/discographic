<?php

namespace Tests\Feature;

use Inertia\Testing\AssertableInertia as Assert;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->user = User::factory()->create();
    Storage::fake('public');
    $image = UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(100)->store('images/artists', 'public');
    $this->artist = Artist::factory()->create([
        'image' => $image,
    ]);
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
    $genres = Genre::factory()->count(3)->create();
    $artist = Artist::factory()->make([
        'genres' => $genres->pluck('id')->toArray(),
    ]);
    $payload = $artist->toArray();
    $payload['image'] = UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(100);

    $this->actingAs($this->user)
        ->post(route('artists.store'), $payload)
        ->assertRedirect(route('artists.index'))
        ->assertSessionHas('success', 'Artist created successfully');

    $this->assertDatabaseHas('artists', [
        'name' => $artist->name,
        'slug' => $artist->slug,
        'image' => 'images/artists/' . $payload['image']->hashName(),
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
    $payload = $artist->toArray();
    $payload['image'] = UploadedFile::fake()->image('avatar.jpg', 400, 400)->size(100);

    $this->actingAs($this->user)
        ->put(route('artists.update', $this->artist->id), $payload)
        ->assertRedirect(route('artists.index'))
        ->assertSessionHas('success', 'Artist updated successfully');

    $this->assertDatabaseHas('artists', [
        'id' => $this->artist->id,
        'name' => $artist->name,
        'slug' => $artist->slug,
        'image' => 'images/artists/' . $payload['image']->hashName(),
    ]);

    Storage::disk('public')->assertExists('images/artists/' . $payload['image']->hashName());
    Storage::disk('public')->assertMissing($this->artist->image);
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
