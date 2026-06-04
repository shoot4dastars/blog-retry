<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    //
    protected $fillable = [
        'post_id',
        'user_id',
        'visitor_id'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
