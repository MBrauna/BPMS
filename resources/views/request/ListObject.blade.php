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

    <div class="col-12 pt-3" style="overflow-x: auto;">
        <div class="table-responsive">
            <table class="table table-responsive table-hover table-sm table-striped">
                <thead>
                    <tr class="bg-primary text-white">
                        <th scope="col"><small>#ID</small></th>
                        <th scope="col"><small>SLA Fornecedor</small></th>
                        <th scope="col"><small>SLA Cliente</small></th>
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

                        <tr data-toggle="modal" data-target="#item_{{ $item->id_entrada_solicitacao }}">
                            <th scope="row"><small>#{{ $item->id_entrada_solicitacao }}</small></td>
                            <th><small>{{ $item->sla_fornecedor ? 'Sim' : 'Não' }}</small></td>
                            <th><small>{{ $item->sla_cliente ? 'Sim' : 'Não' }}</small></td>
                            <td><small class="font-weigth-bold text-primary">{{ consulta_tipo_periodico($item->tipo) ?? 'Nenhum tipo' }}</small></td>
                            
                            <td><small>{{ consulta_processo($item->id_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_tipo($item->id_tipo_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_usuario($item->id_responsavel_origem)->name ?? 'Nenhum responsavel' }}</small></td>

                            <td><small>{{ $item->titulo }}</small></td>

                            @if($item->tipo == 2)
                            <td><small>{{ consulta_processo($item->id_processo_destino)->descricao ?? 'Não identificado' }}</small></td>
                            <!-- <td><small>{{ consulta_tipo($item->id_tipo_processo_destino)->descricao ?? 'Não identificado' }}</small></td>-->
                            <td><small>{{ consulta_usuario($item->id_responsavel_destino)->name ?? 'Nenhum responsavel' }}</small></td>
                            @else
                            <td colspan="2"></td>
                            @endif

                            <td><small>{{ consulta_periodicidade($item->periodicidade) ?? 'Nenhuma periodicidade'}}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_criacao)->format('d/m/Y H:i:s') }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_primeiro_agendamento)->format('d/m/Y H:i:s') }}</small></td>
                            <td><small>{{ $item->qtde_chamado ?? 0 }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_proximo_agendamento)->format('d/m/Y H:i:s') }}</small></td>

                            <td>
                                <form method="POST" action="{{ route('object.remove') }}" class="flex-fill">
                                    @csrf
                                    <input type="hidden" value="{{ $item->id_entrada_solicitacao }}" name="id_entrada_solicitacao">
                                    <button type="submit" class="btn btn-sm btn-danger btn-block">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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