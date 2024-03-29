<?php

    namespace App\Http\Controllers\request;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Chamado;
    use Auth;
    use DB;
    use Carbon\Carbon;
    use Mail;
    use App\User;
    use App\Mail\ChamadoMail;

    class CreateRequest extends Controller
    {
        public function index(Request $request) {
            try {
                return view('request.CreateRequest');
            } // try { ... }
            catch(Exception $error) {
                return redirect()->route('raiz');
            } // catch(Exception $error) { ... }
        } // public function index(Request $request) { ... }

        public function create(Request $request) {
            $titulo     =   $request->input('tituloBPMS');
            $idEmpresa  =   $request->input('idEmpresaBPMS');
            $idProcesso =   $request->input('idProcessoBPMS');
            $idTipo     =   $request->input('idTipoBPMS');
            $arquivos   =   $request->file('arquivoBPMS');

            if(is_null($titulo) || is_null($idEmpresa) || is_null($idProcesso) || is_null($idTipo)) return back();

            // Validação dos dados
            $cabecalho  =   DB::table('empresa')
                            ->join('processo','processo.id_empresa','empresa.id_empresa')
                            ->join('tipo_processo','tipo_processo.id_processo','processo.id_processo')
                            ->where('empresa.situacao',true)
                            ->where('processo.situacao',true)
                            ->where('tipo_processo.situacao',true)
                            ->where('empresa.id_empresa',intval($idEmpresa))
                            ->where('processo.id_processo',intval($idProcesso))
                            ->where('tipo_processo.id_tipo_processo',intval($idTipo))
                            ->select(
                                'empresa.id_empresa',
                                'processo.id_processo',
                                'tipo_processo.id_tipo_processo',
                                'tipo_processo.id_situacao',
                                'tipo_processo.sla'
                            )
                            ->first();

            if(is_null($cabecalho) || !isset($cabecalho->id_tipo_processo)) return back();

            $dbReg      =   DB::table('pergunta_tipo')
                            ->where('pergunta_tipo.id_tipo_processo',intval($idTipo))
                            ->where('pergunta_tipo.situacao',true)
                            ->orderBy('pergunta_tipo.ordem','asc')
                            ->get();
            
            $itemChamado    =   [];

            foreach ($dbReg as $conteudo) {
                // Datetime coleta 2 campos
                if($conteudo->tipo == 'datetime') {
                    $dataHora       =   $request->input('questao_'.$conteudo->id_pergunta_tipo.'_data').' '.$request->input('questao_'.$conteudo->id_pergunta_tipo.'_hora');
                    array_push($itemChamado,(object)[
                        'questao'   =>  $conteudo->descricao,
                        'tipo'      =>  $conteudo->tipo,
                        'alteraVenc'=>  $conteudo->alt_data_vencimento,
                        'resposta'  =>  Carbon::parse($dataHora),
                    ]);
                }
                elseif($conteudo->tipo == 'date') {
                    array_push($itemChamado,(object)[
                        'questao'   =>  $conteudo->descricao,
                        'tipo'      =>  $conteudo->tipo,
                        'alteraVenc'=>  $conteudo->alt_data_vencimento,
                        'resposta'  =>  Carbon::parse($request->input('questao_'.$conteudo->id_pergunta_tipo))->startOfDay(),
                    ]);
                }
                else  {
                    array_push($itemChamado,(object)[
                        'questao'   =>  $conteudo->descricao,
                        'tipo'      =>  $conteudo->tipo,
                        'alteraVenc'=>  false,
                        'resposta'  =>  $request->input('questao_'.$conteudo->id_pergunta_tipo),
                    ]);
                }
            } // foreach ($dbReg as $conteudo) { ... }

            if(count($dbReg) <> count($itemChamado)) return back();

            if(Carbon::now()->isoWeekday() === 6) {
                $dataCriacao    =   Carbon::now()->addDays(2);
                $dataVencimento =   Carbon::now()->addDays(2)->addMinutes($cabecalho->sla);
            }
            elseif(Carbon::now()->isoWeekday() === 7) {
                $dataCriacao    =   Carbon::now()->addDays(1);
                $dataVencimento =   Carbon::now()->addDays(1)->addMinutes($cabecalho->sla);
            }
            else {
                $dataCriacao    =   Carbon::now();
                $dataVencimento =   Carbon::now()->addMinutes($cabecalho->sla);
            }

            if($dataVencimento->isoWeekday() === 6) {
                $dataVencimento =   Carbon::now()->addDays(2)->addMinutes($cabecalho->sla);
            }
            elseif($dataVencimento->isoWeekday() === 7) {
                $dataVencimento =   Carbon::now()->addDays(1)->addMinutes($cabecalho->sla);
            }

            $chamadoID                      =   new Chamado;
            $chamadoID->id_situacao         =   $cabecalho->id_situacao;
            $chamadoID->id_empresa          =   $cabecalho->id_empresa;
            $chamadoID->id_processo         =   $cabecalho->id_processo;
            $chamadoID->id_tipo_processo    =   $cabecalho->id_tipo_processo;
            $chamadoID->data_criacao        =   $dataCriacao;
            $chamadoID->data_vencimento     =   $dataVencimento;
            $chamadoID->id_solicitante      =   Auth::user()->id;
            $chamadoID->url                 =   $_SERVER['HTTP_HOST'];
            $chamadoID->titulo              =   trim($titulo);
            $chamadoID->situacao            =   true;
            $chamadoID->data_cria           =   Carbon::now();
            $chamadoID->data_alt            =   Carbon::now();
            $chamadoID->usr_cria            =   Auth::user()->id;
            $chamadoID->usr_alt             =   Auth::user()->id;
            $chamadoID->save();

            foreach ($itemChamado as $value) {
                if($value->alteraVenc) {
                    try {
                        DB::beginTransaction();
                        DB::table('chamado')
                        ->where('chamado.id_chamado',$chamadoID->id_chamado)
                        ->update([
                            'data_vencimento'   =>  $value->resposta,
                        ]);
                        DB::commit();
                    }
                    catch(Exception $erro){
                        DB::rollback();
                    }
                } // if($value->alteraVenc) { ... }

                 if($value->tipo == 'user' && $value->resposta !== '' && !is_null($value->resposta)) {
                    try {
                        DB::beginTransaction();
                        DB::table('chamado')
                        ->where('chamado.id_chamado',$chamadoID->id_chamado)
                        ->update([
                            'id_responsavel'   =>  intval($value->resposta),
                        ]);
                        DB::commit();
                    }
                    catch(Exception $erro){
                        DB::rollback();
                    }
                 } // if($value->type == 'user' && $value->resposta !== '' && !is_null($value->resposta)) { ... }

                try {
                    DB::beginTransaction();
                    DB::table('chamado_item')->insert([
                        'id_chamado'    =>  $chamadoID->id_chamado,
                        'tipo'          =>  $value->tipo,
                        'questao'       =>  $value->questao,
                        'resposta'      =>  $value->resposta,
                        'data_cria'     =>  Carbon::now(),
                        'data_alt'      =>  Carbon::now(),
                        'usr_cria'      =>  Auth::user()->id,
                        'usr_alt'       =>  Auth::user()->id,

                    ]);
                    DB::commit();
                }
                catch(Exception $erro) {
                    DB::rollback();
                }
            } // foreach ($itemChamado as $value) { ... }

            
            $existeArq  =    ($request->hasFile('arquivoBPMS') && count($arquivos) > 0) ? true : false;

            if($existeArq) {
                try {
                    foreach($arquivos as $chave => $arquivo) {
                        if($arquivo->isValid()) {
                            try {
                                $nomeServidor       =   Carbon::now()->timestamp.'-'.$chave.'.'.$arquivo->getClientOriginalExtension();
                                
                                DB::beginTransaction();
                                DB::table('arquivo')
                                ->insert([
                                    'id_chamado'    =>  $chamadoID->id_chamado,
                                    'nome_servidor' =>  $nomeServidor,
                                    'nome_arquivo'  =>  $arquivo->getClientOriginalName(),
                                    'extensao'      =>  $arquivo->getClientOriginalExtension(),
                                    'mime'          =>  $arquivo->getMimeType(),
                                    'tamanho'       =>  $arquivo->getSize(),
                                    'data_cria'     =>  Carbon::now(),
                                    'data_alt'      =>  Carbon::now(),
                                    'usr_cria'      =>  Auth::user()->id,
                                    'usr_alt'       =>  Auth::user()->id,
                                ]);
                                DB::commit();
    
                                $upload = $arquivo->storeAs('chamado', $nomeServidor);
                            } // try { ... }
                            catch(Exception $erro) {
                                DB::rollback();
                            } // catch(Exception $erro) { ... }
                        }
                    } // foreach($arquivos as $arquivo) { ... }
                }
                catch(Exception $erro) {
                    return redirect()->route('request.index');
                } // catch(Exception $erro) { ... }
            }
            

            try {
                $responsavel    =   DB::table('processo')->where('processo.id_processo',$chamadoID->id_processo)->first();
                $usuarioEmail   =   User::find($responsavel->id_usr_responsavel);
                // Envia ao responsável pelo processo
                Mail::to($usuarioEmail->email)->send(new ChamadoMail($usuarioEmail, $chamadoID->id_chamado));
                // Envia ao solicitante
                Mail::to(Auth::user()->email)->send(new ChamadoMail(Auth::user(), $chamadoID->id_chamado));
            }
            catch(Exception $erro){
                dd($erro);
	    }

            return redirect()->route('request.list');
        } // public function create(Request $request) { ... }
    } // class CreateRequest extends Controller { ... }
