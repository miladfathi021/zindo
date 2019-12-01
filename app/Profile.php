<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['owner_id', 'bio', 'avatar'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
