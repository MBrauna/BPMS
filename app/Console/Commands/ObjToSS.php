<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Carbon\Carbon;
use App\Chamado;

class ObjToSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'objeto:solicitacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transforma objetos de entrada e saída em solicitação de serviço';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //
            // Consulta os dados que necessitam de geração
            $dadoMensal     =   DB::table('entrada_solicitacao')
                                ->where('data_proximo_agendamento','<=', Carbon::now()->addDays(30)->endOfDay())
                                ->whereIn('periodicidade',[4,5,6,7])
                                ->where('situacao',true)
                                ->where('sla_cliente',true)
                                ->whereRaw('(((sla_fornecedor = ?) and (tipo = 2)) or (tipo = 1))',[true])
                                ;
            
            $dadoSemanal    =   DB::table('entrada_solicitacao')
                                ->where('data_proximo_agendamento','<=', Carbon::now()->addDays(10)->endOfDay())
                                ->whereIn('periodicidade',[1,2,3])
                                ->where('situacao',true)
                                ->where('sla_cliente',true)
                                ->whereRaw('(((sla_fornecedor = ?) and (tipo = 2)) or (tipo = 1))',[true])
                                ;

            $dadoGeracao    =   DB::table('entrada_solicitacao')
                                ->where('data_proximo_agendamento','<=', Carbon::now()->addDays(1)->endOfDay())
                                ->whereIn('periodicidade',[1,2,3])
                                ->where('situacao',true)
                                ->where('sla_cliente',true)
                                ->whereRaw('(((sla_fornecedor = ?) and (tipo = 2)) or (tipo = 1))',[true])
                                ->union($dadoSemanal)
                                ->union($dadoMensal)
                                ->distinct()
                                ->get()
                                ;

            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

            foreach($dadoGeracao as $conteudo) {
                $this->info("Executando [#{$conteudo->id_entrada_solicitacao}]");

                $cabecalho  =   DB::table('empresa')
                                ->join('processo','processo.id_empresa','empresa.id_empresa')
                                ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                ->where('empresa.situacao',true)
                                ->where('processo.situacao',true)
                                ->where('tipo_processo.situacao',true)
                                //->where('empresa.id_empresa',intval($idEmpresa))
                                ->where('processo.id_processo',$conteudo->id_processo_origem)
                                ->where('tipo_processo.id_tipo_processo',$conteudo->id_tipo_processo_origem)
                                ->select(
                                    'empresa.id_empresa as id_empresa',
                                    'processo.id_processo as id_processo',
                                    'tipo_processo.id_tipo_processo as id_tipo_processo',
                                    'tipo_processo.id_situacao as id_situacao',
                                    'tipo_processo.sla as sla'
                                )
                                ->first();


                if(Carbon::parse($conteudo->data_proximo_agendamento)->isoWeekday() === 6) {
                    $dataCriacao    =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(2);
                    $dataVencimento =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(2)->addMinutes($cabecalho->sla);
                }
                elseif(Carbon::parse($conteudo->data_proximo_agendamento)->isoWeekday() === 7) {
                    $dataCriacao    =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(1);
                    $dataVencimento =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(1)->addMinutes($cabecalho->sla);
                }
                else {
                    $dataCriacao    =   Carbon::parse($conteudo->data_proximo_agendamento);
                    $dataVencimento =   Carbon::parse($conteudo->data_proximo_agendamento)->addMinutes($cabecalho->sla);
                }
    
                if($dataVencimento->isoWeekday() === 6) {
                    $dataVencimento =   $dataVencimento->addDays(2);
                }
                elseif($dataVencimento->isoWeekday() === 7) {
                    $dataVencimento =   $dataVencimento->addDays(1);
                }

                $chamadoID                      =   new Chamado;
                $chamadoID->id_situacao         =   $cabecalho->id_situacao;
                $chamadoID->id_empresa          =   $cabecalho->id_empresa;
                $chamadoID->id_processo         =   $cabecalho->id_processo;
                $chamadoID->id_tipo_processo    =   $cabecalho->id_tipo_processo;
                $chamadoID->id_responsavel      =   $conteudo->tipo == 1 ? $conteudo->id_responsavel_origem : $conteudo->id_responsavel_destino;
                $chamadoID->data_criacao        =   $dataCriacao;
                $chamadoID->data_vencimento     =   $dataVencimento;
                $chamadoID->id_solicitante      =   $conteudo->id_solicitante;
                $chamadoID->url                 =   $conteudo->url;
                $chamadoID->titulo              =   $conteudo->titulo;
                $chamadoID->situacao            =   true;
                $chamadoID->data_cria           =   Carbon::now();
                $chamadoID->data_alt            =   Carbon::now();
                $chamadoID->usr_cria            =   $conteudo->id_solicitante;
                $chamadoID->usr_alt             =   $conteudo->id_solicitante;
                $chamadoID->save();

                $listItem   =   DB::table('entrada_solicitacao_item')
                                ->where('id_entrada_solicitacao',$conteudo->id_entrada_solicitacao)
                                ->orderBy('id_entrada_sol_item','asc')
                                ->get();

                foreach($listItem as $item) {
                    try {
                        DB::beginTransaction();
                        DB::table('chamado_item')->insert([
                            'id_chamado'    =>  $chamadoID->id_chamado,
                            'tipo'          =>  $item->tipo,
                            'questao'       =>  $item->questao,
                            'resposta'      =>  $item->resposta,
                            'data_cria'     =>  Carbon::now(),
                            'data_alt'      =>  Carbon::now(),
                            'usr_cria'      =>  $conteudo->id_solicitante,
                            'usr_alt'       =>  $conteudo->id_solicitante,
    
                        ]);
                        DB::commit();
                    }
                    catch(Exception $erro) {
                        DB::rollback();
                    }
                } // foreach($listItem as $item) { ... }

                $proximoAgendamento =   Carbon::now();

                switch ($conteudo->periodicidade) {
                    case 1:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(1);
                        break;
                    case 2:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addWeeks(1);
                        break;
                    case 3:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(15);
                        break;
                    case 4:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addMonths(1);
                        break;
                    case 5:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addMonths(2);
                        break;
                    case 6:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addMonths(6);
                        break;
                    case 7:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addYears(1);
                        break;
                    default:
                        $proximoAgendamento =   Carbon::parse($conteudo->data_proximo_agendamento)->addDays(1);
                        break;
                }

                DB::beginTransaction();
                DB::table('entrada_solicitacao')
                ->where('id_entrada_solicitacao',$conteudo->id_entrada_solicitacao)
                ->update([
                    'data_proximo_agendamento'  =>  $proximoAgendamento,
                    'qtde_chamado'              =>  $conteudo->qtde_chamado + 1,
                ]);
                DB::commit();
            } // foreach($dadoGeracao as $conteudo) { ... }
        } // try { ...}
        catch(Exception $error) {
            $this->info("erro {$error}!");
        } // catch(Exception $error) { ... }
    }
}
