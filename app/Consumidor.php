<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumidor extends Model
{
    //Especifica o nome da tabela senão da problema com o plural em inglês
    protected $table = 'consumidores';

    protected $fillable = 
    [
        'id',
        'codPes',
        'nome',
        'cpf',
        'cep',
        'endereco',
        'numEndereco',
        'complEndereco',
        'cidade',
        'uf',
        'email',
        'telefone',
        'nomeEmpresaInstituicao',
        'cnpjEmpresaInstituicao',
        'tipoSacado',
        'rastreioBoleto_id',
        'statusPGTO',
    ];

    public function boleto()
    {
        return $this->belongsTo(Boleto::class); 
    }

}
