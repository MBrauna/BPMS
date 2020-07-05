@extends('layouts.bpms')

@section('titulo','Administração de fluxo de situações')

@section('corpo')
    <div class="row">
        <div class="col-12 mb-1">
            <a href="{{ route('admin.tipo.listar',['idProcessoBPMS' => $tipo->id_processo]) }}" class="btn btn-block btn-primary btn-sm">
                <span class="d-flex justify-content-between">
                    <i class="fas fa-chevron-circle-left"></i>
                    <span>Voltar</span>
                    <i class="fas fa-chevron-circle-left"></i>
                </span>
            </a>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <small>Cadastro de fluxos para {{ $tipo->descricao }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalFluxo">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($fluxos) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhum fluxo cadastrado!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Situação Atual</small></th>
                                <th><small>Situação Posterior</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($fluxos as $fluxo)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($fluxo->id_fluxo_situacao,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $fluxo->id_situacao_atual }}</small></td>
                                    <td><small>{{ $fluxo->id_situacao_posterior }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <form method="POST" action="{{ route('admin.arvore.remover') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $fluxo->id_fluxo_situacao }}" name="id_fluxo_situacao">
                                                    <input type="hidden" value="{{ $fluxo->id_tipo_processo }}" name="id_tipo_processo">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-block" data-toggle="tooltip" data-placement="left" title="Remover fluxo">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalFluxo{{ $fluxo->id_fluxo_situacao }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalFluxo" tabindex="-1" role="dialog" aria-labelledby="modalFluxoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.arvore.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalFluxoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de tipo de processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $tipo->id_tipo_processo }}" name="id_tipo_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_situacao_atual">Situação atual:</label>
                            <select class="form-control form-control-sm" id="id_situacao_atual" name="id_situacao_atual" required>
                                <option value="" selected>Nenhuma situação cadastrada</option>
                                @foreach($situacoes as $situacao)
                                    <option value="{{ $situacao->id_situacao }}">{{ $situacao->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_situacao_posterior">Situação posterior:</label>
                            <select class="form-control form-control-sm" id="id_situacao_posterior" name="id_situacao_posterior" required>
                                <option value="" selected>Nenhuma situação cadastrada</option>
                                @foreach($situacoes as $situacao)
                                    <option value="{{ $situacao->id_situacao }}">{{ $situacao->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar Tipo</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.$(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection