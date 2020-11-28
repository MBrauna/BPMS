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
                            ->join('perfil','perfil.id_perfil','perfil_usuario.id_perfil')
                            ->where('perfil_usuario.id_usuario',intval($idUsuario))
                            ->orderBy('perfil.descricao','asc')
                            ->select('perfil_usuario.*')
                            ->get();

            $perfilData =   DB::table('perfil_usuario')
                            ->where('id_usuario',intval($idUsuario))
                            ->select('id_perfil');

            $perfil     =   DB::table('perfil')
                            ->where('situacao',true)
                            ->whereNotIn('id_perfil',$perfilData)
                            ->orderBy('perfil.descricao','asc')
                            ->get();
            
            $usuarios   =   DB::table('users')
                            ->orderBy('users.name','asc')
                            ->get();

            return view('admin.acesso',[
                'usuario'   =>  $usuario,
                'acessos'   =>  $acesso,
                'perfis'    =>  $perfil,
                'usuarios'  =>  $usuarios,
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
            $idSuperior =   $request->input('id_superior');

            if(is_null($idUsuario) || is_null($idPerfil)) return redirect()->route('admin.usuario.listar');

            DB::beginTransaction();
            DB::table('perfil_usuario')
            ->insert([
                'id_perfil'     =>  intval($idPerfil),
                'id_usuario'    =>  intval($idUsuario),
                'id_superior'   =>  is_null($idSuperior) ? null : intval($idSuperior),
                'data_cria'     =>  Carbon::now(),
                'data_alt'      =>  Carbon::now(),
                'usr_cria'      =>  Auth::user()->id,
                'usr_alt'       =>  Auth::user()->id,
            ]);
            DB::commit();

            return redirect()->route('admin.usuario.perfil.listar',[
                'idUsuarioBPMS' => intval($idUsuario),
            ]);
        } // public function remove(Request $request) { ... }
    }
