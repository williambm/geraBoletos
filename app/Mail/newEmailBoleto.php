<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Consumidor;

class newEmailBoleto extends Mailable
{
    use Queueable, SerializesModels;

    public $consumidor;
    public $nomeEvento;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Consumidor $consumidor, String $nomeEvento)
    {
        $this->consumidor = $consumidor; 
        $this->nomeEvento = $nomeEvento;
         
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //dd($consumidor);
        return $this
            ->subject('Boleto Escola de Enfermagem - USP')
            ->view('mail.newEmailBoleto');
    }
}
