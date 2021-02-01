<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\BoletoRequest;
use App\Grupo;
use App\Boleto;
use App\Consumidor;
use App\libs\BoletoSoap;

class BoletoController extends Controller
{

    private $boleto;

    public function __construct(Boleto $boleto)
    {
        $this->boleto = $boleto;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Aqui se pega o grupo de trabalho em que o usuário está trabalhando por SESSION vem de LoginController@dashBoard
        $grupoID = session()->get('grupoSelecionado');
        //Listar o grupo que está se trabalhando
        $grupo = new Grupo();
        $grupo = $grupo->where('id',$grupoID)->get();

        //Retorna para a View a Data atual para validar o calendario de inicio do período de publicação
        $dataAtual = date("Y-m-d");
        
        //dd($grupo[0]);
        //mandar para a view de Criação com o compact contendo o Grupo selecionado
        return view('admin.boleto.create', compact('grupo','dataAtual'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BoletoRequest $request)
    {
        $data = $request->all();
        /**
         * Formata valores monetários para ficarem em formato INGLÊS Ex.:120.12
         * Tem que ser feito pois vem como string do formulário devido a máscara
         */

        $data['valor'] = str_replace(['R$','.',','],['','','.'],$data['valor']);
        $data['valor'] = floatval($data['valor']);
        $data['desconto'] = str_replace(['R$','.',','],['','','.'],$data['desconto']);
        $data['desconto'] = floatval($data['desconto']);
                        
        //Pega o grupo em que se está trabalhando com ajuda da Session
        $grupo = Grupo::where('id', session()->get('grupoSelecionado'))->get();        
        //Salvar o Boleto pelo grupo->boletos()->create() devido relação 1:N
        $grupo[0]->boletos()->create($data);
        
        flash('Evento de Boleto Criado com Sucesso!')->success();
        //return redirect()->route('area');
        return redirect()->route('dash');
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
     * Exibe todos os boletos de um grupoID
     *
     * @param  int  $grupoID
     * @return \Illuminate\Http\Response
     */
    public function showAll($grupoID)
    {
        $id = (int) $grupoID;
        //dd($id);        
        //Instanciar um oobjeto boleto para consultar por hasmany       
        $grupo = new Grupo();        
        $grupo = $grupo->where('id',$id)->get();         
        $boletos = $grupo[0]->boletos()->get()->sortByDesc('id'); //pega todos os boletos do grupoSeleciondo
        $boletos = Boleto::orderBy('id','desc')->paginate(7);
        //dd($boletos);
        return view('admin.boleto.listaHistoricoGrupo', compact('boletos','grupo'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Busca o evento de boletp
        $boleto = $this->boleto->where('id',$id)->first();
        //Remove a públicação do evento de Boleto para realizar atualização        
        $boleto->isPublicado = 'nao';
        $boleto->save();

        /**
         * Verifica se já foram gerados boletos para consumidores deste evento 
         * (Regra 1 definida nos requisitos de negócio, alteração de forma geral do boleto 
         * só pode ser feita se nenhum boleto ainda foi gerado "regra amarela do ppt de documentação")
         * 
         * Campos editaveis na regra amarela (Início e Fim do período de inscrições, qutde de limite de inscritos, Data de Vencimeto do Boleto)
         * 
         * Flag de controle @var boolean $hasBoletoEmitido
        **/
        $hasBoletoEmitido = $boleto->consumidores()->exists();


        //Aqui se pega o grupo de trabalho em que o usuário está trabalhando por SESSION vem de LoginController@dashBoard
        $grupoID = session()->get('grupoSelecionado');
        //Listar o grupo que está se trabalhando
        $grupo = new Grupo();
        $grupo = $grupo->where('id',$grupoID)->get();

        //Aqui não estou passando a data Atual para acertar o evento a datas anteriores a do dia "atual" - deixei aberto para que se quiserem implemento depois aqui.

        return view ('admin.boleto.edit',compact('boleto','grupo','hasBoletoEmitido'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $boleto = $this->boleto->where('id',$id)->first();
        $boleto->update($data);
        
        //Atualiza a publicação voltando ao ar o link de inscrição 
        $boleto->update(['isPublicado'=>'sim']);

        flash('Configuração do Evento de Boleto Atualizada com Sucesso!')->success();
        return redirect()->route('dash');
        //dd($data, $boleto);
    }

     /**
     * Copia um Evento de Boleto.
     *
     * @param  int  $boletoID
     * @return \Illuminate\Http\Response
     * 
     * @var boleto $boletoBase serve de base para criar o novo evento de boleto
     * Pego o first aqui para não ter que ficar manipulando array[0].. como é só um registro já retorna como objeto boleto com uso de first()
     */
    public function copyConfig($boletoID)
    {        
        $boletoBase = $this->boleto->where('id',$boletoID)->get()->first();
        $grupo = $boletoBase->grupo()->get()->first();
        //Retorna para a View a Data atual para validar o calendario de inicio do período de publicação
        $dataAtual = date("Y-m-d");
        //dd($boletoBase,$grupo);
        return view ('admin.boleto.copyConf', compact('boletoBase','grupo','dataAtual'));
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

    /**
     * Trata a Área expandida do evento de boleto, lista quem está inscrito no evento de boleto selecionado
     * 
     * @param int $boletoID
     */
    public function boletoExpandido($boletoID)
    {
        //Busca o evento de boleto a ser expandido com detalhes
        $boletoBase = $this->boleto->where('id',$boletoID)->get()->first();
        $nomeEvento = $boletoBase->nomeEvento;
        //dd($nomeEvento);
        //captura os consumidores deste boleto
        $consumidores = $boletoBase->consumidores()->get();        
        
        //Cria instância do webservice de boleto
        $boletoSOAP = new BoletoSoap(config('soapAuth.user'),config('soapAuth.password'));

        //Variável que vai agrupar arrays de consumidorBase que vão para a view
        $consumidoresTratado = [];

        //Array Base que vai com os dados para a view do boleto expandido
        $consumidorBase = array(
            'id' =>'',
            'nome'  =>'',
            'email' =>'',
            'rastreioBoleto_id' =>'',
            'statusPGTO' =>'',
        );
        
        foreach ($consumidores as $consumidor)
        {        
            $consumidorBase['id'] = $consumidor->id;
            $consumidorBase['nome'] = $consumidor->nome;
            $consumidorBase['rastreioBoleto_id'] = $consumidor->rastreioBoleto_id;
            $consumidorBase['email'] = $consumidor->email;

            //Pega o Status de pagamento
            $statusPGTO = $boletoSOAP->situacao($consumidor->rastreioBoleto_id);
            $stringFlag = '';
            $stringFlag = $statusPGTO['value']['situacao'];
            //Verificação por Switch Case do status de pgto, acerta o status da view ex.: 'C' do webservice vira 'Cancelado' e assim por diante
            switch($stringFlag)
            {
                case 'E':
                    $stringFlag = "Emitido";
                break;

                case 'P':
                    $stringFlag = "Pago";
                break;

                case 'V':
                    $stringFlag = "Verificar";
                break;

                case 'C':
                    $stringFlag = "Cancelado";
                break;

                default:
                    $stringFlag = "Não foi possível verificar";
            }
            $consumidorBase['statusPGTO'] = $stringFlag;
           
            //dd($consumidorBase) ;
           
           array_push($consumidoresTratado, $consumidorBase);
        }

        return view ('admin.boleto.expandido',compact('nomeEvento','consumidoresTratado')); 
    }

    /**
     * Remove Publicação do Evento de Boleto
     * altera o enum isPublicado para "nao"
     * 
     * @param int $boletoID
     */
    public function removePublication($boletoID)
    {
        //Atualiza isPublicado de acordo com o id
        $this->boleto->where('id',$boletoID)->update(['isPublicado'=>'nao']);
        //flash('Removida a publicação do Evento de Boleto selecionado!')->success();
        return redirect()->route('dash');
    }

    /**
     * Ativa a Publicação do Evento de Boleto
     * altera o enum isPublicado para "sim"
     * 
     * @param int $boletoID
     */
    public function activePublication($boletoID)
    {
        //Atualiza isPublicado de acordo com o id
        $this->boleto->where('id',$boletoID)->update(['isPublicado'=>'sim']);
        //flash('Ativada a publicação do Evento de Boleto selecionado!')->success();
        return redirect()->route('dash');
    }
}
