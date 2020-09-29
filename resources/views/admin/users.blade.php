@extends('layouts.bpms')

@section('titulo','Administração de usuários')

@section('corpo')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <small>Usuários</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalUsuario">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($usuarios) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhum usuário cadastrado!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Nome</small></th>
                                <th><small>e-Mail</small></th>
                                <th><small>Perfil de acesso</small></th>
                                <th><small>Data cadastro</small></th>
                                <th><small>Data alteração</small></th>
                                <th><small>Ações</small></th>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $usuario)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($usuario->id,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $usuario->name }}</small></td>
                                    <td><small>{{ $usuario->email }}</small></td>
                                    <td><small>{{ ($usuario->administrador) ? 'Administrador' : 'Usuário' }}</small></td>
                                    <td><small>{{ Carbon\Carbon::parse($usuario->created_at)->format('d/m/Y h:i:s') }}</small></td>
                                    <td><small>{{ Carbon\Carbon::parse($usuario->updated_at)->format('d/m/Y h:i:s') }}</small></td>
                                    <td>
                                        <div class="d-flex bd-highlight">
                                            <form method="POST" action="{{ route('admin.usuario.perfil.listar') }}" class="flex-fill">
                                                @csrf
                                                <input type="hidden" value="{{ $usuario->id }}" name="idUsuarioBPMS">
                                                <button type="submit" class="btn btn-sm btn-success btn-block">
                                                    <i class="fas fa-users-cog"></i>
                                                </button>
                                            </form>
                                            <button class="btn btn-sm btn-warning flex-fill ml-1" data-toggle="modal" data-target="#modalUsuario{{ $usuario->id }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $usuarios->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalUsuario" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.usuario.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalUsuarioTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de situação</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="name">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="name" name="name" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="email">e-Mail:</label>
                            <input type="email" minlength="5" maxlength="350" class="form-control form-control-sm" id="email" name="email" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="administrador">Administrador:</label>
                            <select class="form-control form-control-sm" id="administrador" name="administrador" required>
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="senha">Senha:</label>
                            <input type="password" minlength="5" maxlength="40" class="form-control form-control-sm" id="senha" name="senha" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar situação</button>
                </div>
            </form>
        </div>
    </div>
    
    @foreach($usuarios as $usuario)
<div class="modal fade" id="modalUsuario{{ $usuario->id }}" tabindex="-1" role="dialog" aria-labelledby="modalUsuarioTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.usuario.editar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalUsuarioTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de situação</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id_usuario" value="{{ $usuario->id }}">
                    <div class="row">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="name">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="name" name="name" value="{{ $usuario->name }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="email">E-mail:</label>
                            <input type="email" minlength="5" maxlength="350" class="form-control form-control-sm" id="email" name="email" value="{{ $usuario->email }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="administrador">Administrador:</label>
                            <select class="form-control form-control-sm" id="administrador" name="administrador" required>
                                @if($usuario->administrador)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif

                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="senha">Senha:</label>
                            <input type="password" minlength="5" maxlength="40" class="form-control form-control-sm" id="senha" name="senha">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-sm btn-outline-light" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar situação</button>
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