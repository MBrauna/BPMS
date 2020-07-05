<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Carbon;

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

            $this->gbUsuario=   intval($idUsuario);
            $this->gbAcesso =   usuario_acesso(intval($idUsuario));

            $retorno        =   [];

            try {
                // Fazer filtros
                // Fazer filtros

                // Tarefas atribuídas ao meu usuário
                $tarefaUsuario          =   DB::table('chamado')
                                            ->join('situacao','situacao.id_situacao','chamado.id_situacao')
                                            ->where('situacao.conclusiva',false)
                                            ->where('chamado.id_responsavel',intval($idUsuario))
                                            ->where(function($query){
                                                foreach($this->gbAcesso as $csr) {
                                                    $query->orWhereRaw('((chamado.id_empresa = ?) and (chamado.id_processo = ?) and (situacao.id_perfil = ?))',[$csr->id_empresa, $csr->id_processo, $csr->id_perfil]);
                                                } // foreach($this->gbAcesso as $csr) { ... }
                                            })
                                            ->select('chamado.*')
                                            ;
                
                $tarefaTramite          =   DB::table('chamado')
                                            ->join('situacao','situacao.id_situacao','chamado.id_situacao')
                                            ->where('situacao.conclusiva',false)
                                            ->where('chamado.id_responsavel', '!=',intval($idUsuario))
                                            ->where(function($query){
                                                foreach($this->gbAcesso as $csr) {
                                                    $query->orWhereRaw('((chamado.id_empresa = ?) and (chamado.id_processo = ?) and (situacao.id_perfil = ?))',[$csr->id_empresa, $csr->id_processo, $csr->id_perfil]);
                                                } // foreach($this->gbAcesso as $csr) { ... }
                                            })
                                            ->select('chamado.*')
                                            ;

                $tarefaSolicitante      =   DB::table('chamado')
                                            ->join('situacao','situacao.id_situacao','=','chamado.id_situacao')
                                            ->where('situacao.tarefa_solicitante',true)
                                            ->where('chamado.id_solicitante',$this->gbUsuario)
                                            ->select('chamado.*')
                                            ->union($tarefaUsuario)
                                            ->union($tarefaTramite)
                                            ->where('situacao.conclusiva',false)
                                            ->distinct()
                                            ->orderBy('data_vencimento','asc')
                                            ->get();

                foreach ($tarefaSolicitante as $chamado) {
                    $tmpRetorno     =   [];
                    $situacao       =   DB::table('situacao')->where('id_situacao',$chamado->id_situacao)->first();

                    $fluxoManter    =   DB::table('situacao')->where('id_situacao',$chamado->id_situacao);
                    $fluxo          =   DB::table('fluxo_situacao')
                                        ->join('situacao','situacao.id_situacao','fluxo_situacao.id_situacao_posterior')
                                        ->where('fluxo_situacao.id_situacao_atual',$chamado->id_situacao)
                                        ->where('fluxo_situacao.situacao',true)
                                        ->where('situacao.situacao',true)
                                        ->union($fluxoManter)
                                        ->select(
                                            'situacao.*'
                                        )
                                        ->get();

                    $tmpRetorno['idChamado']    =   $chamado->id_chamado;
                    $tmpRetorno['titulo']       =   $chamado->titulo;
                    $tmpRetorno['lista']        =   $fluxo;
                    $tmpRetorno['subordinados'] =   usuario_subordinado($this->gbUsuario, $chamado->id_empresa, $chamado->id_processo);
                    $tmpRetorno['solicitante']  =   $situacao->tarefa_solicitante;
                    $tmpRetorno['responsavel']  =   $situacao->marca_responsavel;
                    $tmpRetorno['altVenc']      =   $situacao->alterar_data_vencimento;
                    $tmpRetorno['conclusiva']   =   $situacao->conclusiva;

                    array_push($retorno, (object)$tmpRetorno);
                } // foreach ($tarefaSolicitante as $chamado) { ... }

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
