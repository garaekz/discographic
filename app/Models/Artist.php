<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'name',
        'slug',
        'image',
        'region',
        'bio',
        'links',
    ];

    protected $casts = [
        'links' => 'array',
    ];

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }
}
