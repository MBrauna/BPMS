<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use Carbon\Carbon;
    use DB;

    class Acesso extends Controller
    {
        public function index(Request $request) {
            $idUsuario  =   $request->input('idUsuarioBPMS');
            if(is_null($idUsuario)) return redirect()->route('raiz');

            $usuario    =   DB::table('users')->where('id',intval($idUsuario))->first();

            $acesso     =   DB::table('perfil_usuario')
                            ->where('id_usuario',intval($idUsuario))
                            ->orderBy('id_perfil','asc')
                            ->get();

            $perfil     =   DB::table('perfil')
                            ->where('situacao',true)
                            ->get();

            return view('admin.acesso',[
                'usuario'   =>  $usuario,
                'acessos'   =>  $acesso,
                'perfis'    =>  $perfil,
            ]);
        } // public function index(Request $request) { ... }

        public function remove(Request $request) {
            $idAcesso   =   $request->input('id_perfil_usuario');
            $idUsuario  =   $request->input('id_usuario');
            if(is_null($idAcesso)) return redirect()->route('admin.usuario.listar');

            DB::beginTransaction();
            DB::table('perfil_usuario')
            ->where('id_perfil_usuario',intval($idAcesso))
            ->delete();
            DB::commit();

            return redirect()->route('admin.usuario.perfil.listar',[
                'idUsuarioBPMS' => intval($idUsuario),
            ]);
        } // public function remove(Request $request) { ... }

        public function cad(Request $request) {
            $idUsuario  =   $request->input('id_usuario');
            $idPerfil   =   $request->input('id_perfil');
            if(is_null($idUsuario) || is_null($idPerfil)) return redirect()->route('admin.usuario.listar');

            DB::beginTransaction();
            DB::table('perfil_usuario')
            ->insert([
                'id_perfil' =>  intval($idPerfil),
                'id_usuario'=>  intval($idUsuario),
                'data_cria' =>  Carbon::now(),
                'data_alt'  =>  Carbon::now(),
                'usr_cria'  =>  Auth::user()->id,
                'usr_alt'   =>  Auth::user()->id,
            ]);
            DB::commit();

            return redirect()->route('admin.usuario.perfil.listar',[
                'idUsuarioBPMS' => intval($idUsuario),
            ]);
        } // public function remove(Request $request) { ... }
    }
