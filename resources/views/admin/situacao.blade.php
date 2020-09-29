@extends('layouts.bpms')

@section('titulo','Administração de situações')

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
                    <small>Eventos (situações) possíveis no processo {{ $processo->descricao }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalSituacao">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($situacoes) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhuma situação cadastrada!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Situação</small></th>
                                <th><small>Perfil que atua na solicitação</small></th>
                                <th><small>Tarefa retorna ao solicitante?</small></th>
                                <th><small>O perfil registra responsável?</small></th>
                                <th><small>Permite alterar vencimento?</small></th>
                                <th><small>A situação é conclusiva?</small></th>
                                <th><small>Ativo</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($situacoes as $situacao)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($situacao->id_situacao,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $situacao->descricao }}</small></td>
                                    <td><small>{{ is_null($situacao->id_perfil) ? 'Não atribuído' : consulta_perfil($situacao->id_perfil)->descricao }}</small></td>
                                    <td><small>{{ ($situacao->tarefa_solicitante) ? 'Sim' : 'Não' }}</small></td>
                                    <td><small>{{ ($situacao->marca_responsavel) ? 'Sim' : 'Não' }}</small></td>
                                    <td><small>{{ ($situacao->alterar_data_vencimento) ? 'Sim' : 'Não' }}</small></td>
                                    <td><small>{{ ($situacao->conclusiva) ? 'Sim' : 'Não' }}</small></td>
                                    <td><small>{{ ($situacao->situacao) ? 'Ativo' : 'Inativo' }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalSituacao{{ $situacao->id_situacao }}">
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


    <div class="modal fade" id="modalSituacao" tabindex="-1" role="dialog" aria-labelledby="modalSituacaoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.situacao.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalSituacaoTitulo"><i class="fas fa-pencil-alt"></i> Eventos (situações) possíveis no processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $processo->id_processo }}" name="id_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Descrição:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_perfil">Perfil:</label>
                            <select class="form-control form-control-sm" id="id_perfil" name="id_perfil">
                                <option value="" selected>Perfil não atribuído</option>
                                @foreach($perfis as $perfil)
                                    <option value="{{ $perfil->id_perfil }}">{{ $perfil->descricao }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="tarefa_solicitante">Tarefa ao solicitante:</label>
                            <select class="form-control form-control-sm" id="tarefa_solicitante" name="tarefa_solicitante" required>
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="marca_responsavel">Marca subordinado:</label>
                            <select class="form-control form-control-sm" id="marca_responsavel" name="marca_responsavel" required>
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="alterar_data_vencimento">Altera Data Vencimento:</label>
                            <select class="form-control form-control-sm" id="alterar_data_vencimento" name="alterar_data_vencimento" required>
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="conclusiva">Tarefa conclusiva:</label>
                            <select class="form-control form-control-sm" id="conclusiva" name="conclusiva" required>
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
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
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar situação</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($situacoes as $situacao)
    <div class="modal fade" id="modalSituacao{{ $situacao->id_situacao }}" tabindex="-1" role="dialog" aria-labelledby="modalSituacaoTitulo{{ $situacao->id_situacao }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.situacao.editar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalSituacaoTitulo{{ $situacao->id_situacao }}"><i class="fas fa-pencil-alt"></i> Alterar situação</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $processo->id_processo }}" name="id_processo">
                        <input type="hidden" value="{{ $situacao->id_situacao }}" name="id_situacao">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Nome:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $situacao->descricao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="id_perfil">Perfil:</label>
                            <select class="form-control form-control-sm" id="id_perfil" name="id_perfil">
                                @if(is_null($situacao->id_perfil))
                                    <option value="" selected>Perfil não atribuído</option>
                                @else
                                    <option value="">Perfil não atribuído</option>
                                @endif
                                @foreach($perfis as $perfil)
                                    @if($situacao->id_perfil == $perfil->id_perfil)
                                        <option value="{{ $perfil->id_perfil }}" selected>{{ $perfil->descricao }}</option>
                                    @else
                                        <option value="{{ $perfil->id_perfil }}">{{ $perfil->descricao }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="tarefa_solicitante">Tarefa ao solicitante:</label>
                            <select class="form-control form-control-sm" id="tarefa_solicitante" name="tarefa_solicitante" required>
                                @if($situacao->tarefa_solicitante)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="marca_responsavel">Marca subordinado:</label>
                            <select class="form-control form-control-sm" id="marca_responsavel" name="marca_responsavel" required>
                                @if($situacao->marca_responsavel)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="alterar_data_vencimento">Altera Data Vencimento:</label>
                            <select class="form-control form-control-sm" id="alterar_data_vencimento" name="alterar_data_vencimento" required>
                                @if($situacao->alterar_data_vencimento)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="conclusiva">Tarefa conclusiva:</label>
                            <select class="form-control form-control-sm" id="conclusiva" name="conclusiva" required>
                                @if($situacao->conclusiva)
                                    <option value="1" selected>Sim</option>
                                    <option value="0">Não</option>
                                @else
                                    <option value="1">Sim</option>
                                    <option value="0" selected>Não</option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="situacao">Situação:</label>
                            <select class="form-control form-control-sm" id="situacao" name="situacao" required>
                                @if($situacao->situacao)
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