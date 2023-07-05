<?php

namespace App\Http\Controllers;

use App\Actions\SaveGenreAction;
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
    public function store(StoreGenreRequest $request, SaveGenreAction $action)
    {
        $action->execute(new Genre(), $request->validated());
        return redirect()->route('genres.index')->with('success', 'Genre created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        return Inertia::render('Genres/Show', [
            'genre' => $genre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGenreRequest $request, Genre $genre, SaveGenreAction $action)
    {
        $action->execute($genre, $request->validated());
        return redirect()->route('genres.index')->with('success', 'Genre updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect()->route('genres.index')->with('success', 'Genre deleted successfully');
    }
}
