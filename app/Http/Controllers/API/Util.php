<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;

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
                if(is_null($idUsuario)) return response()->json(['erro' => 'O código do usuário não foi informado! Verifique.'],404);

                $validador  =   DB::table('perfil')->where('id_usuario',intval($idUsuario))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do usuário informado não possui parametrização válida! Verifique.'],404);

                // Coleta os dados para Empresa
                try {
                    $listaEmpresa   =   DB::table('perfil')
                                        ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                        ->where('perfil.id_usuario',intval($idUsuario))
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
                    $listaProcesso  =   DB::table('perfil')
                                        ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->where('perfil.id_usuario',intval($idUsuario))
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->select(
                                            'processo.*'
                                            //'processo.id_processo',
                                            //'empresa.id_empresa',
                                            //'empresa.sigla'
                                        )
                                        ->distinct()
                                        //->orderBy('empresa.descricao','asc')
                                        ->orderBy('processo.descricao','asc')
                                        ->get();
                }
                catch(Exception $erro) {
                    $listaProcesso  =   [];
                }

                // Coleta os dados para Tipo
                try {
                    $listaTipo      =   DB::table('perfil')
                                        ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                        ->where('perfil.id_usuario',intval($idUsuario))
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
                }
                catch(Exception $erro) {
                    $listaTipo      =   [];
                }

                // Coleta os dados para Tipo
                try {
                    $listaSituacao  =   DB::table('perfil')
                                        ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->join('situacao','situacao.id_processo','processo.id_processo')
                                        ->where('perfil.id_usuario',intval($idUsuario))
                                        ->where('situacao.situacao',true)
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->select(
                                            'situacao.*'
                                        )
                                        ->distinct()
                                        //->orderBy('empresa.descricao','asc')
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
                if(is_null($idUsuario)) return response()->json(['erro' => 'O código do usuário não foi informado! Verifique.'],404);

                $validador  =   DB::table('perfil')->where('id_usuario',intval($idUsuario))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do usuário informado não possui parametrização válida! Verifique.'],404);

                $idProcesso =   $request->input('idProcesso');
                if(is_null($idProcesso)) return response()->json(['erro' => 'O código do processo não foi informado! Verifique.'],404);
                $validador  =   DB::table('processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização válida! Verifique.'],404);
                $validador  =   DB::table('tipo_processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização para tipo válida! Verifique.'],404);

                $listaTipo  =   DB::table('perfil')
                                ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                ->join('processo','processo.id_empresa','empresa.id_empresa')
                                ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                ->where('perfil.id_usuario',intval($idUsuario))
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
                if(is_null($idUsuario)) return response()->json(['erro' => 'O código do usuário não foi informado! Verifique.'],404);

                $validador  =   DB::table('perfil')->where('id_usuario',intval($idUsuario))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do usuário informado não possui parametrização válida! Verifique.'],404);

                $idProcesso =   $request->input('idProcesso');
                if(is_null($idProcesso)) return response()->json(['erro' => 'O código do processo não foi informado! Verifique.'],404);
                $validador  =   DB::table('processo')->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do processo informado não possui parametrização válida! Verifique.'],404);

                $idTipo     =   $request->input('idTipo');
                if(is_null($idTipo)) return response()->json(['erro' => 'O código do tipo não foi informado! Verifique.'],404);
                $validador  =   DB::table('tipo_processo')->where('id_tipo_processo',intval($idProcesso))->where('id_processo',intval($idProcesso))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do tipo informado não possui parametrização válida! Verifique.'],404);
                $validador  =   DB::table('pergunta_tipo')->where('id_tipo_processo',intval($idTipo))->count();
                if($validador <= 0) return response()->json(['erro' => 'O código do tipo informado não possui parametrização para questões válida! Verifique.'],404);

                $questao        =   DB::table('perfil')
                                    ->join('empresa','empresa.id_empresa','perfil.id_empresa')
                                    ->join('processo','processo.id_empresa','empresa.id_empresa')
                                    ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                                    ->join('pergunta_tipo','pergunta_tipo.id_tipo_processo','tipo_processo.id_tipo_processo')
                                    ->where('perfil.id_usuario',intval($idUsuario))
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
                        'questao'  =>  $questao,
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
