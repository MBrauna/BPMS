<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;

    class RequestData extends Controller
    {
        public function index(Request $request) {
            dd($_SERVER['HTTP_HOST']);
            $idChamado  =   $request->input('idBPMS');
            $titulo     =   $request->input('tituloBPMS');
            $idEmpresa  =   $request->input('idEmpresaBPMS');
            $idProcesso =   $request->input('idProcessoBPMS');
            $idTipo     =   $request->input('idTipoBPMS');
            $idSituacao =   $request->input('idSituacaoBPMS');

            $vData  =   DB::table('chamado');
            
            if(!is_null($idChamado)) {
                $vData  =   $vData->where('chamado.id_chamado',intval($idChamado));
            }

            if(!is_null($titulo)) {
                $vData  =   $vData->where('chamado.titulo','like','%'.str_replace(' ','%',$titulo).'%');
            }

            if(!is_null($idEmpresa)) {
                $vData  =   $vData->where('chamado.id_empresa',intval($idEmpresa));
            }

            if(!is_null($idProcesso)) {
                $vData  =   $vData->where('chamado.id_processo',intval($idProcesso));
            }

            if(!is_null($idTipo)) {
                $vData  =   $vData->where('chamado.id_tipo_processo',intval($idTipo));
            }

            if(!is_null($idSituacao)) {
                $vData  =   $vData->where('chamado.id_situacao',intval($idSituacao));
            }
            /*else{
                $sitData=   DB::table('situacao')->where('situacao.conclusiva',false)->where('situacao.situacao',true)->select('situacao.id_situacao');
                $vData  =   $vData->whereIn('chamado.id_situacao',$sitData);
            }*/

            $vData      =   $vData->get();

            dd($vData);
        } // public function index(Request $request) { ... }
    }
