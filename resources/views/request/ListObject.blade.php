@extends('layouts.bpms')

@section('titulo','Lista de agendamentos')

@section('corpo')
    <div class="col-12">
        <filtro-bpms></filtro-bpms>
    </div>

    <form class="col-12 pt-2" method="POST" action="{{ route('object.list') }}">
        @csrf
        <input type="hidden" id="idBpms" name="idBpms" value="">
        <input type="hidden" id="tituloBpms" name="tituloBpms" value="">
        <input type="hidden" id="idEmpresaBpms" name="idEmpresaBpms" value="">
        <input type="hidden" id="idProcessoBpms" name="idProcessoBpms" value="">
        <input type="hidden" id="idTipoBpms" name="idTipoBpms" value="">
        <input type="hidden" id="idSolicitanteBpms" name="idSolicitanteBpms" value="">
        <input type="hidden" id="idResponsavelBpms" name="idResponsavelBpms" value="">

        <button class="btn btn-sm btn-block btn-success">Atualizar lista</button>
    </form>

    <div class="col-12 pt-3">
        <div class="table-responsive">
            <table class="table table-responsive table-sm">
                <thead>
                    <tr class="bg-primary text-white">
                        <th scope="col" class="text-center"><small>#ID</small></th>
                        <th scope="col" class="text-center"><small>Acordo de nível de serviço</small></th>
                        <th scope="col" class="text-center"><small>Tipo</small></th>
                        <th scope="col" class="text-center"><small>Processo Origem</small></th>
                        <th scope="col" class="text-center"><small>Tipo de solicitação de serviço</small></th>
                        <th scope="col" class="text-center"><small>Responsável Origem</small></th>

                        <th scope="col" class="text-center"><small>Entregável</small></th>

                        <th scope="col" class="text-center"><small>Processo Destino</small></th>
                        <!-- <th scope="col"><small>Tipo de solicitação de serviço</small></th> -->
                        <th scope="col" class="text-center"><small>Responsável Destino</small></th>

                        <th scope="col" class="text-center"><small>Periodicidade</small></th>
                        <th scope="col" class="text-center"><small>Criado em</small></th>
                        <th scope="col" class="text-center"><small>Primeiro agendamento</small></th>
                        <th scope="col" class="text-center"><small>Solicitações criadas</small></th>
                        <th scope="col" class="text-center"><small>Próximo agendamento</small></th>
                        
                        @if(usuario_lider_processo())
                        <th scope="col" class="text-center"><small>Ações</small></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)

                        <tr>
                            <th scope="row" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>#{{ $item->id_entrada_solicitacao }}</small></td>
                            <td style="min-width: 20vw"><aprovar-objeto id="{{ $item->id_entrada_solicitacao }}"></aprovar-objeto></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small class="font-weigth-bold text-primary">{{ consulta_tipo_periodico($item->tipo) ?? 'Nenhum tipo' }}</small></td>
                            
                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_processo($item->id_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_tipo($item->id_tipo_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_usuario($item->id_responsavel_origem)->name ?? 'Nenhum responsavel' }}</small></td>

                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ $item->titulo }}</small></td>

                            @if($item->tipo == 2)
                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_processo($item->id_processo_destino)->descricao ?? 'Não identificado' }}</small></td>
                            <!-- <td><small>{{ consulta_tipo($item->id_tipo_processo_destino)->descricao ?? 'Não identificado' }}</small></td>-->
                            <td style="min-width: 10vw" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_usuario($item->id_responsavel_destino)->name ?? 'Nenhum responsavel' }}</small></td>
                            @else
                            <td colspan="2" data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"></td>
                            @endif

                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ $item->qtde_periodicidade.' '.consulta_periodicidade($item->periodicidade) ?? 'Nenhuma periodicidade'}}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_criacao)->format('d/m/Y H:i:s') }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_primeiro_agendamento)->format('d/m/Y H:i:s') }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable text-center"><small>{{ $item->qtde_chamado ?? 0 }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_proximo_agendamento)->format('d/m/Y H:i:s') }}</small></td>

                            @if(usuario_lider_processo())
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable">
                                <form method="POST" action="{{ route('object.remove') }}" class="flex-fill">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id_entrada_solicitacao }}" name="id_entrada_solicitacao">
                                    <button type="submit" class="btn btn-sm btn-danger btn-block">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="15">
                                <div id="accordion_{{ $item->id_entrada_solicitacao }}" class="collapse">
                                    <div class="card border-primary">
                                        <div class="card-body">

                                            <ul class="list-group">
                                            @forelse ($item->listaQuestao as $contentItem)
                                                <li class="list-group-item list-group-item-action">
                                                    <div class="form-group">
                                                        <label for="questao_{{ $contentItem->id_chamado_item ?? ''}}" class="text-primary">{{ $contentItem->questao ?? ''}}</label>
                                                        @switch($contentItem->tipo)
                                                            @case('date')
                                                                <input type="text" class="form-control-plaintext form-control-sm" value="{{ \Carbon\Carbon::parse($contentItem->resposta)->format('d/m/Y H:i:s') }}" disabled>
                                                                @break
                                                            @case('datetime')
                                                                <input type="text" class="form-control-plaintext form-control-sm" value="{{ \Carbon\Carbon::parse($contentItem->resposta)->format('d/m/Y H:i:s') }}" disabled>
                                                                @break
                                                            @case('longtext')
                                                                <textarea class="form-control-plaintext form-control-sm" disabled>{{ $contentItem->resposta }}</textarea>
                                                                @break
                                                            @default
                                                                <input type="text" class="form-control-plaintext form-control-sm" value="{{ $contentItem->resposta }}" disabled>
                                                        @endswitch
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="list-group-item">
                                                    <h6 class="text-primary text-center">
                                                        <i>Nenhuma questão preenchida</i><br/>
                                                        <i class="fas fa-frown"></i>
                                                    </h6>
                                                </li>
                                            @endforelse
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection