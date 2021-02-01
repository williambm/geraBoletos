<?php

namespace App\Http\Controllers\gestor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pessoa;

class GestorController extends Controller
{
    public function index()
    {
        //Busca todas pessoas que tem isGestor = sim
        //Como não é eloquent aqui mas sim query builder tem que trazer paginado do bd
        $gestores = DB::table('pessoas')->where('isGestor',"sim")->simplePaginate(7);        
        //dd($gestores);

        return view('gestor.index',compact('gestores'));
    }
    
    // Vai para a View de Criação
    public function create()
    {
        return view('gestor.create');
    }

    //Cria regirstro no BD
    public function store(Request $request)
    {
        //Pega todos os dados da Request HTTP
        $data = $request->all(); 
        //Cria e instancia objeto pessoa setando como gestor
        $pessoa = new Pessoa();
        $pessoa->codPes = $data['codPes'];
        $pessoa->nome = $data['nome'];
        $pessoa->isGestor = "sim";

        //Verifica se a Pessoa já existe na tabela Pessoas deste sistema
        //Se sim atualiza do sistema, senão cria nova pessoa...
        $verificaSeExiste = $pessoa->where('codPes',$pessoa->codPes)->count();        
        if($verificaSeExiste!=0) 
        {
            $pessoa->where('codPes',$pessoa->codPes)->update(['isGestor'=>'sim']);
        }
        else{
            //Como estou trabalhando diretamente com o objeto uso o save()
            //Armazena
            $pessoa->save();
        }
        // Retorna msg de sucesso 
        flash('Permissão concedida com Sucesso!')->success();   
        return redirect()->route('gestor.index');
    }

    //Busca automaticamente Pessoa pelo Número USP
    public function liveSearch (Request $request)
    {
        
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::connection("eebase")->select('SELECT *
            FROM EEBASE.dbmaint.pessoa where codpes ='.$query);                        
            $data = $data[0]->nompes;
            echo $data;
        }
        
    }

    //Editar um gestor
    public function edit($gestor)
    {
        //Pega o gestor pelo id vindo da index de gestores, vem em array
        $gestor = DB::table('pessoas')->where('id',$gestor)->get();
        
        //Passar para objeto Pessoa, como não tem eloquent aqui to fazendo na mão
        $pessoa = new Pessoa();
        //dd($gestor[0]->codPes);
        $pessoa->codPes     = $gestor[0]->codPes;
        $pessoa->nome       = $gestor[0]->nome;
        $pessoa->isGestor   = $gestor[0]->isGestor;
        //pessoaID é parte da mecânica para realizar o update na base 
        $pessoaID           = $gestor[0]->id;
        //dd($pessoaID);
        return view('gestor.edit',compact('pessoa','pessoaID'));
    }

    //Atualiza registro no BD
    public function update(Request $request, $pessoaID)
    {
        //Capta os dados vindo de request
        $data = $request->all();
        //dd($data);
        //Busca pelo ID o registro a ser atualizado
        $pessoa = new Pessoa();
        $pessoa = $pessoa->find($pessoaID);
        $pessoa->update($data);
        flash('Gestor Atualizado com Sucesso!')->success();
        return redirect()->route('gestor.index');
    }

    //remove permissão
    public function removePermission($pessoaID)
    {        
        $pessoa = new Pessoa();
        $pessoa = $pessoa->find($pessoaID);
        $pessoa->isGestor = 'nao';
        $pessoa->update();
        flash('Permissão Revogada com Sucesso!')->success();
        return redirect()->route('gestor.index');
    }
    
}
