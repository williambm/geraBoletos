<?php

namespace App\Http\Controllers\inscricao;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\InscricaoRequest;
use Illuminate\Support\Facades\DB;
use Cagartner\CorreiosConsulta\Facade;
use \Cagartner\CorreiosConsulta\CorreiosConsulta;
use Illuminate\Support\Facades\Mail;
use App\Mail\newEmailBoleto;

use App\libs\BoletoSoap;
use App\Boleto;
use App\Consumidor;


class InscricaoController extends Controller
{
    /**
     * Este método encaminha para a view inscricao.formulario
     * @param int boletoID - para carregar com as informações do evnto de boleto corretas
     */
    public function forumularioIndex($boletoID)
    {
        //pega do BD selecionando $boletoID
        $boleto = new Boleto();
        $boleto = $boleto->find($boletoID);

        //Retorna para a View a Data atual para validar os boletos a serem exibidos
        $dataAtual = date("Y-m-d");

        /**
         * Flag de Validação isTempoDeAbrirInscricao - Valida se já pode liberar o formulário para inscrições
         * de acordo com a data inicial da publicação
         * 
         * Se sim = true
         * Se ainda não é tempo = false
         */
        $isTempoDeAbrirInscricao;
        $dataQueAbreInscricao = date('d/m/Y' , strtotime($boleto['iniDataPublicacao']));
        if($dataAtual < $boleto['iniDataPublicacao'])
        {
            $isTempoDeAbrirInscricao = false;
        }else{
            $isTempoDeAbrirInscricao = true;
        }
        
        /**
         * Flag de Validação isTempoValido - valida se está no tempo ainda de inscrições de acordo com a data final da publicação
         * Se sim = true
         * Se expirou = false
         */        
        $isTempoValido;
        if($boleto['fimDataPublicacao']>=$dataAtual)
        {
            $isTempoValido = true;
        }else{
            $isTempoValido = false;
        }
        
        /**
         * Flag de validação isPublicado - valida se está com está opção ativada ou não
         * Se não = "nao
         * Se sim prossegue com a exibição do formulario... validando na VIEW com @if...
         */
        $isPublicado;
        if($boleto['isPublicado']=='nao')
        {
            $isPublicado = 'nao';
        }else{
            $isPublicado = 'sim';
        }
        
        return view('inscricao.formulario', compact('boleto','isTempoValido','isPublicado','isTempoDeAbrirInscricao','dataQueAbreInscricao')); 
    }

