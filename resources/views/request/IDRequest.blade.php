@extends('layouts.bpms')

@section('titulo','Solicitação de serviço')

@section('corpo')
    <div class="card was-validated border-primary shadow-sm">
        <div class="card-header bg-primary text-center text-white">
            Solicitação de Serviço
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-2">
                    <label for="tituloBPMS">Solicitação de Serviço</label>
                    <input type="text" class="form-control form-control-sm text-right" name="tituloBPMS" value="{{ '#'.$chamado->id_chamado }}" readonly>
                </div>
                <div class="form-group col-10">
                    <label for="tituloBPMS">O que foi solicitado</label>
                    <input type="text" class="form-control form-control-sm" id="tituloBPMS" name="tituloBPMS" placeholder="Informe o título da solicitação de forma objetiva" value="{{ $chamado->titulo }}" readonly>
                </div>
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="empresaBPMS">Empresa</label>
                    <input type="text" class="form-control form-control-sm" id="empresaBPMS" name="empresaBPMS" value="{{ consulta_empresa($chamado->id_empresa)->descricao ?? '' }}" readonly>
                </div>

                <input type="hidden" name="idProcessoBPMS">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="processoBPMS">Processo</label>
                    <input type="text" class="form-control form-control-sm" id="processoBPMS" name="processoBPMS" value="{{ consulta_processo($chamado->id_processo)->descricao ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="tipoBPMS">Fluxo</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ consulta_tipo($chamado->id_tipo_processo)->descricao ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="tipoBPMS">Situação atual</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ consulta_situacao($chamado->id_situacao)->descricao ?? '' }}" readonly>
                </div>


                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label for="tipoBPMS">Data abertura</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ Carbon\Carbon::parse($chamado->data_criacao)->format('d/m/Y H:i:s') ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label for="tipoBPMS">Data conclusão</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ (is_null($chamado->data_conclusao)) ? 'Não concluído' : Carbon\Carbon::parse($chamado->data_conclusao)->format('d/m/Y H:i:s') ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label for="tipoBPMS">Data limite</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ Carbon\Carbon::parse($chamado->data_vencimento)->format('d/m/Y H:i:s') ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <label for="tipoBPMS">Prazo</label>
                    <input type="text" class="form-control form-control-sm {{ $classeCor }}" name="tipoBPMS" value="{{ (is_null($chamado->data_conclusao) ? (Carbon\Carbon::now()->greaterThan(Carbon\Carbon::parse($chamado->data_vencimento)) ? Carbon\Carbon::now()->diff(Carbon\Carbon::parse($chamado->data_vencimento))->format('%ya %mm %dd %H:%I:%S') : Carbon\Carbon::now()->diff(Carbon\Carbon::parse($chamado->data_vencimento))->format('%ya %mm %dd %H:%I:%S')) : Carbon\Carbon::parse($chamado->data_criacao)->diff(Carbon\Carbon::parse($chamado->data_conclusao))->format('%ya %mm %dd %H:%I:%S')) }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="tipoBPMS">Solicitante</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ consulta_usuario($chamado->id_solicitante)->name ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="tipoBPMS">Responsável pelo processo</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ consulta_usuario(consulta_processo($chamado->id_processo)->id_usr_responsavel)->name ?? '' }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <label for="tipoBPMS">Responsável pelo atendimento</label>
                    <input type="text" class="form-control form-control-sm" name="tipoBPMS" value="{{ (is_null($chamado->id_responsavel)) ? 'Não atribuído' : consulta_usuario($chamado->id_responsavel)->name }}" readonly>
                </div>

                

                @foreach($chamadoItem as $item)
                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="questao_{{ $item->id_chamado_item ?? ''}}">{{ $item->questao ?? ''}}</label>
                        @switch($item->tipo)
                            @case('date')
                                <input type="{{ $item->tipo }}"  class="form-control form-control-sm" value="{{ $item->resposta ?? '' }}" readonly>
                                @break
                            @case('datetime')
                                <div class="col-12">
                                    <div class="row">
                                        <input class="form-control form-control-sm" type="datetime" value="{{ \Carbon\Carbon::parse($item->resposta)->format('d/m/Y H:i:s') }}" readonly>
                                    </div>
                                </div>
                                @break
                            @case('longtext')
                                <textarea minlength="20" class="form-control form-control-sm" readonly>{{ $item->resposta }}</textarea>
                                @break
                            @default
                                <input type="{{ $item->tipo }}" class="form-control form-control-sm" value="{{ $item->resposta }}" readonly>
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="taefa-tab" data-toggle="pill" href="#tarefa" role="tab" aria-controls="tarefa" aria-selected="true">
                        Histórico
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="arquivo-tab" data-toggle="pill" href="#arquivo" role="tab" aria-controls="arquivo" aria-selected="true">
                        Arquivos enviados
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade" id="arquivo" role="tabpanel" aria-labelledby="arquivo-tab">
                    <ul class="list-group">
                        @if(count($arquivos) <= 0)
                            <li class="list-group-item">Nenhum arquivo anexado a este chamado</li>
                        @else
                        @foreach($arquivos as $arquivo)
                            <li class="list-group-item d-flex justify-content-between">
                                @if(is_null($arquivo->id_tarefa))
                                <a href="{{ Storage::url('chamado/'.$arquivo->nome_servidor) }}" target="_blank">
                                @else
                                <a href="{{ Storage::url('tarefa/'.$arquivo->nome_servidor) }}" target="_blank">
                                @endif
                                    <i class="fas fa-file-archive"></i> - {{ $arquivo->nome_arquivo }}
                                </a>
                                <span>Arquivo anexado por {{ consulta_usuario($arquivo->usr_cria)->name }}</span>
                            </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                <div class="tab-pane fade show active" id="tarefa" role="tabpanel" aria-labelledby="tarefa-tab">
                    <ul class="list-group">
                        @if(count($tarefas) <= 0)
                            <li class="list-group-item text-center">Nenhuma tarefa realizada para este chamado</li>
                        @else
                        @foreach($tarefas as $tarefa)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="TituloTarefa">Informação:</label>
                                        <textarea class="form-control form-control-sm" readonly>{{ $tarefa->conteudo }}</textarea>
                                    </div>


                                    <div class="col-12 col-sm-6 col-md-6 form-group">
                                        <label for="dataTarefa">Situação anterior:</label>
                                        <input type="datetime" name="dataTarefa" class="form-control form-control-sm" value="{{ consulta_situacao($tarefa->id_situacao_anterior)->descricao ?? '' }}" readonly>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 form-group">
                                        <label for="dataTarefa">Situação atual:</label>
                                        <input type="datetime" name="dataTarefa" class="form-control form-control-sm" value="{{ consulta_situacao($tarefa->id_situacao_atribuida)->descricao ?? '' }}" readonly>
                                    </div>

                                    <div class="col-12 col-sm-6 col-md-6 form-group">
                                        <label for="idUsuario">Realizada por:</label>
                                        <input type="text" name="idUsuario" class="form-control form-control-sm" value="{{ consulta_usuario($tarefa->usr_cria)->name ?? '' }}" readonly>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-6 form-group">
                                        <label for="dataTarefa">Data:</label>
                                        <input type="datetime" name="dataTarefa" class="form-control form-control-sm" value="{{ \Carbon\Carbon::parse($tarefa->data_cria)->format('d/m/Y H:i:s') }}" readonly>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 form-group">
                                        <label for="dataTarefa">Arquivo(s) anexados:</label>
                                        <div class="form-control">
                                            @foreach($arquivos as $arquivo)
                                            @if($arquivo->id_tarefa == $tarefa->id_tarefa)
                                            <a href="{{ Storage::url('chamado/'.$arquivo->nome_servidor) }}" target="_blank" class="text-primary mr-1">
                                                <i class="fas fa-file-archive"></i> {{ $arquivo->nome_arquivo}}
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection