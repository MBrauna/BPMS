@extends('layouts.bpms')

@section('titulo','Lista de agendamentos')

@section('corpo')
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-hover table-sm table-striped">
                <thead>
                    <tr class="bg-primary text-white">
                        <th scope="col"><small>#ID</small></th>
                        <th scope="col"><small>Tipo</small></th>
                        <th scope="col"><small>Processo Origem</small></th>
                        <th scope="col"><small>Tipo de solicitação de serviço</small></th>
                        <th scope="col"><small>Responsável Origem</small></th>
                        <!--<th scope="col"><small>Processo Destino</small></th>
                        <th scope="col"><small>Tipo de processo do Destino</small></th>
                        <th scope="col"><small>Responsável Destino</small></th>-->
                        <th scope="col"><small>Entregável</small></th>
                        <th scope="col"><small>Periodicidade</small></th>
                        <th scope="col"><small>Criado em</small></th>
                        <th scope="col"><small>Primeiro agendamento</small></th>
                        <th scope="col"><small>Próximo agendamento</small></th>
                        <th scope="col"><small>Ações</small></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                        <tr>
                            <th scope="row"><small>#{{ $item->id_entrada_solicitacao }}</small></td>
                            <td><small class="font-weigth-bold text-primary">{{ consulta_tipo_periodico($item->tipo) ?? 'Nenhum tipo' }}</small></td>
                            
                            <td><small>{{ consulta_processo($item->id_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_tipo($item->id_tipo_processo_origem)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_usuario($item->id_responsavel_origem)->name ?? 'Nenhum responsavel' }}</small></td>

                            <!--
                            @if($item->tipo == 2)
                            <td><small>{{ consulta_processo($item->id_processo_destino)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_tipo($item->id_tipo_processo_destino)->descricao ?? 'Não identificado' }}</small></td>
                            <td><small>{{ consulta_usuario($item->id_responsavel_destino)->name ?? 'Nenhum responsavel' }}</small></td>
                            @else
                            <td colspan="3"></td>
                            @endif
                            -->

                            <td><small>{{ $item->titulo }}</small></td>

                            <td><small>{{ consulta_periodicidade($item->periodicidade) ?? 'Nenhuma periodicidade'}}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_criacao)->format('d/m/Y H:i:s') }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_primeiro_agendamento)->format('d/m/Y H:i:s') }}</small></td>
                            <td><small>{{ \Carbon\Carbon::parse($item->data_proximo_agendamento)->format('d/m/Y H:i:s') }}</small></td>


                            <td>
                                <button class="btn btn-sm btn-warning flex-fill ml-1" data-toggle="modal" data-target="#modalAgenda{{ $item->id_entrada_solicitacao }}">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-sm btn-danger flex-fill ml-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