    /**
     * Este método é o que vai comunicar com o WEBSERVICE da USP para gerar o boleto e depois 
     * armazenar o usuário no banco de dados desta aplicação
     * 
     * @param InscricaoRequest $resquest
     * @param int $boletoID para poder fazer a criação do boleto representando e obdecendo 1:N
     */
    public function gerarBoletoRegistrado(InscricaoRequest $request, $boletoID)
    {
        $data = $request->all();
        $boleto = new Boleto();
        $boleto = $boleto->find($boletoID);
        //Varivavel de controle para a view pós geração de boleto - "se a pessoa ja tem boleto a msg é diferente da pessoa que vai fazer o boleto para umevento especifico pela primeira vez"
        $flagSituacao="";
        //Trata CPF e CNPJ para persistir no banco e também para enviar para o webservice. Só pode ser números e com as máscara Jquery vem como string formatada
        $data['cpf'] = str_replace(['.','-'],['',''],$data['cpf']);        
        $data['cnpjEmpresaInstituicao'] = str_replace(['.','/','-'],['','',''],$data['cnpjEmpresaInstituicao']);
        
        //Tratamento do cpfCNPJ dependendo do tipo do sacado 
        if ($data['tipoSacado'] == 'PF')
        {
            $cpfCNPJ = $data['cpf'];
        }
        elseif($data['tipoSacado'] == 'PJ' )
        {
            $cpfCNPJ = $data['cnpjEmpresaInstituicao'];
        }

        //Validação de apenas um boleto por CPF por evento TO-DO: Imendar aqui uma pagina de explicação e para a pessoa pegar segunda via 
        $VerificaConsumidor = $boleto->consumidores()->where('cpf',$data['cpf'])->exists();        
        if($VerificaConsumidor == true){
            $consumidor = $boleto->consumidores()->where('cpf',$data['cpf'])->first();            
            $flagSituacao = "segundaVia"; //ajusta flag da view
            //dd($boleto);

            //Envio de email
            //Variável que ajuda o envio de email com detalhes do nome do evento no corpo do email.
            $nomeEvento = $boleto->nomeEvento;
            Mail::to($data['email'])->send(new newEmailBoleto($consumidor,$nomeEvento));

            return view('inscricao.boletoGerado',compact('consumidor','flagSituacao'));
        }
        
        $boletoSOAP = new BoletoSoap('ee','9e4bab94');

        /**
         * Array que passa param para gerar o boleto
         * Faz merge de dados do evento de boleto vindo do BD com os da request
         * 
         * Ps.: Não estou passando aqui Nome da Fonte e subfonte, estou operando com o códigoDaFonteDeRecurso, o nosso sistema
         * capta dados de fonte e subfonte mas como é um parâmetro que na documentação da USP será descontinuado Ja operamos da
         * nova forma a partir deste ponto do sistema. Talvez no futuro deva retirar fonte e subfonte do formulario ... 
         */
        $dataSOAP = array(
            'codigoUnidadeDespesa'      => (int) $boleto->codUnidade, 
            'codigoFonteRecurso'        => (int) $boleto->codFonteRecurso,
            'estruturaHierarquica'      => $boleto->estrutHierarq,
            'codigoConvenio'            => $boleto->codConvenio,
            'dataVencimentoBoleto'      => $boleto->dataVenc, 
            'valorDocumento'            => (float)$boleto->valor,
            'valorDesconto'             => (float)$boleto->desconto,
            'tipoSacado'                => $data['tipoSacado'], 
            'cpfCnpj'                   => $cpfCNPJ, 
            'nomeSacado'                => $data['nome'],
            'codigoEmail'               => $data['email'],  
            'informacoesBoletoSacado'   => $boleto->infoSacado,
            'instrucoesObjetoCobranca'  => $boleto->instrObjCobranca,
        );
        
        // [Método Gerar] gerar boleto
        $gerar = $boletoSOAP->gerar($dataSOAP);
        if($gerar['status']) {
            $id = $gerar['value'];
            
            //dd($gerar, $id);

            // [Método Situacao] resgatar informações do boleto
            print_r($boletoSOAP->situacao($id));

            // [Método Obter] recupera o arquivo PDF do boleto 
            // (PDF no formato binário codificado para Base64)        
            $obter = $boletoSOAP->obter($id);
            
            /*
            //redirecionando os dados binarios do pdf para o browser            
            header('Content-type: application/pdf'); 
            header('Content-Disposition: attachment; filename="boleto.pdf"'); 
            echo base64_decode($obter['value']);
            */

            //Com o sucesso da geração do Boleto Registrado agora se cria o consumidor do sistema no BD
            //$consumidor = new Consumidor();
            $consumidor = array(
                'codPes'                =>$data['codPes'],
                'nome'                  =>$data['nome'],
                'cpf'                   =>$data['cpf'],
                'cep'                   =>$data['cep'],
                'endereco'              =>$data['endereco'],
                'numEndereco'           =>$data['numEndereco'],
                'complEndereco'         =>$data['complEndereco'],
                'cidade'                =>$data['cidade'],
                'uf'                    =>$data['uf'],
                'email'                 =>$data['email'],
                'telefone'              =>$data['telefone'],
                'nomeEmpresaInstituicao'=>$data['nomeEmpresaInstituicao'],
                'cnpjEmpresaInstituicao'=>$data['cnpjEmpresaInstituicao'],
                'tipoSacado'            =>$data['tipoSacado'],
                'rastreioBoleto_id'     =>$id,
            );

            //Objeto consumidor para e-mail
            $consumidorMail = new Consumidor();
            $consumidorMail->nome = $consumidor['nome'];
            $consumidorMail->cpf = $consumidor['cpf'];
            $consumidorMail->rastreioBoleto_id = $consumidor['rastreioBoleto_id'];
                        
            //Persiste de acordo com a relçao 1:n (boletos / consumidores) 
            $boleto->consumidores()->create($consumidor);
            
            $flagSituacao = "primeiraVia"; //ajusta flag da view
            //Envio de email
            //Variável que ajuda o envio de email com detalhes do nome do evento no corpo do email.
            $nomeEvento = $boleto->nomeEvento;            
            Mail::to($consumidor['email'])->send(new newEmailBoleto($consumidorMail,$nomeEvento));

            return view('inscricao.boletoGerado',compact('flagSituacao','consumidorMail')); 
        }
        
        
        
    }

    /**
     * Método LiveSearch de numero USP para candidato realizar inscrição
     * Funciona via AJAX pela view inscricao.formulario o ajax chama este método
     */
    //Busca automaticamente Pessoa pelo Número USP
    public function liveSearchPessoaUSP (Request $request)
    {
        
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = DB::connection("eebase")->select('SELECT *
            FROM EEBASE.dbmaint.pessoa where codpes ='.$query);                        
            $dados['nome'] = $data[0]->nompes;
            $dados['cpf'] = $data[0]->numcpf;
                       
            echo json_encode($dados);            
        }
        
    }
    /**
     * Método LiveSearch de CEP para candidato realizar inscrição
     * Funciona via AJAX pela view inscricao.formulario o ajax chama este método
     */
    //Busca automaticamente Endereço por CEP
    public function liveSearchCEP (Request $request)
    {
        
        if($request->get('cep'))
        {
            $query = $request->get('cep');
            $correios = new CorreiosConsulta();
            $correios = $correios->cep($query);
            
            echo json_encode($correios);            
        }
        
    }
    /**
     * Método que lista todas as inscrições que estão com período ativo, em aberto!
     * 
     * Esta lista é de link público
     */
    public function listaBoletosAtivosPublico()
    {
        $boleto = new Boleto();
        //Retorna para a View a Data atual para validar os boletos a serem exibidos
        $dataAtual = date("Y-m-d");        
        //Captura eventos de boleto que não expiraram o prazo de Publicação. Incluindo os que estão ou não publicados- vai que foi despublicado só para manutenção rapida assim não some da vista do administrador
        $boletos = $boleto->where('fimDataPublicacao', '>=', $dataAtual)->get();   
        //dd($boletos);

        return view('inscricao.index', compact('boletos'));
    }
     
}
