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
                        <th scope="col"><small>#ID</small></th>
                        <th scope="col"><small>SLA</small></th>
                        <th scope="col"><small>Tipo</small></th>
                        <th scope="col"><small>Processo Origem</small></th>
                        <th scope="col"><small>Tipo de solicitação de serviço</small></th>
                        <th scope="col"><small>Responsável Origem</small></th>

                        <th scope="col"><small>Entregável</small></th>

                        <th scope="col"><small>Processo Destino</small></th>
                        <!-- <th scope="col"><small>Tipo de solicitação de serviço</small></th> -->
                        <th scope="col"><small>Responsável Destino</small></th>

                        <th scope="col"><small>Periodicidade</small></th>
                        <th scope="col"><small>Criado em</small></th>
                        <th scope="col"><small>Primeiro agendamento</small></th>
                        <th scope="col"><small>Solicitações criadas</small></th>
                        <th scope="col"><small>Próximo agendamento</small></th>
                        <th scope="col"><small>Ações</small></th>
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

                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ consulta_periodicidade($item->periodicidade) ?? 'Nenhuma periodicidade'}}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_criacao)->format('d/m/Y H:i:s') }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_primeiro_agendamento)->format('d/m/Y H:i:s') }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ $item->qtde_chamado ?? 0 }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable"><small>{{ \Carbon\Carbon::parse($item->data_proximo_agendamento)->format('d/m/Y H:i:s') }}</small></td>
                            <td data-toggle="collapse" data-target="#accordion_{{ $item->id_entrada_solicitacao }}" class="clickable">
                                <form method="POST" action="{{ route('object.remove') }}" class="flex-fill">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id_entrada_solicitacao }}" name="id_entrada_solicitacao">
                                    <button type="submit" class="btn btn-sm btn-danger btn-block">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="15">
                                <div id="accordion_{{ $item->id_entrada_solicitacao }}" class="collapse">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr class="bg-primary text-white" style="width: 100vw;">
                                                <th scope="col"><small>#ID</small></th>
                                                <th scope="col"><small>Questão</small></th>
                                                <th scope="col"><small>Resposta</small></th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr class="bg-primary text-white">
                                                <th scope="col"><small>#ID</small></th>
                                                <th scope="col"><small>Questão</small></th>
                                                <th scope="col"><small>Resposta</small></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @forelse ($item->listaQuestao as $contentItem)
                                                <tr>
                                                    <td><small># {{ $contentItem->id_entrada_sol_item}}</small></td>
                                                    <td><small># {{ $contentItem->questao }}</small></td>
                                                    <td><small># {{ $contentItem->resposta }}</small></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3">
                                                        <h6 class="text-primary text-center">
                                                            <i>Nenhuma questão preenchida</i><br/>
                                                            <i class="fas fa-frown"></i>
                                                        </h6>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
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