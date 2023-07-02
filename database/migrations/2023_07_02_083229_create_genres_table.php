<?php

use App\Models\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')
              ->unique();
            $table->string('color');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('genreables', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Genre::class);
            $table->morphs('genreable');
            $table->timestamps();

            $table->unique(['genre_id', 'genreable_id', 'genreable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genreables');
        Schema::dropIfExists('genres');
    }
};
