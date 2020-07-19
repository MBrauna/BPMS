<?php

    namespace App\Http\Controllers\files;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class FileToUser extends Controller
    {
        public function index(Request $request) {
            $listaAcessos   =   usuario_acesso(Auth::user()->id);

            $listaUsuario   =   DB::table('perfil_acesso')
                                ->join('perfil_usuario','perfil_usuario.id_perfil','perfil_acesso.id_perfil')
                                ->join('users','users.id','perfil_usuario.id_usuario')
                                ->where(function($query) use($listaAcessos){
                                    foreach($listaAcessos as $acesso) {
                                        $query->orWhere('perfil_acesso.id_processo',$acesso->id_processo);
                                    } // foreach($listaAcessos as $acesso) { ... }
                                })
                                ->select('users.*')
                                ->distinct()
                                ->orderBy('users.name','asc')
                                ->get();
            
            $enviado        =   DB::table('arquivo')
                                ->where('arquivo.usr_cria',Auth::user()->id)
                                ->whereNotNull('arquivo.id_para_usuario')
                                ->orderBy('arquivo.data_cria','desc')
                                ->get();
            
            $recebido       =   DB::table('arquivo')
                                ->where('arquivo.id_para_usuario',Auth::user()->id)
                                ->orderBy('arquivo.data_cria','desc')
                                ->get();

            return view('file.userFile',[
                'usuarios'  =>  $listaUsuario,
                'enviados'  =>  $enviado,
                'recebidos' =>  $recebido,
            ]);
        } // public function index(Request $request) { ... }


        public function create(Request $request) {
            $usuarios   =   $request->input('usuarios',[]);
            $arquivos   =   $request->file('arquivoBPMS',[]);
            
            if(is_null($usuarios) || count($usuarios) <= 0) dd(1);
            if(is_null($arquivos) || count($arquivos) <= 0) dd(2);
            
            $listaArq   =   [];

            foreach($arquivos as $chave => $arquivo) {
                if($arquivo->isValid()) {

                    $tmpArquivo     =   (object)[
                        'nome_servidor' =>  Carbon::now()->timestamp.'-'.$chave.'.'.$arquivo->getClientOriginalExtension(),
                        'extensao'      =>  $arquivo->getClientOriginalExtension(),
                        'nome_arquivo'  =>  $arquivo->getClientOriginalName(),
                        'mime'          =>  $arquivo->getMimeType(),
                        'tamanho'       =>  $arquivo->getSize(),
                    ];
                    array_push($listaArq,$tmpArquivo);

                    $upload = $arquivo->storeAs('compartilhado', $tmpArquivo->nome_servidor);
                } // if($arquivo->isValid()) { ... }
            } // foreach($arquivos as $arquivo) { ... }

            foreach($usuarios as $usuario) {
                foreach($listaArq as $arquivo) {
                    DB::beginTransaction();
                    DB::table('arquivo')
                    ->insert([
                        'id_para_usuario'   =>  $usuario,
                        'nome_servidor'     =>  $arquivo->nome_servidor,
                        'nome_arquivo'      =>  $arquivo->nome_arquivo,
                        'extensao'          =>  $arquivo->extensao,
                        'mime'              =>  $arquivo->mime,
                        'tamanho'           =>  $arquivo->tamanho,
                        'data_cria'         =>  Carbon::now(),
                        'data_alt'          =>  Carbon::now(),
                        'usr_cria'          =>  Auth::user()->id,
                        'usr_alt'           =>  Auth::user()->id,
                    ]);
                    DB::commit();
                } // foreach($listaArq as $arquivo) { ... }
            } // foreach($usuarios as $usuario) { ... }

            return redirect()->route('file.list');
        } // public function create(Request $request) { ... }
    } // class FileToUser extends Controller { ... }
