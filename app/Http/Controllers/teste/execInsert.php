<?php

namespace App\Http\Controllers\teste;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class execInsert extends Controller
{
    public function index(Request $request) {
        $conteudoProcesso   =   DB::table('empresa')
                                ->join('processo','processo.id_empresa','empresa.id_empresa')
                                ->where('empresa.situacao',true)
                                ->where('processo.situacao',true)
                                ->where('empresa.id_empresa','>',2)
                                ->get();

        foreach($conteudoProcesso as $conteudo) {
            $quantidade =   DB::table('situacao')
                            ->where('situacao.id_processo',$conteudo->id_processo)
                            ->count();

            if($quantidade > 0) continue;


            DB::table('situacao')->insert([
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Aceita pelo coord de área de conhecimento de proc e aguarda nomeação do resp pelo atendimento',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  true,
                    'alterar_data_vencimento'   =>  true,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Aguarda aprovação do gerente de área',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Aguarda reconhecimento do resp pelo desempenho do proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  true,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Atendimento determinado pelo resp pelo desempenho do proc',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Atendimento recusado pelo solicitante e aguarda análise do resp pelo desempenho do proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Concluída',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  true,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Demanda reconhecida e tratamento retomado pelo coord de área de conhecimento de proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  true,
                    'alterar_data_vencimento'   =>  true,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Demanda reconhecida e tratamento suspenso pelo coord de área de conhecimento de proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  true,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Não aceita pelo coord de área de conhecimento de proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Não aprovada pelo gerente de área',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Não reconhecida pelo resp pelo desempenho do proc  ',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Reconhecida como demanda de projeto e aguarda reconhecimento do GP',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Reconhecida como projeto',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Reconhecida pelo resp pele atendimento e aguarda início do tratamento',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Reconhecida pelo resp pelo desempenho do proc e aguarda aceitação do coord de área de conhecimento de proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Recusa do solicitante aceita como procedente pelo resp pelo desempenho do proc',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  true,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Recusada pelo GP',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Resp pelo atendimento apontado e aguarda o seu reconhecimento',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Sendo tratada pelo resp pelo atendimento',
                    'tarefa_solicitante'        =>  false,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Solicitação de serviço atendida',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
                [
                    'id_processo'               =>  $conteudo->id_processo,
                    'descricao'                 =>  'Tratamento cancelado pelo coord de área de conhecimento de proc',
                    'tarefa_solicitante'        =>  true,
                    'marca_responsavel'         =>  false,
                    'alterar_data_vencimento'   =>  false,
                    'conclusiva'                =>  false,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  1,
                    'usr_alt'                   =>  1,
                ],
            ]);


            $situacaoInit   =   DB::table('situacao')
                            ->where('situacao.id_processo',$conteudo->id_processo)
                            ->min('situacao.id_situacao');

            DB::table('tipo_processo')->insert([
                [
                    'id_processo'   =>  $conteudo->id_processo,
                    'descricao'     =>  'Extraordinária',
                    'id_situacao'   =>  $situacaoInit,
                    'questao'       =>  'Atividade extraordinária e conhecida.',
                    'ordem'         =>  6,
                    'sla'           =>  1440,
                    'data_cria'     =>  Carbon::now(),
                    'data_alt'      =>  Carbon::now(),
                    'usr_cria'      =>  1,
                    'usr_alt'       =>  1,
                ],
            ]);
        } // foreach($conteudoProcesso as $conteudo) { ... }

        
        $conteudoTipo   =   DB::table('empresa')
                            ->join('processo','processo.id_empresa','empresa.id_empresa')
                            ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                            ->where('empresa.situacao',true)
                            ->where('processo.situacao',true)
                            ->where('empresa.id_empresa','>',2)
                            ->select('tipo_processo.*')
                            ->get();
        
        foreach($conteudoTipo as $conteudo) {
            DB::table('pergunta_tipo')->insert([
                [
                    'id_tipo_processo'      =>  $conteudo->id_tipo_processo,
                    'descricao'             =>  'Descreva a sua necessidade (descrever uma necessidade por solicitação)',
                    'tipo'                  =>  'longText',
                    'ordem'                 =>  1,
                    'alt_data_vencimento'   =>  false,
                    'data_cria'             =>  Carbon::now(),
                    'data_alt'              =>  Carbon::now(),
                    'usr_cria'              =>  1,
                    'usr_alt'               =>  1,
                ],
                [
                    'id_tipo_processo'      =>  $conteudo->id_tipo_processo,
                    'descricao'             =>  'Aponte a data e hora esperada para conclusão',
                    'tipo'                  =>  'datetime',
                    'ordem'                 =>  2,
                    'alt_data_vencimento'   =>  true,
                    'data_cria'             =>  Carbon::now(),
                    'data_alt'              =>  Carbon::now(),
                    'usr_cria'              =>  1,
                    'usr_alt'               =>  1,
                ],
            ]);
        } // foreach($conteudoTipo as $conteudo) { ... }

        return redirect()->route('raiz');
    } // public function index(Request $request) { ... }
}
