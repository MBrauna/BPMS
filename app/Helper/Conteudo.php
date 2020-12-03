<?php
    if(!function_exists('consulta_empresa')) {
        function consulta_empresa($idEmpresa) {
            return  DB::table('empresa')
                    ->where('empresa.id_empresa',$idEmpresa)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_processo')) {
        function consulta_processo($idProcesso) {
            return  DB::table('processo')
                    ->where('processo.id_processo',$idProcesso)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_tipo')) {
        function consulta_tipo($idTipo) {
            return  DB::table('tipo_processo')
                    ->where('tipo_processo.id_tipo_processo',$idTipo)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_situacao')) {
        function consulta_situacao($idSituacao) {
            return  DB::table('situacao')
                    ->where('situacao.id_situacao',$idSituacao)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_pergunta')) {
        function consulta_pergunta($idPergunta) {
            return  DB::table('pergunta_tipo')
                    ->where('pergunta_tipo.id_pergunta_tipo',$idPergunta)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_usuario')) {
        function consulta_usuario($idUsuario) {
            return  DB::table('users')
                    ->where('users.id',$idUsuario)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_perfil')) {
        function consulta_perfil($idPerfil) {
            return  DB::table('perfil')
                    ->where('perfil.id_perfil',$idPerfil)
                    ->first();
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }


    if(!function_exists('consulta_tipo_periodico')) {
        function consulta_tipo_periodico($idTipo) {
            $retorno = '';
            switch($idTipo) {
                case 1:
                    $retorno =  'Entrada';
                    break;
                case 2:
                    $retorno =  'Saída';
                    break;
                default:
                    $retorno =  'NÃO IDENTIFICADO';
                    break;
            }

            return $retorno;
        } // function consulta_tipo_periodico($idEmpresa) { ... }
    } // if(!function_exists('consulta_tipo_periodico')) { ... }

    if(!function_exists('consulta_tipo_objeto')) {
        function consulta_tipo_objeto($idTipo) {
            $retorno = '';
            switch($idTipo) {
                case 1:
                    $retorno =  'Documento digitalizado';
                    break;
                default:
                    $retorno =  'NÃO IDENTIFICADO';
                    break;
            }

            return $retorno;
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_meio')) {
        function consulta_meio($idTipo) {
            $retorno = '';
            switch($idTipo) {
                case 1:
                    $retorno =  'E-mail';
                    break;
                default:
                    $retorno =  'NÃO IDENTIFICADO';
                    break;
            }

            return $retorno;
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_periodicidade')) {
        function consulta_periodicidade($idTipo) {
            $retorno = '';
            switch($idTipo) {
                case 1:
                    $retorno =  'Dia(s)';
                    break;
                case 2:
                    $retorno =  'Semana(s)';
                    break;
                case 3:
                    $retorno =  'Quinzena(s)';
                    break;
                case 4:
                    $retorno =  'Mês(es)';
                    break;
                case 5:
                    $retorno =  'Bimestre(s)';
                    break;
                case 6:
                    $retorno =  'Semestre(s)';
                    break;
                case 7:
                    $retorno =  'Ano(s)';
                    break;
                default:
                    $retorno =  'NÃO IDENTIFICADO';
                    break;
            }

            return $retorno;
        } // function consulta_empresa($idEmpresa) { ... }
    } // if(!function_exists('consulta_empresa')) { ... }

    if(!function_exists('consulta_subordinados_todos')) {
        function consulta_subordinados_todos($idUsuario, $primeiro = true) {
            $retorno    =   [];

            $selfUser   =   DB::table('users')->where('id',$idUsuario)->first();
            if(!isset($selfUser) && is_null($selfUser)) return [];
            array_push($retorno, $selfUser);

            $reg1       =   DB::table('perfil_usuario')
                            ->join('users','users.id','perfil_usuario.id_usuario')
                            ->where('id_superior',$selfUser->id)
                            ->select('users.*')
                            ->distinct()
                            ->get();
            
            foreach($reg1 as $data1) {
                if(!in_array($data1,$retorno)) {
                    array_push($retorno, $data1);
                } // if(!in_array($data1,$retorno)) { ... }


                if($primeiro && $idUsuario == $data1->id) {
                    $dataref    =   consulta_subordinados_todos($data1->id, false);
                    foreach($dataref as $data2) {
                        if(!in_array($data2,$retorno)) {
                            array_push($retorno, $data2);
                        } // if(!in_array($data1,$retorno)) { ... }
                    } // foreach($dataref as $data2) { ... }
                }
            } // foreach($reg1 as $data1) { ... }

            return $retorno;
        } // function consulta_subordinados_todos($idUsuario) { ... }
    } // if(!function_exists('consulta_subordinados')) { ... }

    if(!function_exists('consulta_grafico')) {
        function consulta_grafico($empresa, $grafico) {

            $quantidade     =   5;

            $retorno        =   (object)[
                'type'      =>  'line',
                'data'      =>  (object)[
                    'labels'    =>  [],
                    'datasets'  =>  [],
                ],
                'options'   =>  (object)[
                    'legend'    =>  (object)[
                        'display'   =>  true,
                    ],
                    'title'     =>  (object)[
                        'display'   =>  true,
                        'text'      =>  ['Gráfico não disponível']
                    ],
                    'scales'    =>  [
                        'xAxes'     =>  [
                            (object)[
                                'gridLines' =>  (object)[
                                    'display'   =>  false,
                                ]
                            ]
                        ],
                        'yAxes' =>  [
                            (object)[
                                'gridLines' =>  (object)[
                                    'display'   =>  false,
                                ],
                                'ticks' =>  (object)[
                                    'beginAtZero' => true,
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            if(is_null($empresa) || is_null($grafico)) return json_encode($retorno);


            if($grafico == 1) {
                $retorno->options->title->text  =   ['Comportamento das Solicitações de Serviços'];

                $ssCriada       =   [];
                $ssAtrasada     =   [];
                $ssConcluida    =   [];
                $ssSaldo        =   [];

                $dataInicial    =   Carbon\Carbon::now()->subDays(31)->startOfDay();
                $dataFinal      =   Carbon\Carbon::now()->startOfDay();
                $dataExec       =   Carbon\Carbon::now()->subDays(31)->startOfDay();

                while($dataExec->lessThanOrEqualTo($dataFinal)) {
                    $dataExec       =   $dataExec->copy()->startOfDay();
                    $inicioData     =   $dataExec->copy()->startOfDay();
                    $finalData      =   $dataExec->copy()->endOfDay();

                    if($finalData->gt(Carbon\Carbon::now())) {
                        $finalData  =   Carbon\Carbon::now();
                    } // if($finalData->gt(Carbon\Carbon::now())) { .. }

                    # Registros para lihas
                    array_push($retorno->data->labels,$dataExec->copy()->format('d/m/y'));
                    $tmpCriado      =   DB::table('chamado')->where('id_empresa',$empresa)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$empresa)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData)->where('data_criacao','<=',$finalData)->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$empresa)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$empresa)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count('id_chamado');

                    # Adiciona os dados no conteudo necessário
                    array_push($ssCriada,$tmpCriado);
                    array_push($ssAtrasada,$tmpAtrasada);
                    array_push($ssConcluida,$tmpConcluida);
                    array_push($ssSaldo,$tmpSaldo);
                    
                    # Incrementa a data para o próximo dia
                    $dataExec   =   $dataExec->addDays(1)->startOfDay();
                } // while($dataExec->lessThanOrEqualTo($dataFinal)) { ... }

                $retorno->data->datasets    =   [
                    (object)[
                        'label'             =>  'Criadas',
                        'borderColor'       =>  '#348EDA',
                        'backgroundColor'   =>  '#348EDA',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssCriada,
                    ],
                    (object)[
                        'label'             =>  'Atrasadas',
                        'borderColor'       =>  '#db0000',
                        'backgroundColor'   =>  '#db0000',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssAtrasada,
                    ],
                    (object)[
                        'label'             =>  'Concluídas',
                        'borderColor'       =>  '#00c90d',
                        'backgroundColor'   =>  '#00c90d',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssConcluida,
                    ],
                    (object)[
                        'label'             =>  'Saldo',
                        'borderColor'       =>  '#ff730f',
                        'backgroundColor'   =>  '#ff730f',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssSaldo,
                    ],
                ];

                return json_encode($retorno);

            }
            elseif($grafico == 2) {
                $retorno->options->title->text  =   ['Comportamento das Solicitações de Serviços'];

                $ssCriada       =   [];
                $ssAtrasada     =   [];
                $ssConcluida    =   [];
                $ssSaldo        =   [];

                $dataInicial    =   Carbon\Carbon::now()->subMonths(12)->startOfMonth()->startOfDay();
                $dataFinal      =   Carbon\Carbon::now()->startOfDay();
                $dataExec       =   Carbon\Carbon::now()->subMonths(12)->startOfDay();

                while($dataExec->lessThanOrEqualTo($dataFinal)) {
                    $dataExec       =   $dataExec->copy()->startOfMonth();
                    $inicioData     =   $dataExec->copy()->startOfMonth()->startOfDay();
                    $finalData      =   $dataExec->copy()->endOfMonth()->endOfDay();

                    if($finalData->gt(Carbon\Carbon::now())) {
                        $finalData  =   Carbon\Carbon::now();
                    } // if($finalData->gt(Carbon\Carbon::now())) { .. }

                    # Registros para lihas
                    array_push($retorno->data->labels,$dataExec->copy()->format('m/y'));
                    /*$tmpCriado      =   DB::table('chamado')->where('id_empresa',$empresa)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$empresa)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData->startOfDay())->where('data_criacao','<=',$finalData->startOfDay())->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$empresa)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$empresa)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count();*/
                    $tmpCriado      =   DB::table('chamado')->where('id_empresa',$empresa)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$empresa)->where('data_criacao','<=',$finalData)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData)->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$empresa)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$empresa)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count('id_chamado');

                    # Adiciona os dados no conteudo necessário
                    array_push($ssCriada,$tmpCriado);
                    array_push($ssAtrasada,$tmpAtrasada);
                    array_push($ssConcluida,$tmpConcluida);
                    array_push($ssSaldo,$tmpSaldo);
                    
                    # Incrementa a data para o próximo dia
                    $dataExec   =   $dataExec->addMonths(1)->startOfMonth()->startOfDay();
                } // while($dataExec->lessThanOrEqualTo($dataFinal)) { ... }

                $retorno->data->datasets    =   [
                    (object)[
                        'label'             =>  'Criadas',
                        'borderColor'       =>  '#348EDA',
                        'backgroundColor'   =>  '#348EDA',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssCriada,
                    ],
                    (object)[
                        'label'             =>  'Atrasadas',
                        'borderColor'       =>  '#db0000',
                        'backgroundColor'   =>  '#db0000',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssAtrasada,
                    ],
                    (object)[
                        'label'             =>  'Concluídas',
                        'borderColor'       =>  '#00c90d',
                        'backgroundColor'   =>  '#00c90d',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssConcluida,
                    ],
                    (object)[
                        'label'             =>  'Saldo',
                        'borderColor'       =>  '#ff730f',
                        'backgroundColor'   =>  '#ff730f',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  false,
                        'data'              =>  $ssSaldo,
                    ],
                ];

                return json_encode($retorno);
            }
            elseif($grafico == 3) {
                $retorno->type  =   'bar';
                $retorno->options->title->text  =   ['Solicitação de Serviços','(maior número de atendimentos atrasados)'];

                $processos  =   DB::table('chamado')
                                ->join('processo','processo.id_processo','chamado.id_processo')
                                ->where('chamado.id_empresa',$empresa)
                                ->whereNull('chamado.data_conclusao')
                                ->where('chamado.data_vencimento','<=',Carbon\Carbon::now())
                                ->select(
                                    DB::raw('count(1) as atrasadas'),
                                    'processo.sigla',
                                    'processo.id_processo',
                                    'processo.id_empresa'
                                )
                                ->groupBy(['processo.sigla','processo.id_processo','processo.id_empresa'])
                                ->orderBy('atrasadas','desc')
                                ->get();
                
                $ssCriada       =   [];
                $ssAtrasada     =   [];
                $ssConcluida    =   [];
                $ssSaldo        =   [];

                $tmpQuantidade  =   0;
                $inicioData     =   Carbon\Carbon::now()->subMonths(12)->startOfDay();
                $finalData      =   Carbon\Carbon::now();
                $dataExec       =   Carbon\Carbon::now();

                foreach($processos as $processo) {
                    if($tmpQuantidade > $quantidade ) break;
                    $tmpQuantidade = $tmpQuantidade+1;

                    array_push($retorno->data->labels,$processo->sigla);
                    /*$tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   $processo->atrasadas; //DB::table('chamado')->where('id_empresa',$processo->id_processo)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData->startOfDay())->where('data_criacao','<=',$finalData->startOfDay())->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$processo->id_empresa)
                                        ->where('id_processo',$processo->id_processo)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count();*/
                    $tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$dataExec)->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$processo->id_empresa)
                                        ->where('id_processo',$processo->id_processo)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count('id_chamado');

                    # Adiciona os dados no conteudo necessário
                    array_push($ssCriada,$tmpCriado);
                    array_push($ssAtrasada,$tmpAtrasada);
                    array_push($ssConcluida,$tmpConcluida);
                    array_push($ssSaldo,$tmpSaldo);
                }

                $retorno->data->datasets    =   [
                    (object)[
                        'label'             =>  'Criadas',
                        'borderColor'       =>  '#348EDA',
                        'backgroundColor'   =>  '#348EDA',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssCriada,
                    ],
                    (object)[
                        'label'             =>  'Atrasadas',
                        'borderColor'       =>  '#db0000',
                        'backgroundColor'   =>  '#db0000',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssAtrasada,
                    ],
                    (object)[
                        'label'             =>  'Concluídas',
                        'borderColor'       =>  '#00c90d',
                        'backgroundColor'   =>  '#00c90d',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssConcluida,
                    ],
                    (object)[
                        'label'             =>  'Saldo',
                        'borderColor'       =>  '#ff730f',
                        'backgroundColor'   =>  '#ff730f',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssSaldo,
                    ],
                ];

                return json_encode($retorno);
            }
            elseif($grafico == 4) {
                $retorno->type  =   'bar';
                $retorno->options->title->text  =   ['Solicitação de Serviços','(maior saldo de solicitações aguardando atendimento)'];
                
                $processos  =   DB::table('chamado')
                                ->join('processo','processo.id_processo','chamado.id_processo')
                                ->where('chamado.id_empresa',$empresa)
                                ->where('processo.id_empresa',$empresa)
                                ->where('processo.situacao',true)
                                ->whereNull('chamado.data_conclusao')
                                ->select(
                                    DB::raw('count(1) as aguardando_atendimento'),
                                    'processo.sigla',
                                    'processo.id_processo',
                                    'processo.id_empresa'
                                )
                                ->groupBy(['processo.sigla','processo.id_processo','processo.id_empresa'])
                                ->orderBy('aguardando_atendimento','desc')
                                ->get();
                
                $ssCriada       =   [];
                $ssAtrasada     =   [];
                $ssConcluida    =   [];
                $ssSaldo        =   [];

                $tmpQuantidade  =   0;
                $inicioData     =   Carbon\Carbon::now()->subMonths(12)->startOfDay();
                $finalData      =   Carbon\Carbon::now();
                $dataExec       =   Carbon\Carbon::now();

                foreach($processos as $processo) {
                    if($tmpQuantidade > $quantidade ) break;
                    $tmpQuantidade = $tmpQuantidade+1;

                    /*$tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData->startOfDay())->where('data_criacao','<=',$finalData->startOfDay())->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   $processo->aguardando_atendimento;*/
                    $tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData)->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$processo->id_empresa)
                                        ->where('id_processo',$processo->id_processo)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count('id_chamado');
                    

                    if($tmpSaldo > 0) {
                        array_push($retorno->data->labels,$processo->sigla);
                        # Adiciona os dados no conteudo necessário
                        array_push($ssCriada,$tmpCriado);
                        array_push($ssAtrasada,$tmpAtrasada);
                        array_push($ssConcluida,$tmpConcluida);
                        array_push($ssSaldo,$tmpSaldo);
                    }
                }

                $retorno->data->datasets    =   [
                    (object)[
                        'label'             =>  'Criadas',
                        'borderColor'       =>  '#348EDA',
                        'backgroundColor'   =>  '#348EDA',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssCriada,
                    ],
                    (object)[
                        'label'             =>  'Atrasadas',
                        'borderColor'       =>  '#db0000',
                        'backgroundColor'   =>  '#db0000',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssAtrasada,
                    ],
                    (object)[
                        'label'             =>  'Concluídas',
                        'borderColor'       =>  '#00c90d',
                        'backgroundColor'   =>  '#00c90d',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssConcluida,
                    ],
                    (object)[
                        'label'             =>  'Saldo',
                        'borderColor'       =>  '#ff730f',
                        'backgroundColor'   =>  '#ff730f',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssSaldo,
                    ],
                ];

                return json_encode($retorno);
            }
            elseif($grafico == 5) {
                $retorno->type  =   'bar';
                $retorno->options->title->text  =   ['Solicitação de Serviços','(processo que mais recebe solicitações)'];

                $processos  =   DB::table('chamado')
                                ->join('processo','processo.id_processo','chamado.id_processo')
                                ->where('chamado.id_empresa',$empresa)
                                ->where('processo.id_empresa',$empresa)
                                ->where('processo.situacao',true)
                                ->where('chamado.data_criacao','<=',Carbon\Carbon::now())
                                //->whereNull('chamado.data_conclusao')
                                ->select(
                                    DB::raw('count(1) as criada'),
                                    'processo.sigla',
                                    'processo.id_processo',
                                    'processo.id_empresa'
                                )
                                ->groupBy(['processo.sigla','processo.id_processo','processo.id_empresa'])
                                ->orderBy('criada','desc')
                                ->get();
                
                $ssCriada       =   [];
                $ssAtrasada     =   [];
                $ssConcluida    =   [];
                $ssSaldo        =   [];

                $tmpQuantidade  =   0;
                $inicioData     =   Carbon\Carbon::now()->subMonths(12)->startOfDay();
                $finalData      =   Carbon\Carbon::now();
                $dataExec       =   Carbon\Carbon::now();

                foreach($processos as $processo) {
                    if($tmpQuantidade > $quantidade ) break;
                    $tmpQuantidade = $tmpQuantidade+1;

                    array_push($retorno->data->labels,$processo->sigla);
                    /*$tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    //$tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData->startOfDay())->where('data_criacao','<=',$finalData->startOfDay())->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData->copy()->endOfDay())->where('data_criacao','<=',$finalData->copy()->endOfDay())->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   $processo->criada;*/
                    $tmpCriado      =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->where('data_criacao','<=',$finalData)->where('data_criacao','>=',$inicioData)->count();
                    $tmpAtrasada    =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNull('data_conclusao')->where('data_vencimento','<=',$finalData)->count();
                    $tmpConcluida   =   DB::table('chamado')->where('id_empresa',$processo->id_empresa)->where('id_processo',$processo->id_processo)->whereNotNull('data_conclusao')->where('data_conclusao','>=',$inicioData)->where('data_conclusao','<=',$finalData)->count();
                    $tmpSaldo       =   DB::table('chamado')
                                        ->where('id_empresa',$processo->id_empresa)
                                        ->where('id_processo',$processo->id_processo)
                                        ->where(function($query) use ($inicioData, $finalData, $dataExec){
                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNull('chamado.data_conclusao');
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });

                                            $query->orWhere(function($query1) use($inicioData, $finalData, $dataExec) {
                                                $query1->whereNotNull('chamado.data_conclusao');
                                                $query1->where('chamado.data_conclusao','>=',$finalData);
                                                //$query1->where('chamado.data_criacao','>=',$inicioData);
                                                $query1->where('chamado.data_criacao','<=',$finalData);
                                            });
                                        })
                                        ->count('id_chamado');
                    

                    # Adiciona os dados no conteudo necessário
                    array_push($ssCriada,$tmpCriado);
                    array_push($ssAtrasada,$tmpAtrasada);
                    array_push($ssConcluida,$tmpConcluida);
                    array_push($ssSaldo,$tmpSaldo);
                }

                $retorno->data->datasets    =   [
                    (object)[
                        'label'             =>  'Criadas',
                        'borderColor'       =>  '#348EDA',
                        'backgroundColor'   =>  '#348EDA',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssCriada,
                    ],
                    (object)[
                        'label'             =>  'Atrasadas',
                        'borderColor'       =>  '#db0000',
                        'backgroundColor'   =>  '#db0000',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssAtrasada,
                    ],
                    (object)[
                        'label'             =>  'Concluídas',
                        'borderColor'       =>  '#00c90d',
                        'backgroundColor'   =>  '#00c90d',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssConcluida,
                    ],
                    (object)[
                        'label'             =>  'Saldo',
                        'borderColor'       =>  '#ff730f',
                        'backgroundColor'   =>  '#ff730f',//'rgba(0, 0, 0, 0)',
                        'fill'              =>  true,
                        'data'              =>  $ssSaldo,
                    ],
                ];
                
                return json_encode($retorno);
            }
            else {
                $retorno->type  =   'bar';
                $retorno->options->title->text  =   'Gráfico escolhido está indisponível';

                return json_encode($retorno);
            }
            


            return json_encode($retorno);
        }
    } // if(!function_exists('consulta_grafico')) { ... }