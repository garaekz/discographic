<?php

namespace App\Http\Controllers;

use App\Actions\SaveAlbumAction;
use App\Http\Requests\Album\StoreAlbumRequest;
use App\Http\Requests\Album\UpdateAlbumRequest;
use App\Models\Album;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = QueryBuilder::for(Album::class)
            ->allowedFilters(['name', 'slug'])
            ->allowedSorts(['name', 'slug'])
            ->allowedFields(['name', 'slug'])
            ->paginate();

        return Inertia::render('Albums/Index', [
            'filters' => request()->all('search', 'trashed'),
            'albums' => $data,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request, SaveAlbumAction $action)
    {
        $action->execute(new Album(), $request->validated());

        return redirect()->route('albums.index')->with('success', 'Album created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        return Inertia::render('Albums/Show', [
            'album' => $album->load('artist', 'tracks'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, Album $album, SaveAlbumAction $action)
    {
        $action->execute($album, $request->validated());

        return redirect()->route('albums.index')->with('success', 'Album updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return redirect()->route('albums.index')->with('success', 'Album deleted successfully');
    }
}
