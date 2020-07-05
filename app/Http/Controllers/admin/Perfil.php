<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class Perfil extends Controller
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
            $perfil =   DB::table('perfil')
                        ->orderBy('situacao','desc')
                        ->orderBy('descricao','asc')
                        ->get();

            return view('admin.perfil',[
                'perfis'    =>  $perfil,
            ]);
        } // public function index(Request $request) { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function cad(Request $request) {
            $descricao  =   $request->input('descricao');
            $situacao   =   intval($request->input('situacao','0')) == 1 ? true : false;

            if(is_null($descricao)) return redirect()->route('raiz');

            DB::beginTransaction();
            DB::table('perfil')
            ->insert([
                'descricao' =>  $descricao,
                'situacao'  =>  $situacao,
                'data_cria' =>  Carbon::now(),
                'data_alt'  =>  Carbon::now(),
                'usr_cria'  =>  Auth::user()->id,
                'usr_alt'   =>  Auth::user()->id,
            ]);
            DB::commit();

            return $this->index($request);
        } // public function cad(Request $request) { ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function iddx(Request $request) {
            $idPerfil   =   $request->input('idPerfilBPMS');
            if(is_null($idPerfil)) return redirect()->route('admin.perfil.lista');

            $processo   =   DB::table('processo')
                            ->where('situacao',true)
                            ->get();

            $perfilAcc  =   DB::table('perfil_acesso')
                            ->where('id_perfil',intval($idPerfil))
                            ->get();

            return view('admin.perfilAcesso',[
                'processos' =>  $processo,
                'perfis'    =>  $perfilAcc,
                'idPerfil'  =>  intval($idPerfil),
            ]);
        } // public function iddx(Request $request) { ... }

        public function caad(Request $request) {
            try {
                $idPerfil   =   $request->input('id_perfil');
                $idProcesso =   $request->input('id_processo');

                if(is_null($idPerfil) || is_null($idProcesso)) return redirect()->route('raiz');

                DB::beginTransaction();
                DB::table('perfil_acesso')
                ->insert([
                    'id_perfil'     =>  intval($idPerfil),
                    'id_processo'   =>  intval($idProcesso),
                    'data_cria'     =>  Carbon::now(),
                    'data_alt'      =>  Carbon::now(),
                    'usr_cria'      =>  Auth::user()->id,
                    'usr_alt'       =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.perfil.acesso.listar',[
                    'idPerfilBPMS'  =>  intval($idPerfil),
                ]);
            }
            catch(Exception $erro) {
                DB::rollback();
                return redirect()->route('admin.perfil.listar');
            } // catch(...){...}
        } // public function caad(Request $request) { ... }

        public function reem(Request $request) {
            try {
                $idPerfil   =   $request->input('idPerfilBPMS');
                $idProcesso =   $request->input('id_perfil');

                DB::table('perfil_acesso')
                ->where('id_perfil_acesso',intval($idPerfil))
                ->delete();

                return redirect()->route('admin.perfil.acesso.listar',[
                    'idPerfilBPMS'  =>  intval($idProcesso),
                ]);
            }
            catch(Exception $erro)  {
                DB::rollback();
                return redirect()->route('admin.perfil.listar');
            } // catch(Exception $erro)  { ... }
        } // public function reem(Request $request) { ... }
    }
