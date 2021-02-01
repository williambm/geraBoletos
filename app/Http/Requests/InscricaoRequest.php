<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscricaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome'          =>'required',
            'cpf'           =>'required',
            'email'         =>'required',
            'telefone'      =>'required',
            'cep'           =>'required',
            'cidade'        =>'required',
            'uf'            =>'required',
            'endereco'      =>'required',
            'numEndereco'   =>'required',
        ];
    }
    public function messages()
    {
        return[
            'required'  =>"Este campo é obrigatório",
            'min'       =>"Este campo deve ter no mínimo :min caracteres",
        ];        
    }
}
