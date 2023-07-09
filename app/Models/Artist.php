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

    protected $appends = [
        'image_url',
    ];

    protected $casts = [
        'links' => 'array',
    ];

    // accessors
    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('storage/' . $this->image)
            : 'https://ui-avatars.com/api/?name='.$this->name.'&color=7F9CF5&background=EBF4FF';
    }

    public function albums(): MorphToMany
    {
        return $this->morphToMany(Album::class, 'albumable');
    }

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }
}
