<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    public $table = 'posts';

    protected $guarded = [];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function bookmarked()
    {
        return $this->belongsToMany(User::class, 'bookmarked_posts', 'post_id', 'user_id');
    }

    protected function casts()
    {
        return [
            'photo_path' => 'array'
        ];
    }
}
