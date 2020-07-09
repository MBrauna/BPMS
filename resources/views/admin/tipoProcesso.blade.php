@extends('layouts.bpms')

@section('titulo','Administração de tipos de processos')

@section('corpo')
    <div class="row">
        <div class="col-12 mb-1">
            <a href="{{ route('admin.processo.listar',['idEmpresaBPMS' => $processo->id_empresa]) }}" class="btn btn-block btn-primary btn-sm">
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
                    <small>Cadastro de Tipos para {{ $processo->descricao }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalTipo">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($tipos) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhum tipo de processo cadastrado!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Descrição</small></th>
                                <th><small>Situação inicial</small></th>
                                <th><small>Redireciona processo</small></th>
                                <th><small>Opção</small></th>
                                <th><small>ordem</small></th>
                                <th><small>S.L.A.</small></th>
                                <th><small>Situação</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($tipos as $tipo)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($tipo->id_tipo_processo,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $tipo->descricao }}</small></td>
                                    <td><small>{{ consulta_situacao($tipo->id_situacao)->descricao }}</small></td>
                                    <td><small>{{ is_null($tipo->id_processo_redireciona) ? 'Sem redirecionamento' : consulta_processo($tipo->id_processo_redireciona)->descricao }}</small></td>
                                    <td><small>{{ $tipo->questao }}</small></td>
                                    <td><small>{{ $tipo->ordem }}</small></td>
                                    <td><small>{{ $tipo->sla }}</small></td>
                                    <td><small>{{ ($tipo->situacao) ? 'Ativo' : 'Inativo' }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <form method="POST" action="{{ route('admin.arvore.listar') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $tipo->id_tipo_processo }}" name="idTipoProcessoBPMS">
                                                    <button type="submit" class="btn btn-sm btn-success btn-block" data-toggle="tooltip" data-placement="left" title="Fluxo de situações">
                                                        <i class="fas fa-sitemap"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.questao.listar') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $tipo->id_tipo_processo }}" name="idTipoProcessoBPMS">
                                                    <button type="submit" class="btn btn-sm btn-success btn-block" data-toggle="tooltip" data-placement="left" title="Questões">
                                                        <i class="far fa-eye"></i>
                                                    </button>
                                                </form>
                                                <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalTipo{{ $tipo->id_tipo_processo }}">
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


    <div class="modal fade" id="modalTipo" tabindex="-1" role="dialog" aria-labelledby="modalTipoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.tipo.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalTipoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de tipo de processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $processo->id_processo }}" name="id_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Descrição:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="questao">Questão:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="questao" name="questao" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_situacao">Situação inicial:</label>
                            <select class="form-control form-control-sm" id="id_situacao" name="id_situacao">
                                <option value="" selected>Nenhuma situação cadastrada</option>
                                @foreach($situacoes as $situacao)
                                    <option value="{{ $situacao->id_situacao }}">{{ $situacao->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_processo_redireciona">Redireciona:</label>
                            <select class="form-control form-control-sm" id="id_processo_redireciona" name="id_processo_redireciona">
                                <option value="" selected>Nenhuma processo para redirecionamento</option>
                                @foreach($processos as $proc)
                                    <option value="{{ $proc->id_processo }}">{{ $proc->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="ordem">Ordem:</label>
                            <input type="number" min="0" max="9999" class="form-control form-control-sm" id="ordem" name="ordem" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sla">S.L.A (minutos):</label>
                            <input type="number" min="0" max="9999999" class="form-control form-control-sm" id="sla" name="sla" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                <option value="1">Ativo</option>
                                <option value="0" selected>Inativo</option>
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

    @foreach($tipos as $tipo)
    <div class="modal fade" id="modalTipo{{ $tipo->id_tipo_processo }}" tabindex="-1" role="dialog" aria-labelledby="modalTipoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.tipo.editar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalTipoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de situação</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $processo->id_processo }}" name="id_processo">
                        <input type="hidden" value="{{ $tipo->id_tipo_processo }}" name="id_tipo_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Descrição:</label>
                        <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $tipo->descricao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="questao">Questão:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="questao" name="questao" value="{{ $tipo->questao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_situacao">Situação inicial:</label>
                            <select class="form-control form-control-sm" id="id_situacao" name="id_situacao">
                                @foreach($situacoes as $situacao)
                                    @if($situacao->id_situacao == $tipo->id_situacao)
                                        <option value="{{ $situacao->id_situacao }}" selected>{{ $situacao->descricao }}</option>
                                    @else
                                        <option value="{{ $situacao->id_situacao }}">{{ $situacao->descricao }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_processo_redireciona">Redireciona:</label>
                            <select class="form-control form-control-sm" id="id_processo_redireciona" name="id_processo_redireciona">
                                @if(is_null($tipo->id_processo_redireciona))
                                    <option value="" selected>Nenhum processo para redirecionamento</option>
                                @else
                                    <option value="">Nenhum processo para redirecionamento</option>
                                @endif
                                @foreach($processos as $proc)
                                    @if($proc->id_processo == $tipo->id_processo)
                                        <option value="{{ $proc->id_processo }}" selected>{{ $proc->descricao }}</option>
                                    @else
                                        <option value="{{ $proc->id_processo }}">{{ $proc->descricao }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="ordem">Ordem:</label>
                            <input type="number" min="0" max="9999" class="form-control form-control-sm" id="ordem" name="ordem" value="{{ $tipo->ordem }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sla">S.L.A (minutos):</label>
                            <input type="number" min="0" max="9999999" class="form-control form-control-sm" id="sla" name="sla" value="{{ $tipo->sla }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                @if($tipo->situacao)
                                    <option value="1" selected>Ativo</option>
                                    <option value="0">Inativo</option>
                                @else
                                    <option value="1">Ativo</option>
                                    <option value="0" selected>Inativo</option>
                                @endif
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
    @endforeach
@endsection

@section('scripts')
    <script>
        window.$(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
@endsection