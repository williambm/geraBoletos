<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $fillable = ['codPes','nome','isGestor'];

    public function grupos()
    {
        return $this->belongsToMany(Grupo::class);
    }
}
