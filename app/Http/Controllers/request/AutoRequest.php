<?php

    namespace App\Http\Controllers\request;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class AutoRequest extends Controller
    {
        public function getDataEntry(Request $request) {
            try {
                $idProcessoReferencia   =   $request->input('idProcessoReferencia');
                $idProcessoOrigem       =   $request->input('idProcessoOrigem');
                $idProcessoDestino      =   $request->input('idProcessoDestino');
                $idSubProcessoOrigem    =   $request->input('idSubProcessoOrigem');
                $idSubProcessoDestino   =   $request->input('idSubProcessoDestino');
                $responsavelOrigem      =   $request->input('responsavelOrigem');
                $responsavelDestino     =   $request->input('responsavelDestino');
                $entregavel             =   $request->input('entregavel');
                $periodicidade          =   $request->input('periodicidade');
                $periodicidade_data     =   $request->input('periodicidade_data');
                $periodicidade_hora     =   $request->input('periodicidade_hora');
                $tipo                   =   $request->input('idTipo');

                if(is_null($idProcessoReferencia) || is_null($tipo) || is_null($entregavel) || is_null($periodicidade)) return redirect()->route('object.index');

                if($tipo == 2) {
                    $dbReg      =   DB::table('pergunta_tipo')
                                    ->where('pergunta_tipo.id_tipo_processo',intval($idSubProcessoDestino))
                                    ->where('pergunta_tipo.situacao',true)
                                    ->orderBy('pergunta_tipo.ordem','asc')
                                    ->get();
                }
                else {
                    $dbReg      =   DB::table('pergunta_tipo')
                                    ->where('pergunta_tipo.id_tipo_processo',intval($idSubProcessoOrigem))
                                    ->where('pergunta_tipo.situacao',true)
                                    ->orderBy('pergunta_tipo.ordem','asc')
                                    ->get();
                }
            
                $itemChamado    =   [];

                foreach ($dbReg as $conteudo) {
                    // Datetime coleta 2 campos
                    if($conteudo->tipo == 'datetime') {
                        $dataHora       =   $request->input('questao_'.$conteudo->id_pergunta_tipo.'_data').' '.$request->input('questao_'.$conteudo->id_pergunta_tipo.'_hora');
                        array_push($itemChamado,(object)[
                            'questao'   =>  $conteudo->descricao,
                            'tipo'      =>  $conteudo->tipo,
                            'alteraVenc'=>  $conteudo->alt_data_vencimento,
                            'resposta'  =>  Carbon::parse($dataHora),
                        ]);
                    }
                    elseif($conteudo->tipo == 'date') {
                        array_push($itemChamado,(object)[
                            'questao'   =>  $conteudo->descricao,
                            'tipo'      =>  $conteudo->tipo,
                            'alteraVenc'=>  $conteudo->alt_data_vencimento,
                            'resposta'  =>  Carbon::parse($request->input('questao_'.$conteudo->id_pergunta_tipo))->startOfDay(),
                        ]);
                    }
                    else  {
                        array_push($itemChamado,(object)[
                            'questao'   =>  $conteudo->descricao,
                            'tipo'      =>  $conteudo->tipo,
                            'alteraVenc'=>  false,
                            'resposta'  =>  $request->input('questao_'.$conteudo->id_pergunta_tipo),
                        ]);
                    }
                } // foreach ($dbReg as $conteudo) { ... }

                $data = null;

                if(is_null($periodicidade_data) && is_null($periodicidade_hora)) {
                    $data   =   Carbon::now();
                } // if(is_null($periodicidade_data) && is_null($periodicidade_hora)) { ... }
                elseif(!is_null($periodicidade_data) && is_null($periodicidade_hora)) {
                    $data   =   Carbon::parse($periodicidade_data.' '.Carbon::now()->hour.':'.Carbon::now()->minute);
                } // elseif if(is_null($periodicidade_data) && is_null($periodicidade_hora)) { ... }
                elseif(is_null($periodicidade_data) && !is_null($periodicidade_hora)) {
                    $data   =   Carbon::now()->startOfDay();
                    $data   =   $data->addHours(explode(':',$periodicidade_hora)[0]);
                    $data   =   $data->addMinutes(explode(':',$periodicidade_hora)[1]);
                } // elseif if(is_null($periodicidade_data) && is_null($periodicidade_hora)) { ... }
                else {
                    $data   =   Carbon::parse($periodicidade_data.' '.$periodicidade_hora);
                }

                $dataCriacao    =   Carbon::now();


                DB::beginTransaction();
                DB::table('entrada_solicitacao')
                ->insert([
                    'tipo'                      =>  intval($tipo),
                    'id_processo_origem'        =>  is_null($idProcessoOrigem) ? null : intval($idProcessoOrigem),
                    'id_processo_destino'       =>  is_null($idProcessoDestino) ? null : intval($idProcessoDestino),
                    'id_tipo_processo_origem'   =>  is_null($idSubProcessoOrigem) ? null : intval($idSubProcessoOrigem),
                    'id_tipo_processo_destino'  =>  is_null($idSubProcessoDestino) ? null : intval($idSubProcessoDestino),
                    'data_criacao'              =>  $dataCriacao,
                    'data_primeiro_agendamento' =>  $data,
                    'data_proximo_agendamento'  =>  $data,
                    'id_solicitante'            =>  Auth::user()->id,
                    'id_responsavel_origem'     =>  is_null($responsavelOrigem) ? null : intval($responsavelOrigem),
                    'id_responsavel_destino'    =>  is_null($responsavelDestino) ? null : intval($responsavelDestino),
                    'id_processo_referencia'    =>  intval($idProcessoReferencia),
                    'url'                       =>  $_SERVER['HTTP_HOST'],
                    'titulo'                    =>  $entregavel,
                    'tipo_objeto'               =>  null, //intval($tipoObjeto),
                    'meio'                      =>  null, //intval($meio),
                    'periodicidade'             =>  intval($periodicidade),
                    'situacao'                  =>  true,
                    'data_cria'                 =>  $dataCriacao,
                    'data_alt'                  =>  $dataCriacao,
                    'usr_cria'                  =>  Auth::user()->id,
                    'usr_alt'                   =>  Auth::user()->id,
                ]);
                DB::commit();

                $entradaSolicitacao =   DB::table('entrada_solicitacao')
                                        //->where('id_processo_origem',intval($idProcessoOrigem))
                                        //->where('id_processo_destino',intval($idProcessoDestino))
                                        ->where('tipo',intval($tipo))
                                        //->where('id_tipo_processo_origem', intval($idSubProcessoOrigem))
                                        //->where('id_tipo_processo_destino', intval($idSubProcessoDestino))
                                        ->where('data_primeiro_agendamento',$data)
                                        ->where('data_proximo_agendamento',$data)
                                        ->where('data_criacao',$dataCriacao)
                                        ->where('data_cria',$dataCriacao)
                                        ->where('usr_cria',Auth::user()->id)
                                        ->orderBy('data_cria', 'desc')
                                        ->first();

                foreach ($itemChamado as $value) {
                    try {
                        DB::beginTransaction();
                        DB::table('entrada_solicitacao_item')->insert([
                            'id_entrada_solicitacao'    =>  $entradaSolicitacao->id_entrada_solicitacao,
                            'tipo'                      =>  $value->tipo,
                            'questao'                   =>  $value->questao,
                            'resposta'                  =>  $value->resposta,
                            'data_cria'                 =>  Carbon::now(),
                            'data_alt'                  =>  Carbon::now(),
                            'usr_cria'                  =>  Auth::user()->id,
                            'usr_alt'                   =>  Auth::user()->id,
                        ]);
                        DB::commit();
                    }
                    catch(Exception $erro) {
                        DB::rollback();
                    }
                } // foreach ($itemChamado as $value) { ... }

                return redirect()->route('object.list');
            } // try { ... }
            catch(Exception $erro) {
                dd($erro);
            } // catch(Exception erro) { ... }
        } // public function getDataEntry(Request $request) { ... }
    }
