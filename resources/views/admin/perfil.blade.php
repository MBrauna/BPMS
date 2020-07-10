@extends('layouts.bpms')

@section('titulo','Administração de perfis')

@section('corpo')
    <div class="row">
        <div class="col-12">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <small>Cadastro de perfis</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalPerfil">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @isset($perfis)
                        @if(count($perfis) <= 0)
                        <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                            <div class="col-sm-12 col-6 col-md-4 text-center">
                                <h5 class="font-weight-bold">Nenhum perfil cadastrado</h5>
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
                                        <th><small>Criação</small></th>
                                        <th><small>Alteração</small></th>
                                        <th><small>Ação</small></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($perfis as $perfil)
        
                                        <tr>
                                            <td class="text-center font-weight-bold"><small>#{{ str_pad($perfil->id_perfil,3,'0',STR_PAD_LEFT) }}</small></td>
                                            <td><small>{{ $perfil->descricao }}</small></td>
                                            <td><small>{{ $perfil->sigla }}</small></td>
                                            <td><small>{{ ($perfil->situacao) ? 'Ativa' : 'Inativa' }}</small></td>
                                            <td><small>{{ Carbon\Carbon::parse($perfil->data_cria)->format('d/m/Y h:i:s') }}</small></td>
                                            <td><small>{{ Carbon\Carbon::parse($perfil->data_alt)->format('d/m/Y h:i:s') }}</small></td>
                                            <td class="d-flex bd-highlight">
                                                <div class="row">
                                                    <form method="POST" action="{{ route('admin.perfil.acesso.listar') }}" class="flex-fill">
                                                        @csrf
                                                        <input type="hidden" value="{{ $perfil->id_perfil }}" name="idPerfilBPMS">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalPerfil{{$perfil->id_perfil}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalPerfil" tabindex="-1" role="dialog" aria-labelledby="modalPerfilTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.perfil.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalPerfilTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de perfil</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sigla">Sigla:</label>
                            <input type="text" minlength="3" maxlength="10" class="form-control form-control-sm" id="sigla" name="sigla" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
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
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar perfil</button>
                </div>
            </form>
        </div>
    </div>


    @foreach ($perfis as $perfil)
    <div class="modal fade" id="modalPerfil{{$perfil->id_perfil}}" tabindex="-1" role="dialog" aria-labelledby="modalPerfilTitulo{{$perfil->id_perfil}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.perfil.editar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalPerfilTitulo{{$perfil->id_perfil}}"><i class="fas fa-pencil-alt"></i>Alteração de perfil</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="id_perfil" value="{{ $perfil->id_perfil }}">
                    <div class="row">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $perfil->descricao }}"required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sigla">Sigla:</label>
                            <input type="text" minlength="3" maxlength="10" class="form-control form-control-sm" id="sigla" name="sigla" value="{{ $perfil->sigla }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                @if($perfil->situacao)
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
                    <button type="submit" class="btn btn-sm btn-outline-light">Alterar perfil</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endsection