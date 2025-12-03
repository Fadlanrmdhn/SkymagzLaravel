<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'description',
    ];

    public function magazines()
    {
        return $this->belongsToMany(Magazine::class, 'magazine_categories');
    }
}
