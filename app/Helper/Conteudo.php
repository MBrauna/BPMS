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

    if(!function_exists('consulta_subordinados_todos')) {
        function consulta_subordinados_todos($idUsuario) {
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

                $dataref    =   consulta_subordinados_todos($data1->id);

                foreach($dataref as $data2) {
                    if(!in_array($data2,$retorno)) {
                        array_push($retorno, $data2);
                    } // if(!in_array($data1,$retorno)) { ... }
                } // foreach($dataref as $data2) { ... }
            } // foreach($reg1 as $data1) { ... }

            return $retorno;
        } // function consulta_subordinados_todos($idUsuario) { ... }
    } // if(!function_exists('consulta_subordinados')) { ... }