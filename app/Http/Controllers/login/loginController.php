<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Pessoa;
use App\Grupo;

class loginController extends Controller
{
    //Chama a VIEW que faz regra de negócio de login oauth 
    //XXX:Sei que não é o correto mas para arrumar isso deve ser feito configurações de callback ID do dev que não possuo acesos ao sistema..
    public function index()
    {
        return view('login.index');
    }

    //este método verifica o Login, faz algumas validações
    //TO-DO: está apenas o básico.. tem ou não senha única .. depois tem que ser feito verificações de tipo de usuario
    public function verificaLogin()
    {
        //Verifica se login deu certo e se gerou o cookie que está programado na login/index.blade
        //XXX: Posso aqui depois pegar esse $statusLogin e colocar como validação de perfis criando flags booleanas para cada ou redirecionando views
        // $statusLogin;    ---->acho qu vai cair fora essas parada todos comentados ver isso
        $msgLogin;
        if(isset ($_COOKIE['loginUSP']))
        {
            //$statusLogin = true;            
            $JSONCookie=unserialize($_COOKIE['loginUSP']);//Cookie fica no formato JSON            
            $loginDadosOBJ=json_decode($JSONCookie); //Passa para o formato de Objeto para ser manipilado mais fácil

            //Pesquisa as permissões dessa pessoa, até agora só se ela é Gestora ou não. Depois tem que trazer os grupos se ela tiver tb..
            $pessoa = new Pessoa();
            $pessoa = $pessoa->where('codPes',$loginDadosOBJ->loginUsuario)->get();
            //Ve se a pessoa já tem algum cadastro com esse sistema            
            $contaPessoa = $pessoa->where('codPes',$loginDadosOBJ->loginUsuario)->count();

            //Verifica se a pessoa já está no BD deste sistema se não entra no if se já tem cadastro entra no else, feito isso por causa do erro do offset 0 
            if($pessoa->count() == 0)
            {
                //Como não tem vinculo algum, zeramos os verificadores e isGestor = nao
                $contaGrupo = 0;                
                $isGestor = "nao";
            }else{
                //Se entrou aqui é pq a pessoa tem algum vínculo com o sistema
                //Ve se a pessoa já tem algum grupo de trabalho - conta quantos grupos ela tem
                $contaGrupo = $pessoa[0]->grupos()->count();
                //Ve se a pessoa é do tipo gestora
                $isGestor = $pessoa[0]->where('codPes',$loginDadosOBJ->loginUsuario)->get(['isGestor']);
                //Se for gestor seta variavel de checagem de login como isGestor ="sim" senão coloca isGestor="nao"
                if($isGestor[0]->isGestor =="sim")
                {
                    $isGestor = "sim";
                }else{
                    $isGestor = "nao";
                }
            }
            // dd($pessoa);
                        
            // //Ve se a pessoa já tem algum grupo de trabalho - conta quantos grupos ela tem
            // $contaGrupo = $pessoa[0]->grupos()->count();
            // //Ve se a pessoa é do tipo gestora
            // $isGestor = $pessoa[0]->where('codPes',$loginDadosOBJ->loginUsuario)->get(['isGestor']);
            
            /**
             * Se já tem registro dessa Pessoa Capturo dados do usuário já cadastrados neste sistema (se é gestora ou não e quais seus grupos), 
             * estes dados vão para a Dashnoard do sistema
             * Abaixo são validados os tipos de acesso ao sistema ( gestor sem grupo, gestor com grupo, administrador ou sem nenhum vínculo)
             **/

             //É Apenas Gestor não tem grupo de Trabalho
            //if($isGestor[0]->isGestor =="sim" && $contaGrupo < 1) -- OLD tem que ser tudo direto e não consulta de arrays
            if($isGestor =="sim" && $contaGrupo < 1)
            {                 
                //Crio uma SESSION com estes dados para controle de menus / já implementado no layout\app.blade
                SESSION::PUT ('codPes', $pessoa[0]->codPes);
                SESSION::PUT ('nome', $pessoa[0]->nome);
                SESSION::PUT ('isGestor', $pessoa[0]->isGestor);
                SESSION::PUT ('id', $pessoa[0]->id);

                //Flag para view de DashBoard ser moldada para pessoa que é apenas Gestora
                $onlyGestor = true;
                /**
                 * Manda para a view de dashBoard a Pessoa e seus Grupos
                 * ATENÇÃO: Não é uma rota é apenas uma view pois a rota de verifica login já vai atribuir as permissões e passar os grupos que a pessoa pertence
                 * Dentro da view a pessoa vai escolher o grupo que deseja trabalhar e depois que seleciona-lo este vai acionar uma rota para carregar os grupos neste mesma view
                 */                
                return view('login.dashboard',compact('pessoa','onlyGestor'));

            }elseif($contaPessoa > 0 && $contaGrupo >0) 
            {                
                //Pega a pessoa que fez o login no sistema GeraBoletos
                //$pessoa = $pessoa->where('codPes',$loginDadosOBJ->loginUsuario)->get();                
                //Pega os Grupos que essa pessoa pertence
                $gruposDaPessoa = $pessoa[0]->grupos()->get();
                //Crio uma SESSION com estes dados para controle de menus / já implementado no layout\app.blade
                SESSION::PUT ('codPes', $pessoa[0]->codPes);
                SESSION::PUT ('nome', $pessoa[0]->nome);
                SESSION::PUT ('isGestor', $pessoa[0]->isGestor);
                SESSION::PUT ('id', $pessoa[0]->id);

                //Flag para view de DashBoard ser moldada para pessoa que tem grupo ou seja pode ser gestor ou administrador 
                $temGrupo = true;
                /**
                 * Manda para a view de dashBoard a Pessoa e seus Grupos
                 * ATENÇÃO: Não é uma rota é apenas uma view pois a rota de verifica login já vai atribuir as permissões e passar os grupos que a pessoa pertence
                 * Dentro da view a pessoa vai escolher o grupo que deseja trabalhar e depois que seleciona-lo este vai acionar uma rota para carregar os grupos neste mesma view
                 */
                return view('login.dashboard',compact('pessoa','gruposDaPessoa','temGrupo'));

            }else{
                //Passo para a Session que é uma pessoa não gestora e sem dados
                SESSION::PUT ('codPes', 0);
                SESSION::PUT ('nome', '');
                SESSION::PUT ('isGestor', 'nao');
                // Mensagem a ser exibida para pessoa que tem n USP e fez login senha única, mas não tem ainda vínculo com o sistema
                $msgLogin = "<p>Olá, ".$loginDadosOBJ->nomeUsuario.".</p>";
                $msgLogin .= "<p>Este sistema é destinado a gerir boletos gerados, atualmente você não possui nenhum grupo de trabalho ou vínculo de gestão 
                com o sistema. Se deseja utilizá-lo por favor solicite a um dos gestores do sistema.</p><br><p>Lista de Gestores</p>";
                //Apresenta lista de Gestores
                $gestores = Pessoa::where('isGestor',"sim")->get();
                $msgLogin.="<ul>";
                foreach ($gestores as $gestor)
                {
                    $msgLogin .="<li>".$gestor->nome."</li>";
                }
                $msgLogin .="</ul>";
            }            

        }
        else{
            //$statusLogin = false;

            //Atende sessão expirada ou problemas de login
            $msgLogin = "<h3>Sessão Expirada - por favor faça login novamente pelo link abaixo.</h3>";
            $msgLogin .= "<a href='http://143.107.172.21/boletos'>"."Faça Login Novamente!"."</a>";
        }
        
        //retorna a página principal caso seja pessoa sem vinculos com sistema ou com sessão expirada
        return view('login.area',compact('msgLogin'));
    }

