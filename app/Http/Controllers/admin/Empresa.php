<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class Empresa extends Controller
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
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            } // if($this->verificaPermissao()) { ... }

            try {
                $listaEmpresa   =   DB::table('empresa')
                                    ->orderBy('situacao','desc')
                                    ->orderBy('descricao','asc')
                                    ->get();
            } // try { ... }
            catch(Exception $erro) {
                $listaEmpresa   =   [];
            } // catch(Exception $erro) { ... }

            return view('admin.empresa',[
                'empresas'  =>  $listaEmpresa,
            ]);
        } // public function index(Request $request) { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function insert(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }

            $descricao  =   $request->input('descricao');
            $sigla      =   $request->input('sigla');
            $situacao   =   (intval($request->input('situacao','0')) <= 0) ? false : true;

            // Se não estiver devidamente preenchido ... encerra o cadastro
            if(is_null($descricao) || is_null($sigla)) return redirect()->route('admin.empresa.listar');

            try {
                DB::beginTransaction();
                DB::table('empresa')->insert([
                    'descricao' =>  $descricao,
                    'sigla'     =>  strtoupper($sigla),
                    'situacao'  =>  $situacao,
                    'data_cria' =>  Carbon::now(),
                    'data_alt'  =>  Carbon::now(),
                    'usr_cria'  =>  Auth::user()->id,
                    'usr_alt'   =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.empresa.listar');
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function insert(Request $request) { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function edit(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }
            $id_empresa =   $request->input('id_empresa');
            $descricao  =   $request->input('descricao');
            $sigla      =   $request->input('sigla');
            $situacao   =   (intval($request->input('situacao','0')) <= 0) ? false : true;

            // Se não estiver devidamente preenchido ... encerra o cadastro
            if(is_null($id_empresa) || is_null($descricao) || is_null($sigla)) return redirect()->route('admin.empresa.listar');

            try {
                DB::beginTransaction();
                DB::table('empresa')
                ->where('id_empresa',intval($id_empresa))
                ->update([
                    'descricao' =>  $descricao,
                    'sigla'     =>  strtoupper($sigla),
                    'situacao'  =>  $situacao,
                    'data_alt'  =>  Carbon::now(),
                    'usr_alt'   =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.empresa.listar');
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function edit(Request $request) { ... }
    } // class Empresa extends Controller { ... }
