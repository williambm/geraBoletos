<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    protected $fillable = 
    [
        'nomeEvento',
        'codFonteRecurso',
        'nomeFonte',
        'nomeSubFonte',
        'estrutHierarq',
        'codConvenio',
        'dataVenc',
        'valor',
        'desconto',
        'infoSacado',
        'instrObjCobranca',
        'obsLegal',
        'iniDataPublicacao',
        'fimDataPublicacao',
        'codUnidade',
        'isPublicado',
        'limQtdeInscritos',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class); 
    }

    public function consumidores()
    {
        return $this->hasMany(Consumidor::class); 
    }


}
