<?php

    namespace App\Http\Controllers\task;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Carbon\Carbon;
    use DB;
    use Auth;
    use App\Mail\TarefaMail;
    use App\User;
    use Mail;

    class ListTask extends Controller
    {
        public function edit(Request $request) {
            try {

                
                $idChamado      =   intval($request->input('id_chamado'));
                $idSituacao     =   intval($request->input('id_situacao'));
                $conteudo       =   $request->input('entrada');
                $idAtendente    =   $request->input('id_responsavel');
                $dataAlt        =   $request->input('data_vencimento');
                $horaAlt        =   $request->input('hora_vencimento');
                $arquivos       =   $request->file('arquivoBPMS');


                if(is_null($idChamado) || is_null($idSituacao)) return redirect()->route('task.list');

                $situacaoData   =   DB::table('situacao')->where('situacao.id_situacao',$idSituacao)->first();
                if(!isset($situacaoData->id_situacao)) return redirect()->route('raiz');
                $dataChamado    =   DB::table('chamado')
                                    ->where('chamado.id_chamado',$idChamado)
                                    ->first();

                // Sempre vai limpar se não for manter
                $limpa_resp     =   0;
                if($dataChamado->id_responsavel === Auth::user()->id) {
                    foreach(usuario_acesso(Auth::user()->id) as $csr) {
                        if(is_null($situacaoData->id_perfil) || ($situacaoData->id_perfil === $csr->id_perfil)) {
                            $limpa_resp =   $limpa_resp +1;
                        }
                    }
                }
                
                
                /*if(!is_null($dataChamado) && ($dataChamado->id_situacao != $situacaoData->id_situacao)) {
                    if($dataChamado->id_responsavel === Auth::user()->id) {

                        foreach(usuario_acesso(Auth::user()->id) as $csr) {
                            if(is_null($situacaoData->id_perfil) || ($situacaoData->id_perfil === $csr->id_perfil)) {
                                $limpa_resp =   $limpa_resp +1;
                            }
                        }
                    }
                }*/


                if($limpa_resp <= 0 && ($dataChamado->id_situacao != $situacaoData->id_situacao)) {
                    DB::table('chamado')
                    ->where('chamado.id_chamado',$dataChamado->id_chamado)
                    ->update([
                        'id_responsavel'    =>  null
                    ]);
                }

                $dataVenc = null;
                if(!is_null($dataAlt)) $dataVenc =  Carbon::parse($dataAlt.' '.$horaAlt);

                DB::beginTransaction();

                $dataCria                       =   Carbon::now();

                DB::table('tarefa')->insert([
                    [
                        'id_chamado'            =>  $dataChamado->id_chamado,
                        'conteudo'              =>  is_null($conteudo) ? ' ' : $conteudo,
                        'id_situacao_anterior'  =>  $dataChamado->id_situacao,
                        'id_situacao_atribuida' =>  $situacaoData->id_situacao,
                        'id_usuario_atribuido'  =>  intval($idAtendente),
                        'data_venc_anterior'    =>  $dataChamado->data_vencimento,
                        'data_venc_atribuida'   =>  $dataVenc,
                        'data_cria'             =>  $dataCria,
                        'data_alt'              =>  Carbon::now(),
                        'usr_cria'              =>  Auth::user()->id,
                        'usr_alt'               =>  Auth::user()->id,
                    ],
                ]);

                DB::commit();

                $tarefa =   DB::table('tarefa')
                            ->where('id_chamado',$dataChamado->id_chamado)
                            ->where('id_situacao_anterior',$dataChamado->id_situacao)
                            ->where('id_situacao_atribuida',$situacaoData->id_situacao)
                            ->where('id_usuario_atribuido',intval($idAtendente))
                            ->where('data_venc_anterior',$dataChamado->data_vencimento)
                            ->where('data_venc_atribuida',$dataVenc)
                            ->where('data_cria',$dataCria)
                            ->where('usr_cria',Auth::user()->id)
                            ->first();

                DB::beginTransaction();

                if(!is_null($dataAlt) && ($dataChamado->id_situacao != $situacaoData->id_situacao)) {
                    DB::table('chamado')
                    ->where('id_chamado',$idChamado)
                    ->update([
                        'data_vencimento'   =>  Carbon::parse($dataAlt)->endOfDay(),
                    ]);
                }

                if(!is_null($horaAlt) && ($dataChamado->id_situacao != $situacaoData->id_situacao)) {
                    $chamado    =   DB::table('chamado')->where('id_chamado',$idChamado)->first();
                    $tmpHora    =   explode(':',$horaAlt);

                    DB::table('chamado')
                    ->where('id_chamado',$idChamado)
                    ->update([
                        'data_vencimento'   =>  Carbon::parse($chamado->data_vencimento)->startOfDay()->hour($tmpHora[0])->minute($tmpHora[1]),
                    ]);
                }

                // Caso solicite alteração do responsavel
                if(!is_null($idAtendente)) {
                    DB::table('chamado')
                    ->where('id_chamado',$idChamado)
                    ->update([
                        'id_responsavel' => intval($idAtendente),
                    ]);
                }

                if($situacaoData->tarefa_solicitante) {
                    DB::table('chamado')
                    ->where('id_chamado',$idChamado)
                    ->update([
                        'id_responsavel' => $dataChamado->id_solicitante,
                    ]);
                } // if($situacaoData->tarefa_solicitante) { ... }

                DB::table('chamado')
                ->where('id_chamado',$idChamado)
                ->update([
                    'id_situacao' => $idSituacao,
                ]);

                if($situacaoData->conclusiva) {
                    DB::table('chamado')
                    ->where('id_chamado',$idChamado)
                    ->update([
                        'data_conclusao' => Carbon::now(),
                        //'id_responsavel' => $dataChamado->id_solicitante,
                    ]);
                }

                DB::commit();


                $existeArq  =   ($request->input('abrirArquivo') == 'on' && $request->hasFile('arquivoBPMS') && count($arquivos) > 0) ? true : false;

                if($existeArq) {
                    try {
                        foreach($arquivos as $chave => $arquivo) {
                            try {
                                if($arquivo->isValid()) {
                                    $nomeServidor       =   Carbon::now()->timestamp.'-'.$chave.'.'.$arquivo->getClientOriginalExtension();
                                
                                    DB::beginTransaction();
                                    DB::table('arquivo')
                                    ->insert([
                                        'id_chamado'    =>  $dataChamado->id_chamado,
                                        'id_tarefa'     =>  $tarefa->id_tarefa,
                                        'nome_servidor' =>  $nomeServidor,
                                        'nome_arquivo'  =>  $arquivo->getClientOriginalName(),
                                        'extensao'      =>  $arquivo->getClientOriginalExtension(),
                                        'mime'          =>  $arquivo->getMimeType(),
                                        //'tamanho'       =>  $arquivo->getSize(),
                                        'data_cria'     =>  Carbon::now(),
                                        'data_alt'      =>  Carbon::now(),
                                        'usr_cria'      =>  Auth::user()->id,
                                        'usr_alt'       =>  Auth::user()->id,
                                    ]);
                                    
                                    $upload = $arquivo->storeAs('tarefa', $nomeServidor);
                                    DB::commit();
                                }
                            } // try { ... }
                            catch(Exception $erro) {
                                DB::rollback();
                                dd($erro);
                            } // catch(Exception $erro) { ... }
                        } // foreach($arquivos as $arquivo) { ... }
                    }
                    catch(Exception $erro) {
                        dd($erro);
                    } // catch(Exception $erro) { ... }
                }


                try {
                    $usuarioEmail   =   User::find($dataChamado->id_solicitante);
                    // Envia ao solicitante
                    Mail::to($usuarioEmail->email)->send(new TarefaMail($usuarioEmail, $tarefa->id_tarefa));
                }
                catch(Exception $erro){
                    return redirect()->route('request.index');
                }

                return redirect()->route('task.list');


            }
            catch(Exception $erro) {
                return redirect()->route('task.list');
            }
        }



        public function index(Request $request) {
            /*$retorno        =   [];

            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

            $idChamado      =   $request->input('idBPMS');
            $titulo         =   $request->input('tituloBPMS');
            $idEmpresa      =   $request->input('idEmpresaBPMS');
            $idProcesso     =   $request->input('idProcessoBPMS');
            $idTipo         =   $request->input('idTipoBPMS');
            $idSituacao     =   $request->input('idSituacaoBPMS');

            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

            if(is_null($idSituacao)) {
                $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',false)->where('situacao.situacao',true)->select('situacao.id_situacao');
            }
            else {
                $perfilSituacao =   DB::table('situacao')->where('situacao.id_situacao',intval($idSituacao))->where('situacao.situacao',true)->select('situacao.id_situacao');
            }

            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

            $perfilProcesso     =   [];
            $listaSubordinados  =   [];

            foreach(usuario_acesso(Auth::user()->id) as $conteudo) {
                if(!in_array($conteudo->id_processo,$perfilProcesso)) {
                    array_push($perfilProcesso, $conteudo->id_processo);
                } // if(!in_array($conteudo->id_processo)) { ... }
            } // foreach(usuario_acesso(intval($idUsuario)) as $conteudo) { ... }

            foreach(consulta_subordinados_todos(Auth::user()->id) as $conteudo) {
                if(!in_array($conteudo->id,$listaSubordinados)) {
                    array_push($listaSubordinados, $conteudo->id);
                } // if(!in_array($conteudo->id_processo)) { ... }
            }

            // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

            // Tarefas vinculadas ao meu usuário
            $tarefaVinculada        =   DB::table('chamado')
                                        ->join('situacao','situacao.id_situacao','=','chamado.id_situacao')
                                        ->where('situacao.conclusiva',false) // NÃO FINALIZADA
                                        ->whereNull('chamado.data_conclusao')
                                        ->where('chamado.id_responsavel',Auth::user()->id)
                                        ->whereIn('chamado.id_situacao',$perfilSituacao)
                                        ->select('chamado.*')
                                        ->where(function($query){
                                            foreach(usuario_acesso(Auth::user()->id) as $csr) {
                                                $query->orWhereRaw('((chamado.id_processo = ?) and (situacao.id_perfil = ?))',[$csr->id_processo, $csr->id_perfil]);
                                            }
                                            $query->orWhereRaw('1=2');
                                        })
                                        ;
            
            $tarefaTramite          =   DB::table('chamado')
                                        ->join('situacao','situacao.id_situacao','=','chamado.id_situacao')
                                        ->where('situacao.conclusiva',false) // NÃO FINALIZADA
                                        ->whereNull('chamado.data_conclusao')
                                        ->whereIn('chamado.id_situacao',$perfilSituacao)
                                        ->whereNull('chamado.id_responsavel')
                                        ->select('chamado.*')
                                        ->where(function($query){
                                            foreach(usuario_acesso(Auth::user()->id) as $csr) {
                                                $query->orWhereRaw('((chamado.id_processo = ?) and (situacao.id_perfil = ?))',[$csr->id_processo, $csr->id_perfil]);
                                            }
                                            $query->orWhereRaw('1=2');
                                        });

            $tarefaFinal            =   DB::table('chamado')
                                        ->join('situacao','situacao.id_situacao','=','chamado.id_situacao')
                                        ->where('situacao.tarefa_solicitante',true)
                                        ->where('situacao.conclusiva',false)
                                        ->whereIn('chamado.id_situacao',$perfilSituacao)
                                        ->where('chamado.id_solicitante',Auth::user()->id)
                                        ->select('chamado.*')
                                        ->union($tarefaVinculada)
                                        ->union($tarefaTramite)
                                        ->distinct()
                                        ->orderBy('data_vencimento','asc')
                                        ->get();


            // -------------------------------------------------------------- //

            foreach($tarefaFinal as $tarefa) {
                // Prepara a saída
                $tmpRetorno =   [];

                // Prepara os dados do chamado
                $tmpRetorno['tarefa']   =   $tarefa;

                // Coleta  a situação atual e os dados da próxima
                $tmpSitAtual            =   DB::table('situacao')
                                            ->where('situacao.id_situacao',$tarefa->id_situacao);
                $tmpSitFluxo            =   DB::table('fluxo_situacao')
                                            ->where('fluxo_situacao.id_tipo_processo',$tarefa->id_tipo_processo)
                                            ->where('fluxo_situacao.id_situacao_atual',$tarefa->id_situacao)
                                            ->join('situacao','situacao.id_situacao','fluxo_situacao.id_situacao_posterior')
                                            ->select('situacao.*')
                                            ->union($tmpSitAtual)
                                            ->get();
                
                $tmpRetorno['situacao'] =   $tmpSitFluxo;
                $tmpRetorno['config']   =   $tmpSitAtual->get();
                $tmpRetorno['sub']      =   

                array_push($retorno,(object)$tmpRetorno);
            } // foreach($tarefaFinal as $tarefa) { ... }
            */
            return view('task.ListTask');
        } // public function index(Request $request) { ... }
    }
