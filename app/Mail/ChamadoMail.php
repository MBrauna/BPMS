<?php

    namespace App\Mail;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use DB;
    
    class ChamadoMail extends Mailable
    {
        use Queueable, SerializesModels;

        private $user;
        private $chamado;
        private $enviaEmail = false;
        /**
         * Create a new message instance.
         *
         * @return void
         */
        public function __construct($user, $chamado)
        {
            if(is_null($user) || is_null($chamado)) {
                $enviaEmail = false;
                return;
            } // if(is_null($user) || is_null($chamado)) { ... }

            $tmpChamado     =   DB::table('chamado')
                                ->where('chamado.id_chamado',$chamado)
                                ->first();

            if(!isset($tmpChamado->id_chamado) || is_null($tmpChamado->id_chamado)) {
                $this->enviaEmail   =   false;
                return;
            } // if(!isset($tmpChamado->id_chamado) || is_null($tmpChamado->id_chamado)) { ... }

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
            if($this->enviaEmail && !is_null($this->user) || !is_null($this->chamado->id_chamado)) {
                return  $this->from('1ness@1nesstech.com.br')
                        ->subject('Nova solicitação de serviço - #'.$this->chamado->id_chamado)
                        ->view('mail.chamado')->with([
                            'user'      => $this->user,
                            'chamado'   => $this->chamado,
                        ]);
            } // if($this->enviaEmail) { ... }

            return;
        } // public function build() { ... }
    }
