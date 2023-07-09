<?php

namespace App\Http\Controllers;

use App\Actions\SaveArtistAction;
use App\Actions\UpdateArtistAction;
use App\Filters\SearchFilter;
use App\Http\Requests\Artist\StoreArtistRequest;
use App\Http\Requests\Artist\UpdateArtistRequest;
use App\Models\Artist;
use App\Models\Genre;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = QueryBuilder::for(Artist::class)
            ->allowedFilters(
                AllowedFilter::custom('search', new SearchFilter(), 'name,slug,region')
            )
            ->defaultSort('-id')
            ->with('genres')
            ->paginate();

        return Inertia::render('Artists/Index', [
            'filters' => request()->all('filter[search]'),
            'artists' => $data,
            'genres' => Genre::select('id', 'name')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArtistRequest $request, SaveArtistAction $action)
    {
        $action->execute(new Artist(), $request->validated());

        return redirect()->route('artists.index')->with('success', 'Artist created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        return Inertia::render('Artists/Show', [
            'artist' => $artist->load('genres'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArtistRequest $request, Artist $artist, UpdateArtistAction $action)
    {
        $artist = $action->execute($artist, $request->validated());

        return redirect()->route('artists.index')->with('success', 'Artist updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect()->route('artists.index')->with('success', 'Artist deleted successfully');
    }
}
