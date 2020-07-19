@extends('layouts.bpms')

@section('titulo','Arquivo')

@section('corpo')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body login-card">
                    <form class="row was-validated" method="POST" action="{{ route('file.create') }}" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <div class="col-12">
                            <div class="form-group">
                                <label for="usuarios">Para quem deseja enviar o arquivo?</label>
                                <select class="js-example-basic-multiple form-control form-control-sm" id="usuarios" name="usuarios[]" multiple="multiple" required>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 pb-2">
                            <div class="custom-file">
                                <input type="file" name="arquivoBPMS[]" class="custom-file-input" id="arquivoBPMS" multiple data-show-upload="true" data-show-caption="true" required>
                                <label class="custom-file-label" for="arquivoBPMS" data-browse="Selecionar arquivo">Selecione o(s) arquivo(s)</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block btn-sn" type="submit">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 pt-3">
            <div class="card">
                <div class="card-body login-card">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-send-tab" data-toggle="pill" href="#pills-send" role="tab" aria-controls="pills-send" aria-selected="true">
                                Enviados
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-received-tab" data-toggle="pill" href="#pills-received" role="tab" aria-controls="pills-received" aria-selected="false">
                                Recebidos
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-send" role="tabpanel" aria-labelledby="pills-send-tab">
                            <ul class="list-group">
                            @if(count($enviados) <= 0)
                                <li class="list-group-item">Nenhum arquivo enviado</li>
                            @else
                            @foreach($enviados as $enviado)
                            <li class="list-group-item row d-flex justify-content-between">
                                <div class="col-12 col-sm-6">
                                    <a href="{{ Storage::url('compartilhado/'.$enviado->nome_servidor) }}" target="_blank">
                                        <i class="fas fa-file-code text-primary"></i>
                                    </a> - <a href="{{ Storage::url('compartilhado/'.$enviado->nome_servidor) }}" target="_blank">{{ $enviado->nome_arquivo }}</a>
                                </div>

                                <div class="col-12 col-sm-3 row">
                                    <small class="col-12 text-right">Enviado para {{ consulta_usuario($enviado->id_para_usuario)->name ?? ''}}</small>
                                    <small class="col-12 text-right">{{ Carbon\Carbon::parse($enviado->data_cria)->format('d/m/Y H:i:s') }}</small>
                                </div>
                            </li>
                            @endforeach
                            @endif
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="pills-received" role="tabpanel" aria-labelledby="pills-received-tab">
                            <ul class="list-group">
                                @if(count($recebidos) <= 0)
                                    <li class="list-group-item">Nenhum arquivo recebido</li>
                                @else
                                @foreach($recebidos as $recebido)
                                <li class="list-group-item row d-flex justify-content-between">
                                    <div class="col-12 col-sm-6">
                                        <a href="{{ Storage::url('compartilhado/'.$recebido->nome_servidor) }}" target="_blank">
                                            <i class="fas fa-file-code text-primary"></i>
                                        </a> - <a href="{{ Storage::url('compartilhado/'.$recebido->nome_servidor) }}" target="_blank">{{ $recebido->nome_arquivo }}</a>
                                    </div>
    
                                    <div class="col-12 col-sm-3 row">
                                        <small class="col-12 text-right">Enviado por {{ consulta_usuario($recebido->usr_cria)->name ?? ''}}</small>
                                        <small class="col-12 text-right">{{ Carbon\Carbon::parse($recebido->data_cria)->format('d/m/Y H:i:s') }}</small>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                                </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 


@section('scripts')
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                theme: 'bootstrap4',
            });
        });

        $(document).ready(function () {
            bsCustomFileInput.init()
        })
    </script>
@endsection
