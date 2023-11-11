<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [ 'title', 'content', 'image_path', 'is_published', 'category_id' ];

    use HasFactory;

    public function category() {
        return $this->belongsTo( Category::class );
    }

    public function tag() {
        return $this->belongsToMany( Tag::class );
    }

}
