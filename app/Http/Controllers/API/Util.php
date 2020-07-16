<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Carbon\Carbon;

    class Util extends Controller
    {
        public function filtroData(Request $request) {
            try {
                $listaEmpresa   =   null;
                $listaProcesso  =   null;
                $listaTipo      =   null;
                $listaSituacao  =   null;

                $vRetorno       =   null;

                // Coleta informações do usuário
                $idUsuario  =   $request->input('idUsuario');
                if(is_null($idUsuario)) return response()->json(['erro' => ['codigo' => 'UTLFiltro0001', 'mensagem' => 'O código do usuário não foi informado! Verifique.']],202);

                $validador  =   DB::table('perfil_usuario')->where('id_usuario',intval($idUsuario))->count();
                if($validador <= 0) return response()->json(['erro' => ['codigo' => 'UTLFiltro0001', 'mensagem' => 'Parametrização para o usuário é inválida! Verifique.']],202);

                $acessoData =   usuario_acesso(intval($idUsuario));
                $empresaData=   [];
                $processoDat=   [];
                foreach ($acessoData as $conteudo) {
                    if(!in_array($conteudo->id_empresa,$empresaData)) {
                        array_push($empresaData,$conteudo->id_empresa);
                    } // if(!in_array($conteudo->id_empresa,$empresaData)) { ... }

                    if(!in_array($conteudo->id_processo,$processoDat)) {
                        array_push($processoDat,$conteudo->id_processo);
                    } // if(!in_array($conteudo->id_empresa,$empresaData)) { ... }
                } // foreach ($acessoData as $conteudo) { ... }


                // Coleta os dados para Empresa
                try {
                    $listaEmpresa   =   DB::table('empresa')
                                        ->whereIn('empresa.id_empresa',$empresaData)
                                        ->where('empresa.situacao',true)
                                        ->select('empresa.*')
                                        ->distinct()
                                        ->orderBy('empresa.descricao','asc')
                                        ->get();
                } // try { ... }
                catch(Exception $erro) {
                    $listaEmpresa   =   [];
                }

                // Coleta os dados para Processo
                try {
                    $listaProcesso  =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->leftJoin('users','users.id','processo.id_usr_responsavel')
                                        ->whereIn('empresa.id_empresa',$empresaData)
                                        //->whereIn('processo.id_processo',$processoDat)
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->select(
                                            'processo.*',
                                            'users.name as nome_responsavel',
                                            'empresa.sigla as sigla_empresa',
                                            'empresa.descricao as desc_empresa'
                                        )
                                        ->distinct()
                                        ->orderBy('empresa.sigla','asc')
                                        ->orderBy('processo.descricao','asc')
                                        ->get();
                }
                catch(Exception $erro) {
                    $listaProcesso  =   [];
                }

                // Coleta os dados para Tipo
                try {
                    $listaTipo      =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                        ->whereIn('empresa.id_empresa',$empresaData)
                                        ->whereIn('processo.id_processo',$processoDat)
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->where('tipo_processo.situacao',true)
                                        ->select(
                                            'tipo_processo.*',
                                            'processo.descricao as processo_descricao',
                                            'empresa.sigla as sigla_empresa',
                                            'empresa.descricao as desc_empresa'
                                        )
                                        ->distinct()
                                        ->orderBy('processo.descricao','asc')
                                        ->orderBy('tipo_processo.ordem','asc')
                                        ->orderBy('tipo_processo.descricao','asc')
                                        ->get();
                }
                catch(Exception $erro) {
                    $listaTipo      =   [];
                }

                // Coleta os dados para Tipo
                try {
                    $listaSituacao  =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->join('situacao','situacao.id_processo','processo.id_processo')
                                        ->whereIn('empresa.id_empresa',$empresaData)
                                        ->whereIn('processo.id_processo',$processoDat)
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->where('situacao.situacao',true)
                                        ->select(
                                            'situacao.*',
                                            'empresa.sigla as sigla_empresa',
                                            'empresa.descricao as desc_empresa',
                                            'processo.descricao as desc_processo'
                                        )
                                        ->distinct()
                                        ->orderBy('empresa.sigla','asc')
                                        ->orderBy('processo.descricao','asc')
                                        ->orderBy('situacao.descricao','asc')
                                        ->get();
                }
                catch(Exception $erro) {
                    $listaSituacao  =   [];
                }

                $vRetorno   =   [
                    'empresa'   =>  $listaEmpresa,
                    'processo'  =>  $listaProcesso,
                    'tipo'      =>  $listaTipo,
                    'situacao'  =>  $listaSituacao,
                ];

            } // try { ... }
            catch(Exception $erro) {
                $vRetorno   =   [
                    'erro'  =>  [
                        'codigo'    =>  1,
                        'mensagem'  =>  $erro
                    ],
                ];
                return response()->json($vRetorno,500);
            } // catch(Exception $erro) { ... }

            return response()->json($vRetorno,200);
        } // public function filtroData(Request $request) { ... }

        public function filtroTipo(Request $request) {
            $vRetorno   =   [];
            try {
                // Coleta informações do usuário
                $idUsuario  =   $request->input('idUsuario');
                if(is_null($idUsuario)) return response()->json(['erro' => 'O código do usuário não foi informado! Verifique.'],202);

                $validador  =   [];
                foreach(usuario_acesso(intval($idUsuario)) as $reg) {
                    array_push($validador,$reg->id_perfil);
                } // foreach(usuario_acesso(intval($idUsuario)) as $reg) { ... }


                if($validador <= 0) return response()->json(['erro' => 'O código do usuário informado não possui parametrização válida! Verifique.'],202);

                $idProcesso =   $request->input('idProcesso');
                if(is_null($idProcesso)) return response()->json(['erro' => 'O código do processo não foi informado! Verifique.'],202);
                $validador  =   DB::table('processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização válida! Verifique.'],202);
                $validador  =   DB::table('tipo_processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização para tipo válida! Verifique.'],202);

                $listaTipo  =   DB::table('empresa')
                                ->join('processo','processo.id_empresa','empresa.id_empresa')
                                ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                ->where('processo.id_processo',intval($idProcesso))
                                ->where('empresa.situacao',true)
                                ->where('processo.situacao',true)
                                ->where('tipo_processo.situacao',true)
                                ->select(
                                    'tipo_processo.*'
                                )
                                ->distinct()
                                ->orderBy('tipo_processo.ordem','asc')
                                ->orderBy('tipo_processo.descricao','asc')
                                ->get();
                $vRetorno   =   [
                    'tipo'  =>  $listaTipo,
                ];
                return response()->json($vRetorno,200);
            } // try { ... }
            catch(Exception $erro) {
                $vRetorno   =   [
                    'erro'  =>  [
                        'codigo'    =>  1,
                        'mensagem'  =>  $erro
                    ],
                ];
                return response()->json($vRetorno,500);
            } // catch(Exception $erro) { ... }
        } // public function filtroTipo(Request $request) { ... }

        public function filtroQuestao(Request $request) {
            try {
                 // Coleta informações do usuário
                $idUsuario  =   $request->input('idUsuario');
                if(is_null($idUsuario)) return response()->json(['erro' => 'O código do usuário não foi informado! Verifique.'],202);

                
                $idProcesso =   $request->input('idProcesso');
                if(is_null($idProcesso)) return response()->json(['erro' => 'O código do processo não foi informado! Verifique.'],202);
                $validador  =   DB::table('processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização válida! Verifique.'],202);

                $idTipo     =   $request->input('idTipo');
                if(is_null($idTipo)) return response()->json(['erro' => 'O código do tipo não foi informado! Verifique.'],202);
                $validador  =   DB::table('tipo_processo')->where('id_tipo_processo',intval($idTipo))->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do tipo informado não possui parametrização válida! Verifique.'],202);
                $validador  =   DB::table('pergunta_tipo')->where('id_tipo_processo',intval($idTipo))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do tipo informado não possui parametrização para questões válida! Verifique.'],202);

                $questao        =   DB::table('empresa')
                                    ->join('processo','processo.id_empresa','empresa.id_empresa')
                                    ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                    ->join('pergunta_tipo','pergunta_tipo.id_tipo_processo','tipo_processo.id_tipo_processo')
                                    ->where('processo.id_processo',intval($idProcesso))
                                    ->where('tipo_processo.id_tipo_processo',intval($idTipo))
                                    ->where('empresa.situacao',true)
                                    ->where('processo.situacao',true)
                                    ->where('tipo_processo.situacao',true)
                                    ->where('pergunta_tipo.situacao',true)
                                    ->select(
                                        'pergunta_tipo.*'
                                    )
                                    ->distinct()
                                    ->orderBy('pergunta_tipo.ordem','asc')
                                    ->orderBy('pergunta_tipo.descricao','asc')
                                    ->get();

                    $vRetorno   =   [
                        'questao'   =>  $questao,
                        'menorHora' =>  Carbon::now()->addDays(1)->toDateString(),
                    ];
                    return response()->json($vRetorno,200);
            }
            catch(Exception $erro) {
                $vRetorno   =   [
                    'erro'  =>  [
                        'codigo'    =>  1,
                        'mensagem'  =>  $erro
                    ],
                ];
                return response()->json($vRetorno,500);
            }
        } // public function filtroQuestao(Request $request) { ... }
    }
