<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use Carbon\Carbon;
    use DB;

    class Fluxo extends Controller
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
                } // if($this->verificaPermissao()) { ... }

                $idTipo     =   $request->input('idTipoProcessoBPMS');
                if(is_null($idTipo)) return redirect()->route('admin.empresa.listar');

                $tipo       =   DB::table('tipo_processo')
                                ->where('id_tipo_processo',intval($idTipo))
                                ->first();

                $fluxo      =   DB::table('fluxo_situacao')
                                ->where('id_tipo_processo',$tipo->id_tipo_processo)
                                ->where('situacao',true)
                                ->orderBy('id_situacao_atual','asc')
                                ->orderBy('id_situacao_posterior','asc')
                                ->get();
                
                $situacao   =   DB::table('situacao')
                                ->where('id_processo',$tipo->id_processo)
                                ->orderBy('descricao','asc')
                                ->get();

                return view('admin.arvore',[
                    'tipo'      =>  $tipo,
                    'fluxos'    =>  $fluxo,
                    'situacoes' =>  $situacao,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function index(Request $request) { ... }
        

        public function cad(Request $request) {
            $idTipoProcesso =   $request->input('id_tipo_processo');
            $idSitAtual     =   $request->input('id_situacao_atual');
            $idProxima      =   $request->input('id_situacao_posterior');

            if(is_null($idTipoProcesso)) return redirect()->route('admin.empresa.listar');
            if(is_null($idSitAtual) || is_null($idProxima)) return redirect()->route('admin.arvore.listar',['idTipoProcessoBPMS' => $idTipoProcesso]);

            $contador       =   DB::table('fluxo_situacao')
                                ->where('id_tipo_processo',intval($idTipoProcesso))
                                ->where('id_situacao_atual',intval($idSitAtual))
                                ->where('id_situacao_posterior',intval($idProxima))
                                ->count();
            if($contador > 0) return redirect()->route('admin.arvore.listar',['idTipoProcessoBPMS' => $idTipoProcesso]);

            try {
                DB::table('fluxo_situacao')
                ->insert([
                    'id_tipo_processo'      =>  intval($idTipoProcesso),
                    'id_situacao_atual'     =>  intval($idSitAtual),
                    'id_situacao_posterior' =>  intval($idProxima),
                    'situacao'              =>  true,
                    'data_cria'             =>  Carbon::now(),
                    'data_alt'              =>  Carbon::now(),
                    'usr_cria'              =>  Auth::user()->id,
                    'usr_alt'               =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.arvore.listar',['idTipoProcessoBPMS' => $idTipoProcesso]);
            }
            catch(Exception $erro){
                DB::rollback();
                dd($erro);
            }
        } // public function cad(Request $request) { ... }

        public function remove(Request $request) {
            $idTipoProcesso =   $request->input('id_tipo_processo');
            $idFluxo        =   $request->input('id_fluxo_situacao');

            if(is_null($idTipoProcesso)) return redirect()->route('admin.empresa.listar');
            if(is_null($idFluxo)) return redirect()->route('admin.arvore.listar',['idTipoProcessoBPMS' => $idTipoProcesso]);

            try {
                DB::table('fluxo_situacao')
                ->where('id_tipo_processo',intval($idTipoProcesso))
                ->where('id_fluxo_situacao',intval($idFluxo))
                ->delete();
                DB::commit();

                return redirect()->route('admin.arvore.listar',['idTipoProcessoBPMS' => $idTipoProcesso]);
            }
            catch(Exception $erro){
                DB::rollback();
                dd($erro);
            }
        } // public function remove(Request $request) { ... }
    } // class Fluxo extends Controller { ... }
