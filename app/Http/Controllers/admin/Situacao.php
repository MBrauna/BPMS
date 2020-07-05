<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class Situacao extends Controller
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
            }

            try {
                $idProcesso     =   $request->input('idProcessoBPMS');
                if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');

                $processo       =   DB::table('processo')
                                    ->where('processo.id_processo',intval($idProcesso))
                                    ->first();
                if(is_null($processo) || !isset($processo->id_processo)) return redirect()->route('admin.empresa.listar');

                $listaSituacoes =   DB::table('situacao')
                                    ->where('id_processo',$processo->id_processo)
                                    ->orderBy('situacao','asc')
                                    ->get();
                
                $listaPerfil    =   DB::table('perfil')
                                    ->where('perfil.situacao',true)
                                    ->orderBy('perfil.descricao')
                                    ->get();
                
                return view('admin.situacao',[
                    'processo'  =>  $processo,
                    'situacoes' =>  $listaSituacoes,
                    'perfis'    =>  $listaPerfil,
                ]);
            } // try { ... }
            catch(Exception $error) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $error) { ... }
        } // public function index(Request $request) { ... }

        public function cad(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }

            try {
                $idProcesso         =   $request->input('id_processo');
                $descricao          =   $request->input('descricao');
                $idPerfil           =   $request->input('id_perfil');
                $tarefaSolicitante  =   intval($request->input('tarefa_solicitante')) == 1 ? true : false;
                $marcaResponsavel   =   intval($request->input('marca_responsavel')) == 1 ? true : false;
                $dataVencimento     =   intval($request->input('alterar_data_vencimento')) == 1 ? true : false;
                $conclusiva         =   intval($request->input('conclusiva')) == 1 ? true : false;
                $situacao           =   intval($request->input('situacao')) == 1 ? true : false;

                if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');
                if(is_null($descricao)) return redirect()->route('admin.situacao.listar',['idProcessoBPMS' => intval($idProcesso)]);

                DB::beginTransaction();
                DB::table('situacao')
                ->insert([
                    'id_processo'               =>  intval($idProcesso),
                    'descricao'                 =>  $descricao,
                    'id_perfil'                 =>  intval($idProcesso),
                    'tarefa_solicitante'        =>  $tarefaSolicitante,
                    'marca_responsavel'         =>  $marcaResponsavel,
                    'alterar_data_vencimento'   =>  $dataVencimento,
                    'limpar_responsavel'        =>  false,
                    'conclusiva'                =>  $conclusiva,
                    'situacao'                  =>  $situacao,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  Auth::user()->id,
                    'usr_alt'                   =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.situacao.listar',['idProcessoBPMS' => intval($idProcesso)]);
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function cad(Request $request) { ... }

        public function edit(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }
            
            try {
                $idSituacao         =   $request->input('id_situacao');
                $idProcesso         =   $request->input('id_processo');
                $descricao          =   $request->input('descricao');
                $idPerfil           =   $request->input('id_perfil');
                $tarefaSolicitante  =   intval($request->input('tarefa_solicitante')) == 1 ? true : false;
                $marcaResponsavel   =   intval($request->input('marca_responsavel')) == 1 ? true : false;
                $dataVencimento     =   intval($request->input('alterar_data_vencimento')) == 1 ? true : false;
                $conclusiva         =   intval($request->input('conclusiva')) == 1 ? true : false;
                $situacao           =   intval($request->input('situacao')) == 1 ? true : false;

                if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');
                if(is_null($descricao)) return redirect()->route('admin.situacao.listar',['idProcessoBPMS' => intval($idProcesso)]);

                DB::beginTransaction();
                DB::table('situacao')
                ->where('situacao.id_processo',intval($idProcesso))
                ->where('situacao.id_situacao',intval($idSituacao))
                ->update([
                    'id_processo'               =>  intval($idProcesso),
                    'descricao'                 =>  $descricao,
                    'id_perfil'                 =>  intval($idProcesso),
                    'tarefa_solicitante'        =>  $tarefaSolicitante,
                    'marca_responsavel'         =>  $marcaResponsavel,
                    'alterar_data_vencimento'   =>  $dataVencimento,
                    'limpar_responsavel'        =>  false,
                    'conclusiva'                =>  $conclusiva,
                    'situacao'                  =>  $situacao,
                    'data_alt'                  =>  Carbon::now(),
                    'usr_alt'                   =>  Auth::user()->id,
                ]);
                DB::commit();

                return redirect()->route('admin.situacao.listar',['idProcessoBPMS' => intval($idProcesso)]);
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function cad(Request $request) { ... }
    } // class Situacao extends Controller { ... }
