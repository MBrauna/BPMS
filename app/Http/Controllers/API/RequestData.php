<?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use DB;
    use Carbon\Carbon;

    class RequestData extends Controller
    {

        private $gbPermissao    =   [];

        public function index(Request $request) {
            $retorno        =   [];

            $idUsuario      =   $request->input('idUsuario','1');
            $idChamado      =   $request->input('idBPMS');
            $titulo         =   $request->input('tituloBPMS');
            $idEmpresa      =   $request->input('idEmpresaBPMS');
            $idProcesso     =   $request->input('idProcessoBPMS');
            $idTipo         =   $request->input('idTipoBPMS');
            $idSituacao     =   $request->input('idSituacaoBPMS');

            if(is_null($idUsuario)) return response()->json([
                'erro'  =>  [
                    'codigo'    =>  'ReqData0001',
                    'mensagem'  =>  'Código do usuário não preenchido',
                ]
            ],202);

            $this->gbPemissao   =   usuario_acesso(intval($idUsuario));

            $perfilProcesso     =   [];

            foreach($this->gbPermissao as $conteudo) {
                if(!in_array($conteudo->id_processo)) {
                    array_push($perfilProcesso, $conteudo->id_processo);
                } // if(!in_array($conteudo->id_processo)) { ... }
            } // foreach($this->gbPermissao as $conteudo) { ... }




            if(is_null($idSituacao)) {
                $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',false)->where('situacao.situacao',true)->select('situacao.id_situacao');
            }
            else {
                $perfilSituacao =   DB::table('situacao')->where('situacao.id_situacao',intval($idSituacao))->where('situacao.situacao',true)->select('situacao.id_situacao');
            }

            $chamadoProcesso    =   DB::table('chamado')
                                    ->whereIn('chamado.id_processo',$perfilProcesso)
                                    //->whereIn('chamado.id_situacao',$perfilSituacao)
                                    ->select('chamado.*');

            $chamadoUsuario     =   DB::table('chamado')
                                    ->where('chamado.id_solicitante',intval($idUsuario))
                                    //->whereIn('chamado.id_situacao',$perfilSituacao)
                                    ->union($chamadoProcesso)
                                    ->orderBy('data_vencimento','asc')
                                    ->orderBy('titulo','asc')
                                    ->distinct()
                                    ->get();
            
            foreach ($chamadoUsuario as $conteudo) {

                // Para filtros
                // por ID
                if(!is_null($idChamado) && $conteudo->id_chamado != intval($idChamado)) continue;
                // por titulo
                if(!is_null($titulo) && !strpos($conteudo->titulo, $titulo)) continue;
                // Para filtros

                $tmpRetorno                     =   [];
                $tmpSolicitante                 =   DB::table('users')->where('id',$conteudo->id_solicitante)->first();
                $tmpSituacao                    =   DB::table('situacao')->where('id_situacao',$conteudo->id_situacao)->first();
                $tmpResponsavel                 =   DB::table('users')->where('id',$conteudo->id_responsavel)->first();
                $tmpEmpresa                     =   DB::table('empresa')->where('id_empresa',$conteudo->id_empresa)->first();
                $tmpProcesso                    =   DB::table('processo')->where('id_processo',$conteudo->id_processo)->first();
                $tmpTipoProcesso                =   DB::table('tipo_processo')->where('id_tipo_processo',$conteudo->id_tipo_processo)->first();

                $tmpRetorno['id']               =   $conteudo->id_chamado;
                $tmpRetorno['titulo']           =   $conteudo->titulo;
                $tmpRetorno['solicitante']      =   $tmpSolicitante->name;
                $tmpRetorno['situacao']         =   (is_null($tmpSituacao)) ? '' : $tmpSituacao->descricao;
                $tmpRetorno['responsavel']      =   (is_null($conteudo->id_responsavel)) ? '' : $tmpResponsavel->name;
                $tmpRetorno['empresa']          =   $tmpEmpresa->descricao;
                $tmpRetorno['processo']         =   $tmpProcesso->descricao;
                $tmpRetorno['dataSolicitacao']  =   Carbon::parse($conteudo->data_criacao)->format('d/m/y h:i');
                $tmpRetorno['dataVencimento']   =   Carbon::parse($conteudo->data_vencimento)->format('d/m/y h:i');
                $tmpRetorno['dataConclusao']    =   is_null($conteudo->data_conclusao) ? '' : Carbon::parse($conteudo->data_conclusao)->format('d/m/y h:i');
                $tmpRetorno['prazoContratado']  =   $tmpTipoProcesso->sla.' minuto(s)';
                $tmpRetorno['prazoAtribuido']   =   null;
                $tmpRetorno['prazo']            =   null;
                $tmpRetorno['atraso']           =   null;

                array_push($retorno,$tmpRetorno);
            } // foreach ($chamadoUsuario as $conteudo) { ... }

            return response()->json($retorno,200);
        } // public function index(Request $request) { ... }
    }
