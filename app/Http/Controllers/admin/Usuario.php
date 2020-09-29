<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use App\User;
    use Hash;

    class Usuario extends Controller
    {
        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #

        public function __construct()
        {
            $this->middleware('auth');
        } // public function __construct(){ ... }

        # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- #
        public function index(Request $request) {
            try {
                $usuarios   =   DB::table('users')
                                ->orderBy('name','asc')
                                ->orderBy('email','asc')
                                ->paginate(20);

                return view('admin.users',[
                    'usuarios' => $usuarios,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                return redirect()->route('raiz');
            } // catch(Exception $erro) { ... }
        } // public function index(Request $request) { ... }

        public function cad(Request $request) {
            try {
                $nome       =   $request->input('name');
                $email      =   $request->input('email');
                $admin      =   intval($request->input('administrador','0'));
                $senha      =   $request->input('senha','123456789');

                if(is_null($nome) || is_null($email)) return redirect()->route('admin.usuario.listar');

                $count  =   DB::table('users')->where('email',$email)->count();
                if($count > 0) return redirect()->route('admin.usuario.listar');

                $user               =   new User;
                $user->name         =   $nome;
                $user->email        =   strtolower(trim($email));
                $user->password     =   Hash::make(trim($senha));
                $user->administrador=   $admin;
                $user->save();

                return redirect()->route('admin.usuario.listar');
            }
            catch(Exception $erro) {
                dd($erro);
            }
        } // public function cad(Request $request) { ... }

        public function edit(Request $request) {
            try {
                $id     =   $request->input('id_usuario');
                $nome   =   $request->input('name');
                $email  =   $request->input('email');
                $admin  =   intval($request->input('administrador','0'));
                $senha  =   $request->input('senha');
                
                if(is_null($id)) return redirect()->route('admin.usuario.listar');

                $count  =   DB::table('users')->where('email',$email)->count();
                if($count > 0) return redirect()->route('admin.usuario.listar');

                
                $user           =   User::find($id);
                
                if(!is_null($nome)) {
                    $user->name = $nome;
                }

                if(!is_null(trim($email))) {
                    $user->email = strtolower(trim($email));
                }

                if(!is_null($senha)) {
                    $user->password = Hash::make(trim($senha));
                }

                $user->administrador =   $admin;
                $user->save();

                return redirect()->route('admin.usuario.listar');
            }
            catch(Exception $erro) {
                dd($erro);
            }
        } // public function cad(Request $request) { ... }

        
    }
