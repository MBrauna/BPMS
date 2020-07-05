@extends('layouts.bpms')

@section('titulo','Administração de perfis')

@section('corpo')
    <div class="row">
        <div class="col-12">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <small>Cadastro de processos de perfis</small>
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
                                        <th><small>processo</small></th>
                                        <th><small>Ação</small></th>
                                    </thead>
                                    <tbody>
                                        @foreach ($perfis as $perfil)
        
                                        <tr>
                                            <td class="text-center font-weight-bold"><small>#{{ str_pad($perfil->id_perfil_acesso,3,'0',STR_PAD_LEFT) }}</small></td>
                                            <td><small>{{ $perfil->id_processo }}</small></td>
                                            <td>
                                                <div>
                                                    <form method="POST" action="{{ route('admin.perfil.acesso.remover') }}" class="flex-fill">
                                                        @csrf
                                                        <input type="hidden" value="{{ $perfil->id_perfil_acesso }}" name="idPerfilBPMS">
                                                        <input type="hidden" value="{{ $perfil->id_perfil }}" name="id_perfil">
                                                        <button type="submit" class="btn btn-sm btn-danger btn-block">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
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
            <form class="modal-content shadow" method="POST" action="{{ route('admin.perfil.acesso.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalPerfilTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de perfil</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id_perfil" value="{{ $idPerfil }}">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="id_processo">Processo:</label>
                            <select class="form-control form-control-sm" id="id_processo" name="id_processo" required>
                                @foreach($processos as $processo)
                                    <option value="{{ $processo->id_processo }}">{{ $processo->descricao }}</option>
                                @endforeach
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

@endsection