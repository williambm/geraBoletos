<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoletoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;  //tem que colocar true pq senão tem que implementar acl e não sei fazer isso ainda !!!
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nomeEvento'            =>'required',
            'obsLegal'              =>'required | min:10',
            'iniDataPublicacao'     =>'required',
            'fimDataPublicacao'     =>'required',
            'instrObjCobranca'      =>'required | min:5',
            'estrutHierarq'         =>'required',
            'codFonteRecurso'       =>'required',
            'codUnidade'            =>'required',
            'nomeFonte'             =>'required',
            'nomeSubFonte'          =>'required',
            'dataVenc'              =>'required',
            'valor'                 =>'required',
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
