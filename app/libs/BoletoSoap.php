<?php

namespace App\libs;
/**
 * Adaptado do repositório: https://github.com/uspdev/boleto
 * A lib a principio estava de forma incompatível com o laravel, 
 * já está adaptada e com os imports de forma correta para Laravel
 * 
 * Ps.:Nome da classe alterado devido a BOLETO ser uma entidade de Model deste projeto
 * 
 * 01/09/2020 - William B. Montini
 */

class BoletoSoap
{
    private $clienteSoap;

    public function __construct($user, $pass, $dev = False)
    {
        if (!$dev) {
           $wsdl = 'https://uspdigital.usp.br/wsboleto/wsdl/boleto.wsdl';
        } else {
            $wsdl = 'https://dev.uspdigital.usp.br/wsboleto/wsdl/boleto.wsdl';
        }

        // require voltando ao root do projeto e trazendo dependencia da pasta vendor...
        require_once base_path('vendor/econea/nusoap/src/nusoap.php');
        //D:\inetpub\wwwroot\geraBoletos\vendor\econea\nusoap\src\nusoap.php
        $this->clienteSoap = new \nusoap_client($wsdl, 'wsdl');

        $erro = $this->clienteSoap->getError();

        if ($erro) {
            print_r($erro); 
            die();
        }
        $this->clienteSoap->setHeaders(array('username' => $user,'password' => $pass));
    }

    public function gerar($data)
    {
        /* aqui esperamos que tudo cheguem em utf8 e convertemos utf8_decode*/
        foreach($data as $key=>$value) {
            $data[$key] = utf8_decode($value);
        }

        $request = $this->clienteSoap->call('gerarBoletoRegistrado', array('boletoRegistrado' => $data));

        $data = array();
        if ($this->clienteSoap->fault) {
            $data['status'] = False;
            $data['value'] = utf8_encode($request["detail"]["WSException"]);
            return $data;
        }
        else {
            $data['status'] = True;
            $data['value'] = $request['identificacao']['codigoIDBoleto'];
            return $data;
        }
    }

    public function situacao($codigoIDBoleto){
        $param = array('codigoIDBoleto'=>$codigoIDBoleto);
        $request = $this->clienteSoap->call('obterSituacao', array('identificacao'=>$param));
        
        $data = array();
        if ($this->clienteSoap->fault || $this->clienteSoap->getError()) {
            $data['status'] = False;
            $data['value'] = utf8_encode($request["detail"]["WSException"]);
            return $data;
        }
        else {
            $data['status'] = True;
            $data['value'] = array();
            $data['value']['situacao'] = $request['situacao']['statusBoletoBancario'];
            $data['value']['valorCobrado'] = $request['situacao']['valorCobrado'];
            $data['value']['valorEfetivamentePago'] = $request['situacao']['valorEfetivamentePago'];
            $data['value']['dataVencimentoBoleto'] = $request['situacao']['dataVencimentoBoleto'];
            $data['value']['dataEfetivaPagamento'] = $request['situacao']['dataEfetivaPagamento'];
            $data['value']['dataRegistro'] = $request['situacao']['dataRegistro'];
            $data['value']['dataCancelamentoRegistro'] = $request['situacao']['dataCancelamentoRegistro'];
            return $data;
        }
    }
    
    public function obter($codigoIDBoleto)
    {
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
        $request = $this->clienteSoap->call('obterBoleto', array('identificacao' => $param));

        $data = array();
        if ($this->clienteSoap->fault || $this->clienteSoap->getError()) {
            $data['status'] = False;
            $data['value'] = utf8_encode($request["detail"]["WSException"]);
            return $data;
        }
        else {
            $data['status'] = True;
            $data['value'] = $request['boletoPDF'];
            return $data;
        }
    }
    public function cancelar($codigoIDBoleto)
    {
        $param = array('codigoIDBoleto' => $codigoIDBoleto);
        $request = $this->clienteSoap->call('cancelarBoleto', array('identificacao' => $param));

        $data = array();
        if ($this->clienteSoap->fault) {
            $data['status'] = False;
            $data['value'] = utf8_encode($request["detail"]["WSException"]);
            return $data;
        }
        else {
            $data['status'] = True;
            $data['value'] = array();
            $data['value']['situacao'] = $request['situacao']['statusBoletoBancario'];
            $data['value']['valorCobrado'] = $request['situacao']['valorCobrado'];
            $data['value']['valorEfetivamentePago'] = $request['situacao']['valorEfetivamentePago'];
            $data['value']['dataVencimentoBoleto'] = $request['situacao']['dataVencimentoBoleto'];
            $data['value']['dataEfetivaPagamento'] = $request['situacao']['dataEfetivaPagamento'];
            $data['value']['dataRegistro'] = $request['situacao']['dataRegistro'];
            $data['value']['dataCancelamentoRegistro'] = $request['situacao']['dataCancelamentoRegistro'];
            return $data;
        }
    }

}