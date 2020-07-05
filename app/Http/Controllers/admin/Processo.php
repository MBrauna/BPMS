<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use Carbon\Carbon;
    use DB;

    class Processo extends Controller
    {
        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        private function verificaPermissao() {
            try {
                if(!Auth::user()->administrador) return true;
                return false;
            } // try { ... }
            catch(Exception $erro) {
                return true;
            } // catch(Exception $erro) { ... }
        } // private function verificaPermissao() { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function index(Request $request) {
            try {
                if($this->verificaPermissao()) {
                    return redirect()->route('raiz');
                }

                $idEmpresa      =   $request->input('idEmpresaBPMS');

                if(is_null($idEmpresa)) return redirect()->route('admin.empresa.listar');

                $dadosEmpresa   =   DB::table('empresa')
                                    ->where('empresa.id_empresa',intval($idEmpresa))
                                    ->first();
                
                if(is_null($dadosEmpresa) || !isset($dadosEmpresa) || !isset($dadosEmpresa->id_empresa)) return redirect()->route('admin.empresa.listar');

                $listaProcesso  =   DB::table('processo')
                                    ->where('processo.id_empresa',intval($idEmpresa))
                                    ->orderBy('processo.situacao','desc')
                                    ->orderBy('processo.descricao','asc')
                                    ->get();
                
                $listaUsuario   =   DB::table('users')->orderBy('name','asc')->get();
                
                return view('admin.processo',[
                    'processos' =>  $listaProcesso,
                    'empresa'   =>  $dadosEmpresa,
                    'usuarios'   =>  $listaUsuario,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function index(Request $request) { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function cad(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            } // if($this->verificaPermissao()) { ... }

            $id_empresa =   $request->input('id_empresa');
            $id_situacao=   intval($request->input('id_situacao','1'));
            $descricao  =   $request->input('descricao');
            $dono_proc  =   $request->input('id_usuario_processo');
            $sigla      =   $request->input('sigla');
            $icone      =   $request->input('icone','fas fa-circle');
            $situacao   =   (intval($request->input('situacao','0')) <= 0) ? false : true;

            // Se não estiver devidamente preenchido ... encerra o cadastro
            if(is_null($id_empresa) || is_null($descricao) || is_null($sigla)) return redirect()->route('admin.empresa.listar');

            $empresa    =   DB::table('empresa')
                            ->where('empresa.id_empresa',intval($id_empresa))
                            ->first();

            if(!isset($empresa->id_empresa) || is_null($empresa->id_empresa)) return redirect()->route('admin.empresa.listar');

            try {
                DB::beginTransaction();
                DB::table('processo')->insert([
                    'id_empresa'            =>  $empresa->id_empresa,
                    'descricao'             =>  $descricao,
                    'sigla'                 =>  strtoupper($sigla),
                    'icone'                 =>  strtolower($icone),
                    'id_usr_responsavel'    => (is_null($dono_proc) ? null : intval($dono_proc)),
                    'situacao'              =>  $situacao,
                    'data_cria'             =>  Carbon::now(),
                    'data_alt'              =>  Carbon::now(),
                    'usr_cria'              =>  Auth::user()->id,
                    'usr_alt'               =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.processo.listar',[
                    'idEmpresaBPMS' =>  $empresa->id_empresa,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                return redirect()->route('admin.processo.listar',[
                    'idEmpresaBPMS' =>  $empresa->id_empresa,
                ]);
            } // catch(Exception $erro) { ... }
        } // public function cad(Request $request) { ... }

        public function edit(Request $request) {
            try {
                if($this->verificaPermissao()) {
                    return redirect()->route('raiz');
                } // if($this->verificaPermissao()) { ... }
    
                $id_empresa =   $request->input('id_empresa');
                $id_processo=   $request->input('id_processo');
                $id_situacao=   intval($request->input('id_situacao','1'));
                $descricao  =   $request->input('descricao');
                $dono_proc  =   $request->input('id_usuario_processo');
                $sigla      =   $request->input('sigla');
                $icone      =   $request->input('icone','fas fa-circle');
                $situacao   =   (intval($request->input('situacao','0')) <= 0) ? false : true;
    
                // Se não estiver devidamente preenchido ... encerra o cadastro
                if(is_null($id_empresa) || is_null($descricao) || is_null($sigla) || is_null($id_processo)) return redirect()->route('admin.empresa.listar');
    
                $empresa    =   DB::table('empresa')
                                ->where('empresa.id_empresa',intval($id_empresa))
                                ->first();
                if(!isset($empresa->id_empresa) || is_null($empresa->id_empresa)) return redirect()->route('admin.empresa.listar');

                $processo   =   DB::table('processo')
                                ->where('processo.id_processo',intval($id_processo))
                                ->first();
                if(!isset($processo->id_processo) || is_null($processo->id_processo)) return redirect()->route('admin.empresa.listar');

                DB::beginTransaction();
                DB::table('processo')
                ->where('processo.id_processo',$processo->id_processo)
                ->where('processo.id_empresa',$empresa->id_empresa)
                ->update([
                    'descricao'             =>  $descricao,
                    'sigla'                 =>  strtoupper($sigla),
                    'icone'                 =>  strtolower($icone),
                    'id_usr_responsavel'    => (is_null($dono_proc) ? null : intval($dono_proc)),
                    'situacao'              =>  $situacao,
                    'data_alt'              =>  Carbon::now(),
                    'usr_alt'               =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.processo.listar',[
                    'idEmpresaBPMS' =>  $empresa->id_empresa,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                return redirect()->route('admin.processo.listar',[
                    'idEmpresaBPMS' =>  $empresa->id_empresa,
                ]);
            } // catch(Exception $erro) { ... }
        } // public function edit(Request $request) { ... }
    }
