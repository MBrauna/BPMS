<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DB;

class TarefaMail extends Mailable
{
    use Queueable, SerializesModels;

        private $user;
        private $tarefa;
        private $chamado;
        private $enviaEmail = false;
        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct($user, $tarefa)
        {
            if(is_null($user) || is_null($tarefa)) {
                $this->enviaEmail   =   false;
                $this->tarefa       =   null;
                $this->chamado      =   null;
                $this->user         =   null;
                return;
            } // if(is_null($user) || is_null($chamado)) { ... }

            $tmpTarefa     =   DB::table('tarefa')
                                ->where('tarefa.id_tarefa',$tarefa)
                                ->first();


            if(!isset($tmpTarefa->id_tarefa) || is_null($tmpTarefa->id_tarefa)) {
                $this->enviaEmail   =   false;
                $this->tarefa       =   null;
                $this->chamado      =   null;
                $this->user         =   null;
                return;
            } // if(!isset($tmpChamado->id_chamado) || is_null($tmpChamado->id_chamado)) { ... }

            $tmpChamado     =   DB::table('chamado')
                                ->where('chamado.id_chamado',$tmpTarefa->id_chamado)
                                ->first();

            if(!isset($tmpChamado->id_chamado) || is_null($tmpChamado->id_chamado)) {
                $this->enviaEmail   =   false;
                $this->tarefa       =   null;
                $this->chamado      =   null;
                $this->user         =   null;
                return;
            } // if(!isset($tmpChamado->id_chamado) || is_null($tmpChamado->id_chamado)) { ... }

            $this->tarefa       =   $tmpTarefa;
            $this->chamado      =   $tmpChamado;
            $this->user         =   $user;
            $this->enviaEmail   =   true;
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            if($this->enviaEmail && !is_null($this->user) || !is_null($this->tarefa->id_tarefa)) {
                return  $this->from('1ness@1nesstech.com.br')
                        ->subject('Movimentação da solicitação de serviço - #'.$this->tarefa->id_chamado)
                        ->view('mail.tarefa')->with([
                            'user'      =>  $this->user,
                            'tarefa'    =>  $this->tarefa,
                            'chamado'   =>  $this->chamado,
                        ]);
            } // if($this->enviaEmail) { ... }

            return;
        } // public function build() { ... }
}
