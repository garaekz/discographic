<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use Inertia\Inertia;
use Spatie\QueryBuilder\QueryBuilder;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = QueryBuilder::for(Genre::class)
            ->allowedFilters(['name', 'slug'])
            ->allowedSorts(['name', 'slug'])
            ->allowedFields(['name', 'slug'])
            ->paginate();

        return Inertia::render('Genres/Index', [
            'filters' => request()->all('search', 'trashed'),
            'genres' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGenreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        //
    }
}
