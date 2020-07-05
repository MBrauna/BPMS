@extends('layouts.bpms')

@section('titulo','Administração de processos')

@section('corpo')
    <div class="row">
        <div class="col-12 mb-1">
            <a href="{{ route('admin.empresa.listar') }}" class="btn btn-block btn-primary btn-sm">
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
                    <small>Cadastro de processos para {{ $empresa->descricao }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalDepartamento">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($processos) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhum processo cadastrado!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Descrição</small></th>
                                <th><small>Sigla</small></th>
                                <th><small>Situação</small></th>
                                <th><small>Dono do processo</small></th>
                                <th><small>Icone</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($processos as $processo)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($processo->id_processo,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $processo->descricao }}</small></td>
                                    <td><small>{{ $processo->sigla }}</small></td>
                                    <td><small>{{ ($processo->situacao) ? 'Ativo' : 'Inativo' }}</small></td>
                                    <td><small>{{ (is_null($processo->id_usr_responsavel)) ? 'Não atribuído' : ($processo->id_usr_responsavel) }}</small></td>
                                    <td><small><i class="{{ $processo->icone }}"></i> {{ $processo->icone }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <form method="POST" action="{{ route('admin.tipo.listar') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $processo->id_processo }}" name="idProcessoBPMS">
                                                    <button type="submit" class="btn btn-sm btn-success btn-block" data-toggle="tooltip" data-placement="left" title="Tipo de processo">
                                                        <i class="far fa-eye"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.situacao.listar') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $processo->id_processo }}" name="idProcessoBPMS">
                                                    <button type="submit" class="btn btn-sm btn-success btn-block" data-toggle="tooltip" data-placement="left" title="Situação">
                                                        <i class="fas fa-award"></i>
                                                    </button>
                                                </form>
                                                <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalDepartamento{{ $processo->id_processo }}">
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


    <div class="modal fade" id="modalDepartamento" tabindex="-1" role="dialog" aria-labelledby="modalDepartamentoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.processo.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalDepartamentoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de processos</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $empresa->id_empresa }}" name="id_empresa">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sigla">Sigla:</label>
                            <input type="text" minlength="2" maxlength="10" class="form-control form-control-sm" id="sigla" name="sigla" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="icone">Icone(<small><a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">FontAwesome</a></small>):</label>
                            <input type="text" minlength="3" maxlength="30" class="form-control form-control-sm" id="icone" name="icone" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_usuario_processo">Dono do processo:</label>
                            <select class="form-control form-control-sm" id="id_usuario_processo" name="id_usuario_processo">
                                <option value="" selected>Sem usuário responsável</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                <option value="1" selected>Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar processo</button>
                </div>
            </form>
        </div>
    </div>

    @foreach ($processos as $processo)
    <div class="modal fade" id="modalDepartamento{{ $processo->id_processo }}" tabindex="-1" role="dialog" aria-labelledby="modalDepartamentoTitulo{{ $processo->id_processo }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.processo.editar') }}" autocomplete="off">
                <div class="modal-header bg-dark text-center text-white">
                    <h5 class="modal-title" id="modalDepartamentoTitulo{{ $processo->id_processo }}"><i class="fas fa-pencil-alt"></i> Alterar processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" value="{{ $empresa->id_empresa }}" name="id_empresa">
                    <div class="row">
                        <input type="hidden" value="{{ $processo->id_processo }}" name="id_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $processo->descricao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sigla">Sigla:</label>
                            <input type="text" minlength="2" maxlength="10" class="form-control form-control-sm" id="sigla" name="sigla" value="{{ $processo->sigla }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="icone">Icone(<small><a href="https://fontawesome.com/icons" target="_blank" class="text-decoration-none">FontAwesome</a></small>):</label>
                            <input type="text" minlength="3" maxlength="30" class="form-control form-control-sm" id="icone" name="icone" value="{{ $processo->icone }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_usuario_processo">Dono do processo:</label>
                            <select class="form-control form-control-sm" id="id_usuario_processo" name="id_usuario_processo">
                                @if(is_null($processo->id_usr_responsavel))
                                    <option value="">Sem usuário responsável</option>
                                @else
                                    <option value="" selected>Sem usuário responsável</option>
                                @endif
                                @foreach($usuarios as $usuario)
                                    @if($usuario->id === $processo->id_usr_responsavel)
                                        <option value="{{ $usuario->id }}" selected>{{ $usuario->name }}</option>
                                    @else
                                        <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                @if($processo->situacao)
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
                <div class="modal-footer bg-dark">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-success">Cadastrar processo</button>
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