    /**
     * Ajuda a montar a tabela da DashBoard com o grupo selecionado pelo usuário
     * Realiza alguns procedimentos igual ao valida Login para continuar montando a view de dashBoard
     * Carrega tabela com os boletos do grupo selecionado
     * @param int grupoID
     * ATENÇÃO tem que passar a flag $temGrupo = true porque só acessa até aqui quem passou pela validação acima que tem grupo
     * flegando essa variável como true. Ela ajuda a montar a página da view SEM ELA NÃO FUNCIONA !!!
     * ATENÇÃO: O grupo de trabalho selecionado é passado por SESSION serve para criar boletos no grupo certo, escolhido pela pessoa
     */
    public function dashBoard(Request $request)
    {
        //Flag para view de DashBoard ser moldada para pessoa que tem grupo ou seja pode ser gestor ou administrador, se for false mostra sem grupo 
        $temGrupo = true;
        //Pega os dados da request - grupo selecionado
        $data = $request->all();
        //Serve para reexibir a dash através de outros métodos depois do Selecionar grupo na view...
        if(!isset($data['grupo']))
        {
            $data['grupo'] = SESSION::GET('grupoSelecionado');
        }
        //Coloca na Session qual grupo a pessoa vai estar trabalhando, ajuda na criação do BOLETO criando para o grupo certo!
        SESSION::PUT('grupoSelecionado',$data['grupo']);        
        //Pega a pessoa que fez o login no sistema GeraBoletos
        $pessoa = new Pessoa();
        $pessoa = $pessoa->where('codPes',session()->get('codPes'))->get();                
        //Pega os Grupos que essa pessoa pertence
        $gruposDaPessoa = $pessoa[0]->grupos()->get();
        //Cria objeto do grupo selecionado
        $grupoSelecionado = new Grupo();
        $grupoSelecionado = $grupoSelecionado->where('id',$data['grupo'])->get();
        
        //Retorna para a View a Data atual para validar os boletos a serem exibidos
        $dataAtual = date("Y-m-d");
        
        //Captura eventos de boleto que não expiraram o prazo de Publicação. Incluindo os que estão ou não publicados- vai que foi despublicado só para manutenção rapida assim não some da vista do administrador
        $boletos = $grupoSelecionado[0]->boletos()->where('fimDataPublicacao', '>=', $dataAtual)->get();        

        return view('login.dashboard',compact('pessoa','gruposDaPessoa','boletos','grupoSelecionado','temGrupo'));
    }    

    //Realiza o Logout Destruindo Cookie e Session
    public function logout()
    {
        //Cookie::queue(Cookie::forget('loginUSP'));
        setcookie('loginUSP','',-1,"/");//Funciona como Logout para nós
        SESSION::FLUSH();
        //retorna a página principal
        flash('Logout feito com Sucesso!')->success();
        return redirect()->route('area');

    }

}
