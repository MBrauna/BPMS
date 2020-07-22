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

            $idUsuario      =   $request->input('idUsuario');
            $idChamado      =   $request->input('idBPMS');
            $titulo         =   $request->input('tituloBPMS');
            $idEmpresa      =   $request->input('idEmpresaBPMS');
            $idProcesso     =   $request->input('idProcessoBPMS');
            $idTipo         =   $request->input('idTipoBPMS');
            $idSituacao     =   $request->input('idSituacaoBPMS');

            if($idChamado == 'null') {
                $idChamado  =   null;
            }
            if($titulo == 'null') {
                $titulo  =   null;
            }
            if($idEmpresa == 'null') {
                $idEmpresa  =   null;
            }
            if($idProcesso == 'null') {
                $idProcesso  =   null;
            }
            if($idTipo == 'null') {
                $idTipo  =   null;
            }
            if($idSituacao == 'null') {
                $idSituacao  =   null;
            }

            if(is_null($idUsuario)) return response()->json([
                'erro'  =>  [
                    'codigo'    =>  'ReqData0001',
                    'mensagem'  =>  'Código do usuário não preenchido',
                ]
            ],202);

            $perfilProcesso     =   [];
            $listaSubordinados  =   [];

            foreach(usuario_acesso(intval($idUsuario)) as $conteudo) {
                if(!in_array($conteudo->id_processo,$perfilProcesso)) {
                    array_push($perfilProcesso, $conteudo->id_processo);
                } // if(!in_array($conteudo->id_processo)) { ... }
            } // foreach(usuario_acesso(intval($idUsuario)) as $conteudo) { ... }

            foreach(consulta_subordinados_todos($idUsuario) as $conteudo) {
                if(!in_array($conteudo->id,$listaSubordinados)) {
                    array_push($listaSubordinados, $conteudo->id);
                } // if(!in_array($conteudo->id_processo)) { ... }
            }

            if(is_null($idSituacao)) {
                $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',false)->where('situacao.situacao',true)->select('situacao.id_situacao');
            }
            elseif(intval($idSituacao) < 0){
                $perfilSituacao =   DB::table('situacao')->where('situacao.conclusiva',true)->where('situacao.situacao',true)->select('situacao.id_situacao');
            }
            else {
                $perfilSituacao =   DB::table('situacao')->where('situacao.id_situacao',intval($idSituacao))->where('situacao.situacao',true)->select('situacao.id_situacao');
            }

            $chamadoProcesso    =   DB::table('chamado')
                                    ->whereIn('chamado.id_processo',$perfilProcesso)
                                    ->whereIn('chamado.id_situacao',$perfilSituacao)
                                    ->select('chamado.*');

            $chamadoSubordinado =   DB::table('chamado')
                                    ->whereIn('chamado.id_solicitante',$listaSubordinados)
                                    ->whereIn('chamado.id_situacao',$perfilSituacao)
                                    ->select('chamado.*');

            $chamadoUsuario     =   DB::table('chamado')
                                    ->where('chamado.id_solicitante',intval($idUsuario))
                                    ->whereIn('chamado.id_situacao',$perfilSituacao)
                                    ->union($chamadoProcesso)
                                    ->union($chamadoSubordinado)
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
                // Para empresa
                if(!is_null($idEmpresa) && $conteudo->id_empresa != intval($idEmpresa)) continue;
                // Para processo
                if(!is_null($idProcesso) && $conteudo->id_processo != intval($idProcesso)) continue;
                // Para filtros

                $tmpRetorno                     =   [];
                $tmpSolicitante                 =   DB::table('users')->where('id',$conteudo->id_solicitante)->first();
                $tmpSituacao                    =   DB::table('situacao')->where('id_situacao',$conteudo->id_situacao)->first();
                $tmpEmpresa                     =   DB::table('empresa')->where('id_empresa',$conteudo->id_empresa)->first();
                $tmpProcesso                    =   DB::table('processo')->where('id_processo',$conteudo->id_processo)->first();
                $tmpTipoProcesso                =   DB::table('tipo_processo')->where('id_tipo_processo',$conteudo->id_tipo_processo)->first();

                if(isset($conteudo->id_responsavel) && !is_null($conteudo->id_responsavel)) {
                    $tmpResponsavel             =   DB::table('users')->where('id',$conteudo->id_responsavel)->first();
                }
                elseif(isset($tmpProcesso->id_usr_responsavel) && !is_null($tmpProcesso->id_usr_responsavel)) {
                    $tmpResponsavel             =   consulta_usuario($tmpProcesso->id_usr_responsavel);
                }
                else {
                    $tmpResponsavel             =   null;
                }

                if(is_null($tmpSolicitante)) {
                    $tmpNomeSolicitante =   ['Não atribuído'];
                }
                else {
                    $tmpNomeSolicitante =  explode(' ', trim($tmpSolicitante->name));
                }

                if(is_null($tmpResponsavel)) {
                    $tmpNomeResponsavel =   'Não atribuído';
                }
                else {
                    $tmpNomeResponsavel =   explode(' ', trim($tmpResponsavel->name));
                    $tmpNomeResponsavel =   $tmpNomeResponsavel[0].(count($tmpNomeResponsavel) > 1 ? ' '.$tmpNomeResponsavel[1] : '').(count($tmpNomeResponsavel) > 2 ? ' '.$tmpNomeResponsavel[2] : '');
                }

                $tmpRetorno['id']               =   '<a href="/solicitacao/'.$conteudo->id_chamado.'">#'.$conteudo->id_chamado.'</a>';
                $tmpRetorno['titulo']           =   '<a href="/solicitacao/'.$conteudo->id_chamado.'">'.$conteudo->titulo.'</a>'; #.( strlen($conteudo->titulo) <= 30 ? $conteudo->titulo : substr($conteudo->titulo,0,30).'...' ).'</a>';
                $tmpRetorno['solicitante']      =   $tmpNomeSolicitante[0].(count($tmpNomeSolicitante) > 1 ? ' '.$tmpNomeSolicitante[1] : '').(count($tmpNomeSolicitante) > 2 ? ' '.$tmpNomeSolicitante[2] : '');
                $tmpRetorno['situacao']         =   $tmpSituacao->descricao ?? '';#(is_null($tmpSituacao)) ? '' : (strlen($tmpSituacao->descricao) <= 30 ? $tmpSituacao->descricao : substr($tmpSituacao->descricao,0,30).'...');
                $tmpRetorno['responsavel']      =   $tmpNomeResponsavel;
                $tmpRetorno['empresa']          =   trim($tmpEmpresa->sigla);
                $tmpRetorno['processo']         =   $tmpProcesso->descricao;
                $tmpRetorno['dataSolicitacao']  =   Carbon::parse($conteudo->data_criacao)->format('d/m/Y h:i');
                $tmpRetorno['dataVencimento']   =   Carbon::parse($conteudo->data_vencimento)->format('d/m/Y h:i');
                $tmpRetorno['dataConclusao']    =   is_null($conteudo->data_conclusao) ? '' : Carbon::parse($conteudo->data_conclusao)->format('d/m/Y h:i');
                $tmpRetorno['prazoContratado']  =   Carbon::parse($conteudo->data_criacao)->diff(Carbon::parse($conteudo->data_criacao)->addMinutes($tmpTipoProcesso->sla))->format('%ya %mm %dd %H:%I:%S');
                $tmpRetorno['prazoAtribuido']   =   Carbon::parse($conteudo->data_criacao)->diff(Carbon::parse($conteudo->data_vencimento))->format('%ya %mm %dd %H:%I:%S');
                $tmpRetorno['prazo']            =   (is_null($conteudo->data_conclusao) ? (Carbon::now()->greaterThan(Carbon::parse($conteudo->data_vencimento)) ? '<b class="text-danger">'.Carbon::now()->diff(Carbon::parse($conteudo->data_vencimento))->format('%Ya %mm %dd %H:%I:%S').'</a>' : '<b class="text-success">'.Carbon::now()->diff(Carbon::parse($conteudo->data_vencimento))->format('%ya %mm %dd %H:%I:%S').'</a>') : '<b class="text-success">'.Carbon::parse($conteudo->data_criacao)->diff(Carbon::parse($conteudo->data_conclusao))->format('%ya %mm %dd %H:%I:%S').'</a>');

                array_push($retorno,$tmpRetorno);
            } // foreach ($chamadoUsuario as $conteudo) { ... }

            return response()->json($retorno,200);
        } // public function index(Request $request) { ... }
    }
