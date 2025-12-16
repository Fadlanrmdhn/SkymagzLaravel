<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magazine extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'author',
        'publisher',
        'type',
        'cover',
        'description',
        'price',
        'release_date'
    ];

    // Potong author jadi 2 kata
    public function getShortAuthorAttribute()
    {
        return Str::words($this->author, 2, '...');
    }

    // Potong title jadi 2 kata
    public function getShortTitleAttribute()
    {
        return Str::words($this->title, 2, '...');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'magazine_categories');
    }
}
