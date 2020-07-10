@extends('layouts.bpms')
 
@section('titulo','Administração de acessos')

@section('corpo')
    <div class="row">
        <div class="col-12 mb-1">
            <a href="{{ route('admin.usuario.listar') }}" class="btn btn-block btn-primary btn-sm">
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
                    <small>Cadastro de permissões ao usuário {{ $usuario->name }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalAcesso">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($acessos) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhum perfil atribuído</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Permissão</small></th>
                                <th><small>Superior</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($acessos as $acesso)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($acesso->id_perfil_usuario,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ consulta_perfil($acesso->id_perfil)->descricao }}</small></td>
                                    <td><small>{{ is_null($acesso->id_superior) ? 'Nenhum superior cadastrado' : consulta_usuario($acesso->id_superior)->name }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <form method="POST" action="{{ route('admin.usuario.perfil.remover') }}" class="flex-fill ml-1">
                                                    @csrf
                                                    <input type="hidden" value="{{ $acesso->id_perfil_usuario }}" name="id_perfil_usuario">
                                                    <input type="hidden" value="{{ $acesso->id_usuario }}" name="id_usuario">
                                                    <button type="submit" class="btn btn-sm btn-danger btn-block" data-toggle="tooltip" data-placement="left" title="Remover fluxo">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
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


    <div class="modal fade" id="modalAcesso" tabindex="-1" role="dialog" aria-labelledby="modalAcessoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.usuario.perfil.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalAcessoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de tipo de processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $usuario->id }}" name="id_usuario">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_perfil">Perfil:</label>
                            <select class="form-control form-control-sm" id="id_perfil" name="id_perfil" required>
                                @foreach($perfis as $perfil)
                                    <option value="{{ $perfil->id_perfil }}">{{ $perfil->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_superior">Superior:</label>
                            <select class="form-control form-control-sm" id="id_superior" name="id_superior">
                                <option value="" selected>Nenhum superior</option>
                                @foreach($usuarios as $useres)
                                    <option value="{{ $useres->id }}">{{ $useres->name }}</option>
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