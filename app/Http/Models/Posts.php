<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function devs()
    {
        return $this->belongsTo('App\Http\Models\Devs', 'dev_id');
    }
}
