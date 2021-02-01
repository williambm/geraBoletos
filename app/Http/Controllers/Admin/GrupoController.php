<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Grupo;
use App\Pessoa;

class GrupoController extends Controller
{
    
    private $grupos;

    public function __construct(Grupo $grupos)
    {
        $this->grupos = $grupos;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Pega todos os grupos existentes e lista para o Gestor / do administrador tratarei com o adminController
        $grupos = $this->grupos->paginate(7);
        return view('admin.grupo.index',compact('grupos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.grupo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //pega tudo da request http
        $data = $request->all();
        //Grava no BD
        $this->grupos->create($data);

        flash('Grupo Criado com Sucesso!')->success();
        return redirect()->route('admin.grupo.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $grupoID
     * @return \Illuminate\Http\Response
     */
    public function edit($grupoID)
    {
        //Procura o Grupo para edição
        $grupo = $this->grupos->findOrFail($grupoID);
        //Busca as pessoas que tem permissão neste grupo        
        $permitidos = $grupo->pessoas()->get(['id','codPes','nome']);
        //dd($permitidos);
        //retorna para a view de edição o grupo e as pessoas que já tem permissão
        return view('admin.grupo.edit',compact('grupo','permitidos'));
    }

    /**
     * Update apenas dados básicos do grupo como nome e descrição.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $grupoID
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $grupoID)
    {
        $data = $request->all();
        $grupo = $this->grupos->find($grupoID);
        $grupo->update($data);
        flash('Dados do Grupo Atualizado com Sucesso!')->success();
        return redirect()->route('admin.grupo.index');
    }

    /**
     * removePermission retira permissão de pessoa a um grupo
     * @param int $grupoID $pessoaID
     */
     public function removePermission($grupoID , $pessoaID)
     {
        $grupo = $this->grupos->find($grupoID);
        //Busca as pessoas que tem permissão neste grupo        
        $permitidos = $grupo->pessoas()->get(['id']);
        //variavel que é usada como array de id de pessoas
        $arrayPessoaPermitida; 
        //Cria um array com todas as permissões de pessoas a este grupo       
        foreach($permitidos as $permitido)
        {
            $arrayPessoaPermitida[] = $permitido->id;
        }
        //Remove do array de permissões apenas a desejada vindo do Edit
        $arrayPessoaPermitida = array_diff($arrayPessoaPermitida, (array)$pessoaID);
        //Faz o sync na tabela pivo atualizando a permissão do grupo com apenas os ids que fazem parte dele
        $grupo->pessoas()->sync($arrayPessoaPermitida); // para salvar na tabela pivo relação N:N - passa-se um array de IDs
        return redirect()->route('admin.grupo.index');
     }

     /**
     * addPermission retira permissão de pessoa a um grupo
     * @param  \Illuminate\Http\Request  $request
     * @param int $grupoID
     */
    public function addPermission(Request $request, $grupoID)
    {
        $data = $request->all();
        //Busca Grupo
        $grupo = $this->grupos->find($grupoID);
        //Busca Pessoa para validar se já tem vinculo com o sistema
        $pessoa = new Pessoa();        
        $pessoa = $pessoa->where('codPes',$data["codPes"])->get();        
        //Se a pessoa não tem vínculos com esse sistema cria a pessoa e continua a rotina de permissão senão só atribui permissão ao grupo
        if($pessoa->count() == 0)
        {            
            //Tem que criar um novo objeto do tipo pessoa pois o anterior foi sobrescrito e virou um array
            $persistePessoa = new Pessoa();
            $persistePessoa->nome = $data['nomePessoa'];
            $persistePessoa->codPes = $data['codPes'];
            $persistePessoa->isGestor = "nao";
            $persistePessoa->save();            

            //Busca novamente a pessoa devido a ser uma pessoa nova o array antes da linha abaixo vinha zerado
            $pessoa = $persistePessoa->where('codPes',$data["codPes"])->get();
            //Busca as pessoas que tem permissão neste grupo        
            $permitidos = $grupo->pessoas()->get(['id']);
            //variavel que é usada como array de id de pessoas
            $arrayPessoaPermitida; 
            //Cria um array com todas as permissões de pessoas a este grupo       
            foreach($permitidos as $permitido)
            {
                $arrayPessoaPermitida[] = $permitido->id;
            }       
            //Adiciona ao array de permissões o ID da nova pessoa
            $arrayPessoaPermitida[] = $pessoa[0]->id;       
            //Faz o sync na tabela pivo atualizando a permissão do grupo com apenas os ids que fazem parte dele
            $grupo->pessoas()->sync($arrayPessoaPermitida); // para salvar na tabela pivo relação N:N - passa-se um array de IDs
            return redirect()->route('admin.grupo.index');
        }else{ 
            //Busca as pessoas que tem permissão neste grupo        
            $permitidos = $grupo->pessoas()->get(['id']);
            //variavel que é usada como array de id de pessoas
            $arrayPessoaPermitida; 
            //Cria um array com todas as permissões de pessoas a este grupo       
            foreach($permitidos as $permitido)
            {
                $arrayPessoaPermitida[] = $permitido->id;
            }       
            //Adiciona ao array de permissões o ID da nova pessoa
            $arrayPessoaPermitida[] = $pessoa[0]->id;       
            //Faz o sync na tabela pivo atualizando a permissão do grupo com apenas os ids que fazem parte dele
            $grupo->pessoas()->sync($arrayPessoaPermitida); // para salvar na tabela pivo relação N:N - passa-se um array de IDs
            return redirect()->route('admin.grupo.index');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
