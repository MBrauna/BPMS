<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Carbon\Carbon;

    class Admin extends Controller
    {
        public function usuario(Request $request) {
            $vDados     =   DB::table('users')
                            ->orderBy('users.name','asc')
                            ->orderBy('users.email','asc')
                            ->get();
            $retorno    =   [];

            foreach ($vDados as $conteudo) {
                $tmpRetorno             =   [];
                $tmpRetorno['id']       =   $conteudo->id;
                $tmpRetorno['nome']     =   $conteudo->name;
                $tmpRetorno['email']    =   $conteudo->email;
                $tmpRetorno['admin']    =   ($conteudo->administrador) ? 'Administrador' : 'Usuário';
                $tmpRetorno['dataCria'] =   Carbon::parse($conteudo->created_at)->format('d/m/y h:i');
                $tmpRetorno['dataAlt']  =   Carbon::parse($conteudo->updated_at)->format('d/m/y h:i');

                array_push($retorno, $tmpRetorno);
            } // foreach ($vDados as $conteudo) { ... }

            return response()->json($retorno,200);
        } // public function usuario(Request $request) { ... }
    }
