<?php
    if(!function_exists('usuario_acesso')) {
        function usuario_acesso($idUsuario) {
            $retorno    =   [];

            $acessos    =   DB::table('perfil_usuario')
                            ->join('perfil','perfil.id_perfil','perfil_usuario.id_perfil')
                            ->join('perfil_acesso','perfil_acesso.id_perfil','perfil.id_perfil')
                            ->where('perfil_usuario.id_usuario',$idUsuario)
                            ->select(
                                'perfil_acesso.id_empresa',
                                'perfil_acesso.id_processo',
                                'perfil.id_perfil'
                            )
                            ->get()
                            ;

            foreach ($acessos as $acesso) {
                $tmpRetorno     =   [];
                // valida se o acesso é completo
                if(is_null($acesso->id_empresa) && is_null($acesso->id_processo)) {
                    $dataTMPdata    =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->select('empresa.id_empresa','processo.id_processo')
                                        ->get();

                    foreach($dataTMPdata as $reg1) {
                        $tmpRetorno =   (object)[
                            'id_empresa'    =>  $reg1->id_empresa,
                            'id_processo'   =>  $reg1->id_processo,
                            'id_perfil'     =>  $acesso->id_perfil,
                        ];

                        if(!in_array($tmpRetorno, $retorno)) {
                            array_push($retorno, $tmpRetorno);
                        } // if(!in_array($tmpRetorno, $retorno)) { ... }
                    } // foreach($dataTMPdata as $reg1) { ... }
                }
                elseif(!is_null($acesso->id_empresa) && is_null($acesso->id_processo)) {
                    $dataTMPdata    =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->where('empresa.id_empresa',$acesso->id_empresa)
                                        ->select('empresa.id_empresa','processo.id_processo')
                                        ->get();

                    foreach($dataTMPdata as $reg1) {
                        $tmpRetorno =   (object)[
                            'id_empresa'    =>  $reg1->id_empresa,
                            'id_processo'   =>  $reg1->id_processo,
                            'id_perfil'     =>  $acesso->id_perfil,
                        ];

                        if(!in_array($tmpRetorno, $retorno)) {
                            array_push($retorno, $tmpRetorno);
                        } // if(!in_array($tmpRetorno, $retorno)) { ... }
                    } // foreach($dataTMPdata as $reg1) { ... }
                }
                elseif(is_null($acesso->id_empresa) && !is_null($acesso->id_processo)) {
                    $dataTMPdata    =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->where('processo.id_processo',$acesso->id_processo)
                                        ->select('empresa.id_empresa','processo.id_processo')
                                        ->get();

                    foreach($dataTMPdata as $reg1) {
                        $tmpRetorno =   (object)[
                            'id_empresa'    =>  $reg1->id_empresa,
                            'id_processo'   =>  $reg1->id_processo,
                            'id_perfil'     =>  $acesso->id_perfil,
                        ];

                        if(!in_array($tmpRetorno, $retorno)) {
                            array_push($retorno, $tmpRetorno);
                        } // if(!in_array($tmpRetorno, $retorno)) { ... }
                    } // foreach($dataTMPdata as $reg1) { ... }
                }
                elseif(!is_null($acesso->id_empresa) && !is_null($acesso->id_processo)) {
                    $dataTMPdata    =   DB::table('empresa')
                                        ->join('processo','processo.id_empresa','empresa.id_empresa')
                                        ->where('empresa.situacao',true)
                                        ->where('processo.situacao',true)
                                        ->where('empresa.id_empresa',$acesso->id_empresa)
                                        ->where('processo.id_processo',$acesso->id_processo)
                                        ->select('empresa.id_empresa','processo.id_processo')
                                        ->get();

                    foreach($dataTMPdata as $reg1) {
                        $tmpRetorno =   (object)[
                            'id_empresa'    =>  $reg1->id_empresa,
                            'id_processo'   =>  $reg1->id_processo,
                            'id_perfil'     =>  $acesso->id_perfil,
                        ];

                        if(!in_array($tmpRetorno, $retorno)) {
                            array_push($retorno, $tmpRetorno);
                        } // if(!in_array($tmpRetorno, $retorno)) { ... }
                    } // foreach($dataTMPdata as $reg1) { ... }
                }
            } // foreach ($acessos as $acesso) { ... }

            return $retorno;
        } // function usuario_acesso($idUsuario) { ... }
    } // if(!function_exists('usuario_acesso')) { ... }

    if(!function_exists('usuario_subordinado')) {
        function usuario_subordinado($idUsuario, $idEmpresa, $idProcesso) {
            $retorno        =   DB::table('arvore_usuario')
                                ->where('id_usuario_superior',$idUsuario);

            if(!is_null($idEmpresa)) {
                $retorno    =   $retorno->where('arvore_usuario.id_empresa',$idEmpresa);
            }

            if(!is_null($idProcesso)) {
                $retorno    =   $retorno->where('arvore_usuario.id_processo',$idProcesso);
            }
        
            $retorno        =   $retorno->get();
            return $return;
        } // function usuario_subordinado($idUsuario) { ... }
    } // if(!function_exists('usuario_subordinado')) { ... }
