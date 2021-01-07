<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Auth;
    use Carbon\Carbon;

    class Questao extends Controller
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
                $idTipoProcesso =   $request->input('idTipoProcessoBPMS');
                if(is_null($idTipoProcesso)) return redirect()->route('admin.empresa.lista');

                $tipo   =   DB::table('tipo_processo')
                            ->where('id_tipo_processo',intval($idTipoProcesso))
                            ->first();
                if(is_null($tipo) || !isset($tipo->id_tipo_processo)) return redirect()->route('admin.empresa.lista');

                $pergunta       =   DB::table('pergunta_tipo')
                                    ->where('id_tipo_processo',intval($idTipoProcesso))
                                    ->orderBy('ordem','asc')
                                    ->orderBy('descricao','asc')
                                    ->get();

                return view('admin.pergunta',[
                    'tipo'      =>  $tipo,
                    'perguntas' =>  $pergunta,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function index(Request $request) { ... }


        public function cad(Request $request) {
            try {
                if($this->verificaPermissao()) {
                    return redirect()->route('raiz');
                } // if($this->verificaPermissao()) { ... }

                $idTipoProcesso =   $request->input('id_tipo_processo');
                $descricao      =   $request->input('descricao');
                $tipo           =   $request->input('tipo');
                $ordem          =   intval($request->input('ordem','999'));
                $altDataVenc    =   (intval($request->input('alt_data_vencimento','0')) == 1) ? true : false;
                $situacao       =   (intval($request->input('situacao','0')) == 1) ? true : false;

                if(is_null($idTipoProcesso)) return redirect()->route('admin.empresa.lista');

                $tipoProcesso   =   DB::table('tipo_processo')
                                    ->where('tipo_processo.id_tipo_processo',$idTipoProcesso)
                                    ->first();
                
                if(is_null($tipo)) return redirect()->route('admin.questao.listar',['idTipoProcessoBPMS'    =>  $tipoProcesso->id_tipo_processo,]);
                if($tipo != 'datetime' && $altDataVenc) $altDataVenc = false;

                DB::table('pergunta_tipo')
                ->insert([
                    'id_tipo_processo'      =>  $idTipoProcesso,
                    'descricao'             =>  $descricao,
                    'tipo'                  =>  $tipo,
                    'ordem'                 =>  $ordem,
                    'alt_data_vencimento'   =>  $altDataVenc,
                    'situacao'              =>  $situacao,
                    'data_cria'             =>  Carbon::now(),
                    'data_alt'              =>  Carbon::now(),
                    'usr_cria'              =>  Auth::user()->id,
                    'usr_alt'               =>  Auth::user()->id,
                ]);

                return redirect()->route('admin.questao.listar',[
                    'idTipoProcessoBPMS'    =>  $tipoProcesso->id_tipo_processo,
                ]);
            } // try { ... }
            catch(Exception $error) {
                DB::rollback();
                dd($error);
            } // catch(Exception $error) { ... }
        } // public function cad(Request $request) { ... }

        public function edit(Request $request) {
            try {
                if($this->verificaPermissao()) {
                    return redirect()->route('raiz');
                } // if($this->verificaPermissao()) { ... }

                $idPergunta     =   $request->input('id_pergunta_tipo');
                $idTipoProcesso =   $request->input('id_tipo_processo');
                $descricao      =   $request->input('descricao');
                $tipo           =   $request->input('tipo');
                $ordem          =   intval($request->input('ordem','999'));
                $altDataVenc    =   (intval($request->input('alt_data_vencimento','0')) == 1) ? true : false;
                $situacao       =   (intval($request->input('situacao','0')) == 1) ? true : false;

                if(is_null($idPergunta) || is_null($idTipoProcesso)) return redirect()->route('admin.empresa.lista');

                $tipoProcesso   =   DB::table('tipo_processo')
                                    ->where('tipo_processo.id_tipo_processo',$idTipoProcesso)
                                    ->first();

                if(is_null($tipo)) return redirect()->route('admin.questao.listar',['idTipoProcessoBPMS'    =>  $tipoProcesso->id_tipo_processo,]);
                if($tipo != 'datetime' && $altDataVenc) $altDataVenc = false;

                DB::table('pergunta_tipo')
                ->where('pergunta_tipo.id_pergunta_tipo',intval($idPergunta))
                ->where('pergunta_tipo.id_tipo_processo',intval($idTipoProcesso))
                ->update([
                    'id_tipo_processo'      =>  $idTipoProcesso,
                    'descricao'             =>  $descricao,
                    'tipo'                  =>  $tipo,
                    'ordem'                 =>  $ordem,
                    'alt_data_vencimento'   =>  $altDataVenc,
                    'situacao'              =>  $situacao,
                    'data_alt'              =>  Carbon::now(),
                    'usr_alt'               =>  Auth::user()->id,
                ]);

                return redirect()->route('admin.questao.listar',[
                    'idTipoProcessoBPMS'    =>  $tipoProcesso->id_tipo_processo,
                ]);
            } // try { ... }
            catch(Exception $error) {
                DB::rollback();
                dd($error);
            } // catch(Exception $error) { ... }
        }
    }
