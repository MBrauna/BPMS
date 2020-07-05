<?php

    namespace App\Http\Controllers\admin;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class TipoProcesso extends Controller
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
                if(is_null($processo) || !isset($processo->id_processo)) if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');

                $tipoProceso    =   DB::table('tipo_processo')
                                    ->where('tipo_processo.id_processo',intval($idProcesso))
                                    ->orderBy('tipo_processo.situacao','asc')
                                    ->orderBy('tipo_processo.descricao','asc')
                                    ->get();
                
                $situacao       =   DB::table('situacao')
                                    ->where('situacao.id_processo',intval($idProcesso))
                                    ->where('situacao.situacao',true)
                                    ->get();
                $processos      =   DB::table('processo')
                                    ->where('processo.id_empresa',$processo->id_empresa)
                                    ->where('processo.situacao',true)
                                    ->get();
                
                return view('admin.tipoProcesso',[
                    'processo'  =>  $processo,
                    'tipos'     =>  $tipoProceso,
                    'situacoes' =>  $situacao,
                    'processos' =>  $processos,
                ]);
            } // try { ... }
            catch(Exception $erro) {
                dd($erro);
            } // catch(Exception $erro) { ... }
        } // public function index(Request $request) { ... }


        public function cad(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }

            try {
                $idProcesso         =   $request->input('id_processo');
                $descricao          =   $request->input('descricao');
                $idSituacao         =   $request->input('id_situacao');
                $idProcessoRedir    =   $request->input('id_processo_redireciona');
                $questao            =   $request->input('questao');
                $ordem              =   $request->input('ordem','999');
                $sla                =   $request->input('sla','120');
                $permiteAltSLA      =   (intval($request->input('permite_alterar_sla','0')) <= 0) ? false : true;
                $situacao           =   (intval($request->input('situacao','0')) <= 0) ? false : true;

                if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');
                if(is_null($descricao) || is_null($idSituacao) || is_null($questao)) return redirect()->route('admin.tipo.listar',['idProcessoBPMS' => intval($idProcesso)]);
                
                DB::beginTransaction();
                DB::table('tipo_processo')
                ->insert([
                    'id_processo'               =>  intval($idProcesso),
                    'descricao'                 =>  $descricao,
                    'id_situacao'               =>  intval($idSituacao),
                    'id_processo_redireciona'   =>  (is_null($idProcessoRedir) ? null : intval($idProcessoRedir)),
                    'questao'                   =>  $questao,
                    'ordem'                     =>  intval($ordem),
                    'sla'                       =>  intval($sla),
                    'permite_alterar_sla'       =>  $permiteAltSLA,
                    'situacao'                  =>  $situacao,
                    'data_cria'                 =>  Carbon::now(),
                    'data_alt'                  =>  Carbon::now(),
                    'usr_cria'                  =>  Auth::user()->id,
                    'usr_alt'                   =>  Auth::user()->id,
                ]);
                DB::commit();
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }

            return redirect()->route('admin.tipo.listar',['idProcessoBPMS' => intval($idProcesso)]);
        } // public function cad(Request $request) { ... }

        public function edit(Request $request) {
            if($this->verificaPermissao()) {
                return redirect()->route('raiz');
            }

            try {
                $idTipoProcesso     =   $request->input('id_tipo_processo');
                $idProcesso         =   $request->input('id_processo');
                $descricao          =   $request->input('descricao');
                $idSituacao         =   $request->input('id_situacao');
                $idProcessoRedir    =   $request->input('id_processo_redireciona');
                $questao            =   $request->input('questao');
                $ordem              =   $request->input('ordem','999');
                $sla                =   $request->input('sla','120');
                $permiteAltSLA      =   (intval($request->input('permite_alterar_sla','0')) <= 0) ? false : true;
                $situacao           =   (intval($request->input('situacao','0')) <= 0) ? false : true;

                if(is_null($idProcesso)) return redirect()->route('admin.empresa.listar');
                if(is_null($descricao) || is_null($idSituacao) || is_null($questao)) return redirect()->route('admin.tipo.listar',['idProcessoBPMS' => intval($idProcesso)]);
                
                DB::beginTransaction();
                DB::table('tipo_processo')
                ->where('tipo_processo.id_processo',intval($idProcesso))
                ->where('tipo_processo.id_tipo_processo',intval($idTipoProcesso))
                ->update([
                    'descricao'                 =>  $descricao,
                    'id_situacao'               =>  intval($idSituacao),
                    'id_processo_redireciona'   =>  (is_null($idProcessoRedir) ? null : intval($idProcessoRedir)),
                    'questao'                   =>  $questao,
                    'ordem'                     =>  intval($ordem),
                    'sla'                       =>  intval($sla),
                    'permite_alterar_sla'       =>  $permiteAltSLA,
                    'situacao'                  =>  $situacao,
                    'data_alt'                  =>  Carbon::now(),
                    'usr_alt'                   =>  Auth::user()->id,
                ]);
                DB::commit();
            } // try { ... }
            catch(Exception $erro) {
                DB::rollback();
                dd($erro);
            } // catch(Exception $erro) { ... }

            return redirect()->route('admin.tipo.listar',['idProcessoBPMS' => intval($idProcesso)]);
        } // public function edit(Request $request) { ... }
    } // class TipoProcesso extends Controller { ... }
