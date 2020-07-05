@extends('layouts.bpms')

@section('titulo','Solicitação de serviço')

@section('corpo')
    <div class="card was-validated border-primary shadow-sm">
        <div class="card-header bg-primary text-center text-white">
            Solicitação de Serviço
        </div>
        <div class="card-body">
            <div class="row">
                <div class="form-group col-12">
                    <label for="tituloBPMS">Titulo</label>
                    <input type="text" minlength="20" maxlength="320" class="form-control form-control-sm" id="tituloBPMS" name="tituloBPMS" placeholder="Informe o título da solicitação de forma objetiva" value="{{ $chamado->titulo }}" readonly>
                </div>
                <div class="form-group col-12">
                    <label for="empresaBPMS">Empresa</label>
                    <input type="text" class="form-control form-control-sm" id="empresaBPMS" name="empresaBPMS" value="{{ $chamado->id_empresa }}" readonly>
                </div>

                <input type="hidden" name="idProcessoBPMS">
                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="processoBPMS">Processo</label>
                    <input type="text" class="form-control form-control-sm" id="processoBPMS" name="processoBPMS" value="{{ $chamado->id_processo }}" readonly>
                </div>

                <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <label for="tipoBPMS">Tipo</label>
                    <input type="text" class="form-control form-control-sm" id="tipoBPMS" name="tipoBPMS" value="{{ $chamado->id_tipo_processo }}" readonly>
                </div>

                @foreach($chamadoItem as $item)
                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label for="questao_{{ $item->id_chamado_item }}">{{ $item->questao }}</label>
                        @switch($item->tipo)
                            @case('date')
                                <input type="{{ $item->tipo }}"  class="form-control form-control-sm" value="{{ $item->resposta }}" readonly>
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
                    <a class="nav-link active" id="arquivo-tab" data-toggle="pill" href="#arquivo" role="tab" aria-controls="arquivo" aria-selected="true">
                        Arquivos enviados
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="taefa-tab" data-toggle="pill" href="#tarefa" role="tab" aria-controls="tarefa" aria-selected="true">
                        Tarefas
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="arquivo" role="tabpanel" aria-labelledby="arquivo-tab">
                    <ul class="list-group">
                        @if(count($arquivos) <= 0)
                            <li class="list-group-item">Nenhum arquivo anexado a este chamado</li>
                        @else
                        @foreach($arquivos as $arquivo)
                            <li class="list-group-item d-flex justify-content-between">
                                <a href="{{ Storage::url('chamado/'.$arquivo->nome_servidor) }}" target="_blank">
                                    <i class="fas fa-file-archive"></i> - {{ $arquivo->nome_arquivo }}
                                </a>
                                <span>Arquivo anexado por {{ $arquivo->usr_cria }}</span>
                            </li>
                        @endforeach
                        @endif
                    </ul>
                </div>
                <div class="tab-pane fade" id="tarefa" role="tabpanel" aria-labelledby="tarefa-tab">
                    <ul class="list-group">
                        @if(count($tarefas) <= 0)
                            <li class="list-group-item text-center">Nenhuma tarefa realizada para este chamado</li>
                        @else
                        @foreach($tarefas as $tarefa)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label for="TituloTarefa">Informação:</label>
                                        <input type="text" name="TituloTarefa" id="TituloTarefa" class="form-control form-control-sm" value="{{ $tarefa->conteudo }}" readonly>
                                    </div>
                                    <div class="col-sm-12 col-6 form-group">
                                        <label for="idUsuario">Realizada por:</label>
                                        <input type="text" name="idUsuario" id="idUsuario" class="form-control form-control-sm" value="{{ $tarefa->usr_cria }}" readonly>
                                    </div>
                                    <div class="col-sm-12 col-6 form-group">
                                        <label for="dataTarefa">Data:</label>
                                        <input type="datetime" name="dataTarefa" id="dataTarefa" class="form-control form-control-sm" value="{{ \Carbon\Carbon::parse($tarefa->data_cria)->format('d/m/Y H:i:s') }}" readonly>
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