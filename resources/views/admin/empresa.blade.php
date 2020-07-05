@extends('layouts.bpms')

@section('titulo','Administração de empresas')

@section('corpo')
    <div class="row">
        <div class="col-12">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <small>Cadastro de empresas</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalEmpresa">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @isset($empresas)
                        @if(count($empresas) <= 0)
                        <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                            <div class="col-sm-12 col-6 col-md-4 text-center">
                                <h5 class="font-weight-bold">Nenhuma empresa cadastrada</h5>
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
                                        @foreach ($empresas as $empresa)
        
                                        <tr>
                                            <td class="text-center font-weight-bold"><small>#{{ str_pad($empresa->id_empresa,3,'0',STR_PAD_LEFT) }}</small></td>
                                            <td><small>{{ $empresa->descricao }}</small></td>
                                            <td><small>{{ $empresa->sigla }}</small></td>
                                            <td><small>{{ ($empresa->situacao) ? 'Empresa Ativa' : 'Empresa inativa' }}</small></td>
                                            <td><small>{{ Carbon\Carbon::parse($empresa->data_cria)->format('d/m/Y h:i:s') }}</small></td>
                                            <td><small>{{ Carbon\Carbon::parse($empresa->data_alt)->format('d/m/Y h:i:s') }}</small></td>
                                            <td class="d-flex bd-highlight">
                                                <div class="row">
                                                    <form method="POST" action="{{ route('admin.processo.listar') }}" class="flex-fill">
                                                        @csrf
                                                        <input type="hidden" value="{{ $empresa->id_empresa }}" name="idEmpresaBPMS">
                                                        <button type="submit" class="btn btn-sm btn-success btn-block">
                                                            <i class="far fa-eye"></i>
                                                        </button>
                                                    </form>
                                                    <button class="btn btn-sm btn-warning flex-fill ml-1" data-toggle="modal" data-target="#modalEmpresa{{ $empresa->id_empresa }}">
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


    <div class="modal fade" id="modalEmpresa" tabindex="-1" role="dialog" aria-labelledby="modalEmpresaTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.empresa.inserir') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalEmpresaTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de empresas</h5>
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
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar empresa</button>
                </div>
            </form>
        </div>
    </div>


    @foreach($empresas as $empresa)
    <div class="modal fade" id="modalEmpresa{{$empresa->id_empresa}}" tabindex="-1" role="dialog" aria-labelledby="modalEmpresaTitulo{{$empresa->id_empresa}}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.empresa.editar') }}">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalEmpresaTitulo{{$empresa->id_empresa}}"><i class="fas fa-pencil-alt"></i> Cadastro de empresas</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" value="{{ $empresa->id_empresa }}" name="id_empresa">
                    <div class="row">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $empresa->descricao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="sigla">Sigla:</label>
                            <input type="text" minlength="3" maxlength="10" class="form-control form-control-sm" id="sigla" name="sigla" value="{{ $empresa->sigla }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                @if($empresa->situacao)
                                <option value="1" selected>Ativo</option>
                                <option value="0">Inativo</option>
                                @else
                                <option value="1">Ativo</option>
                                <option value="0" selected>Inativo</option>
                                @endif;

                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-primary">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-sm btn-success">Editar empresa</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
@endsection