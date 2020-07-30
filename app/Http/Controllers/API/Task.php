<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Carbon\Carbon;

    class Task extends Controller
    {
        private $gbUsuario;
        private $gbAcesso;

        public function list(Request $request) {
            $idUsuario      =   $request->input('idUsuario');
            $idChamado      =   $request->input('idBPMS');
            $titulo         =   $request->input('tituloBPMS');
            $idEmpresa      =   $request->input('idEmpresaBPMS');
            $idProcesso     =   $request->input('idProcessoBPMS');
            $idTipo         =   $request->input('idTipoBPMS');
            $idSituacao     =   $request->input('idSituacaoBPMS');

            if($idChamado == 'null') {
                $idChamado  =   null;
            }
            if($titulo == 'null') {
                $titulo  =   null;
            }
            if($idEmpresa == 'null') {
                $idEmpresa  =   null;
            }
            if($idProcesso == 'null') {
                $idProcesso  =   null;
            }
            if($idTipo == 'null') {
                $idTipo  =   null;
            }
            if($idSituacao == 'null') {
                $idSituacao  =   null;
            }

            $retorno        =   [];
            if(is_null($idUsuario)) return response()->json(['erro'=>['codigo' => 'Task0001', 'mensagem'=> 'Usuário não informado! Verifique.']],202);

            $this->gbUsuario=   intval($idUsuario);

            try {

                if(is_null($idSituacao)) {
                    $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',false)->where('situacao.situacao',true)->select('situacao.id_situacao');
                }
                elseif(intval($idSituacao) < 0){
                    $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',true)->where('situacao.situacao',true)->select('situacao.id_situacao');
                }    
                else {
                    $perfilSituacao =   DB::table('situacao')->where('situacao.id_situacao',intval($idSituacao))->where('situacao.situacao',true)->select('situacao.id_situacao');
                }

                // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- //

                $perfilProcesso     =   [];
                $listaSubordinados  =   [];

                foreach(usuario_acesso($this->gbUsuario) as $conteudo) {
                    if(!in_array($conteudo->id_processo,$perfilProcesso)) {
                        array_push($perfilProcesso, $conteudo->id_processo);
                    } // if(!in_array($conteudo->id_processo)) { ... }
                } // foreach(usuario_acesso(intval($idUsuario)) as $conteudo) { ... }

                foreach(consulta_subordinados_todos($this->gbUsuario) as $conteudo) {
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
                                            ->where('chamado.id_responsavel',$this->gbUsuario)
                                            ->whereIn('chamado.id_situacao',$perfilSituacao)
                                            ->select('chamado.*')
                                            ->where(function($query){
                                                foreach(usuario_acesso($this->gbUsuario) as $csr) {
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
                                                foreach(usuario_acesso($this->gbUsuario) as $csr) {
                                                    $query->orWhereRaw('((chamado.id_processo = ?) and (situacao.id_perfil = ?))',[$csr->id_processo, $csr->id_perfil]);
                                                }
                                                $query->orWhereRaw('1=2');
                                            });

                $tarefaFinal            =   DB::table('chamado')
                                            ->join('situacao','situacao.id_situacao','=','chamado.id_situacao')
                                            ->where('situacao.tarefa_solicitante',true)
                                            ->where('situacao.conclusiva',false)
                                            ->whereIn('chamado.id_situacao',$perfilSituacao)
                                            ->where('chamado.id_solicitante',$this->gbUsuario)
                                            ->select('chamado.*')
                                            ->union($tarefaVinculada)
                                            ->union($tarefaTramite)
                                            ->distinct()
                                            ->orderBy('data_vencimento','asc')
                                            ->get();


                // -------------------------------------------------------------- //

                foreach($tarefaFinal as $tarefa) {

                    if(!is_null($idChamado) && $tarefa->id_chamado != intval($idChamado)) continue;
                    // por titulo
                    if(!is_null($titulo) && !strpos($tarefa->titulo, $titulo)) continue;
                    // Para empresa
                    if(!is_null($idEmpresa) && $tarefa->id_empresa != intval($idEmpresa)) continue;
                    // Para processo
                    if(!is_null($idProcesso) && $tarefa->id_processo != intval($idProcesso)) continue;
                    // Para filtros
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
                    $tmpRetorno['config']   =   $tmpSitAtual->first();
                    $tmpRetorno['sub']      =   usuario_subordinado($this->gbUsuario, $tarefa->id_processo);
                    $tmpRetorno['menorData']=   Carbon::now()->addDays(1)->toDateString();
                    $tmpRetorno['venc']     =   Carbon::parse($tarefa->data_vencimento)->format('d/m/Y H:i');
                    $tmpRetorno['dataVenc'] =   Carbon::parse($tarefa->data_vencimento)->toDateString();
                    $tmpRetorno['horaVenc'] =   Carbon::parse($tarefa->data_vencimento)->format('H:i');

                    array_push($retorno, $tmpRetorno);
                } // foreach($tarefaFinal as $tarefa) { ... }

            } // try { ... }
            catch(Exception $erro) {
                $retorno    =   [
                    'erro'  =>  [
                        'codigo'    =>  'Task0001',
                        'mensagem'  =>  'Não foi possível coletar as tarefas! Verifique.',
                    ],
                ];

                return response()->json($retorno,500);
            } // catch(Exception $erro) { ... }

            return response()->json($retorno,200);
        } // public function list(Request $request) { ... }
    }
