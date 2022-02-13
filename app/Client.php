<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];

    public function modpacks()
    {
        return $this->belongsToMany(Modpack::class)->withTimestamps();
    }
}
