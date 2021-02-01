<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\libs\BoletoSoap;
use App\Consumidor;
class ConsumidorController extends Controller
{
    private $consumidor;

    public function __construct(Consumidor $consumidor)
    {
        $this->consumidor = $consumidor;
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
        //Criado em inscricaoController
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Criado em inscricaoController
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $consumidor = $this->consumidor->where('id',$id)->first();
        //Cria instância do webservice de boleto
        $boletoSOAP = new BoletoSoap(config('soapAuth.user'),config('soapAuth.password'));
        $statusPGTO = $boletoSOAP->situacao($consumidor->rastreioBoleto_id); 
        //dd($statusPGTO);
        //Formata dados do pagamento do boleto para a View
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
        $valorCobrado = number_format($statusPGTO['value']['valorCobrado'], 2, ',', '.');        
        
        $pgtoTratado = array(
            'situacao'      => $stringFlag,
            'valorCobrado'  => number_format($statusPGTO['value']['valorCobrado'], 2, ',', '.'),
            'valorPago'     => number_format($statusPGTO['value']['valorEfetivamentePago'], 2, ',', '.'),
            'dataVenc'      => $statusPGTO['value']['dataVencimentoBoleto'],
            'dataRegistro'  => $statusPGTO['value']['dataRegistro'],
        );
        //dd($pgtoTratado);
        return view('admin.consumidor.show', compact('consumidor','pgtoTratado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Recuperar Segunda via de boletos gerados
     * 
     * @param int $codBoletoGerado
     * @return boleto.pdf
     */
    public function segundaVia($codBoletoGerado)
    {
        $boletoSOAP = new BoletoSoap(config('soapAuth.user'),config('soapAuth.password'));
        //dd($codBoletoGerado);
        $obter = $boletoSOAP->obter($codBoletoGerado);

        //redirecionando os dados binarios do pdf para o browser            
        header('Content-type: application/pdf'); 
        header('Content-Disposition: attachment; filename="boleto.pdf"'); 
        echo base64_decode($obter['value']);
    }

    /**
     * Captura o status de pagamento do consumidor
     * 
     * @param int $codBoletoGerado
     * @return string $statusPGTO
     */
    public function statusPgto($codBoletoGerado)
    {
        $boletoSOAP = new BoletoSoap(config('soapAuth.user'),config('soapAuth.password'));
        $statusPGTO = $boletoSOAP->situacao($codBoletoGerado);
        //dd($statusPGTO);
        return $statusPGTO->situacao;
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
