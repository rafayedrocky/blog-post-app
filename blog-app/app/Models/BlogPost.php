<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;


    protected $table = 'blog_posts';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'title', 'content', 'author_id', 'updated_by'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

