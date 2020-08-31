<?php

    namespace App\Http\Controllers\request;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Auth;
    use DB;
    use Carbon\Carbon;

    class ObjetoTroca extends Controller
    {
        public function index(Request $request) {
            if(!usuario_lider_processo()) {
                return redirect()->route('raiz');
            } // if(usuario_lider_processo()) { ... }

            return view('request.ObjectRequest');
        } // public function index(Request $request) { .. }

        public function list(Request $request) {
            $idBpms             =   $request->input('idBpms');
            $tituloBpms         =   $request->input('tituloBpms');
            $idEmpresaBpms      =   $request->input('idEmpresaBpms');
            $idProcessoBpms     =   $request->input('idProcessoBpms');
            $idTipoBpms         =   $request->input('idTipoBpms');
            $idSolicitanteBpms  =   $request->input('idSolicitanteBpms');
            $idResponsavelBpms  =   $request->input('idResponsavelBpms');

            if($idBpms == 'null') {
                $idBpms = null;
            }
            if($tituloBpms == 'null') {
                $tituloBpms = null;
            }
            if($idEmpresaBpms == 'null') {
                $idEmpresaBpms = null;
            }
            if($idProcessoBpms == 'null') {
                $idProcessoBpms = null;
            }
            if($idTipoBpms == 'null') {
                $idTipoBpms = null;
            }
            if($idSolicitanteBpms == 'null') {
                $idSolicitanteBpms = null;
            }
            if($idResponsavelBpms == 'null') {
                $idResponsavelBpms = null;
            }

            $lista  =   DB::table('entrada_solicitacao')
                        ->where('usr_cria',Auth::user()->id)
                        ->where('situacao',true)
                        ->orderBy('tipo','asc')
                        ->orderBy('titulo','asc')
                        ->get();

            $retorno=   [];

            foreach ($lista as $conteudoLista) {
                if(!is_null($idBpms) && $conteudoLista->id_entrada_solicitacao != $idBpms) continue;
                if(!is_null($tituloBpms) && !strpos($conteudoLista->titulo, $tituloBpms)) continue;
                //if(!is_null($idEmpresaBpms) && $conteudoLista->id_entrada_solicitacao != $idEmpresaBpms) continue;
                if(!is_null($idProcessoBpms) && $conteudoLista->id_processo_origem != $idProcessoBpms) continue;
                if(!is_null($idTipoBpms) && $conteudoLista->id_tipo_processo_origem != $idTipoBpms) continue;
                if(!is_null($idSolicitanteBpms) && $conteudoLista->id_solicitante != $idSolicitanteBpms) continue;
                if(!is_null($idResponsavelBpms) && $conteudoLista->id_responsavel_origem != $idResponsavelBpms) continue;

                array_push($retorno, $conteudoLista);
            } // foreach ($lista as $conteudoLista) { ... }


            return view('request.ListObject',[
                'list'  =>  $retorno,
            ]);
        } // public function list(Request $request) { ... }

        public function remove(Request $request) {
            $idEntradaSolicitacao = $request->input('id_entrada_solicitacao');

            if(is_null($idEntradaSolicitacao)) return redirect()->route('object.list');

            DB::beginTransaction();
            DB::table('entrada_solicitacao')
            ->where('id_entrada_solicitacao',$idEntradaSolicitacao)
            ->update([
                'situacao'  =>  false,
            ]);
            DB::commit();

            return redirect()->route('object.list');
        } // public function remove(Request $request) { ... }
    }
