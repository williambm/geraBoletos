<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = ['nome','descricao'];

    public function pessoas()
    {
        return $this->belongsToMany(Pessoa::class);
    }

    public function boletos()
    {
        return $this->hasMany(Boleto::class);
    }
}
