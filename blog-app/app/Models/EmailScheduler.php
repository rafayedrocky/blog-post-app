<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailScheduler extends Model
{
    use HasFactory;


    protected $table = 'email_scheduler';
    public $timestamps = true;

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'title', 'body', 'is_send', 'email_send_time'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

