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
        function usuario_subordinado($idUsuario, $idProcesso) {
            $retorno        =   [];

            $subordinados   =   DB::table('perfil_usuario')
                                ->join('perfil_acesso','perfil_acesso.id_perfil','perfil_usuario.id_perfil')
                                ->where('perfil_usuario.id_superior',$idUsuario)
                                ->where('perfil_acesso.id_processo',$idProcesso)
                                ->select('perfil_usuario.*')
                                ->get();

            foreach($subordinados as $sub) {
                if(!is_null($sub->id_usuario)) {
                    $usuario =  DB::table('users')->where('id',$sub->id_usuario)->first();

                    if(!in_array($usuario,$retorno)) {
                        array_push($retorno,$usuario);
                    }
                } // if(!is_null($sub->id_usuario)) { ... }
            } // foreach($subordinados as $sub) { ... }

            return $retorno;
        } // function usuario_subordinado($idUsuario) { ... }
    } // if(!function_exists('usuario_subordinado')) { ... }
