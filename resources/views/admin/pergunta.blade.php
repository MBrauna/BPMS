@extends('layouts.bpms')

@section('titulo','Administração de perguntas')

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
                    <small>Formulário para o fluxo {{ $tipo->descricao }}</small>
                    <button type="button" class="btn btn-sm text-center btn-outline-light" data-toggle="modal" data-target="#modalQuestao">
                        <i class="fas fa-plus-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(count($perguntas) <= 0)
                    <div class="col-12 col-sm-12 col-md-12 d-flex justify-content-center">
                        <div class="col-sm-12 col-6 col-md-4 text-center">
                            <h5 class="font-weight-bold">Nenhuma pergunta cadastrada!</h5>
                            <h3 class="text-danger"><i class="fas fa-sad-tear"></i></h3>
                        </div>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-primary text-white">
                                <th><small>ID</small></th>
                                <th><small>Campo do formulário</small></th>
                                <th><small>Tipo</small></th>
                                <th><small>Ordem</small></th>
                                <th><small>Altera vencimento</small></th>
                                <th><small>Situação</small></th>
                                <th><small>Ação</small></th>
                            </thead>
                            <tbody>
                                @foreach ($perguntas as $pergunta)

                                <tr>
                                    <td class="text-center font-weight-bold"><small>#{{ str_pad($pergunta->id_pergunta_tipo,4,'0',STR_PAD_LEFT) }}</small></td>
                                    <td><small>{{ $pergunta->descricao }}</small></td>
                                    <td><small>{{ $pergunta->tipo }}</small></td>
                                    <td><small>{{ $pergunta->ordem }}</small></td>
                                    <td><small>{{ ($pergunta->alt_data_vencimento) ? 'Sim' : 'Não' }}</small></td>
                                    <td><small>{{ ($pergunta->situacao) ? 'Ativo' : 'Inativo' }}</small></td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="row">
                                                <button class="btn btn-sm btn-warning flex-fill ml-1"  data-toggle="modal" data-target="#modalQuestao{{ $pergunta->id_pergunta_tipo }}">
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


    <div class="modal fade" id="modalQuestao" tabindex="-1" role="dialog" aria-labelledby="modalQuestaoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.questao.cadastrar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalQuestaoTitulo"><i class="fas fa-pencil-alt"></i> Cadastro de tipo de processo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $tipo->id_tipo_processo }}" name="id_tipo_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Descrição:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="tipo">Tipo:</label>
                            <select class="form-control form-control-sm" id="tipo" name="tipo" required>
                                <option value="" selected>Nenhum tipo cadastrado</option>
                                <option value="number">Numérico</option>
                                <option value="text">Frase</option>
                                <option value="longtext">Texto</option>
                                <option value="datetime">Data e Hora</option>
                                <option value="datetime">Data</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="ordem">Ordem:</label>
                            <input type="number" min="0" max="9999999" class="form-control form-control-sm" id="ordem" name="ordem" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="alt_data_vencimento">Alterar data de vencimento:</label>
                            <select class="form-control form-control-sm" id="alt_data_vencimento" name="alt_data_vencimento" required>
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
                    <button type="submit" class="btn btn-sm btn-outline-light">Cadastrar Tipo</button>
                </div>
            </form>
        </div>
    </div>

    @foreach($perguntas as $pergunta)
    <div class="modal fade" id="modalQuestao{{ $pergunta->id_pergunta_tipo }}" tabindex="-1" role="dialog" aria-labelledby="modalQuestaoTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content shadow" method="POST" action="{{ route('admin.questao.editar') }}" autocomplete="off">
                <div class="modal-header bg-primary text-center text-white">
                    <h5 class="modal-title" id="modalQuestaoTitulo"><i class="fas fa-pencil-alt"></i>Formulário para o fluxo</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <input type="hidden" value="{{ $pergunta->id_pergunta_tipo }}" name="id_pergunta_tipo">
                        <input type="hidden" value="{{ $pergunta->id_tipo_processo }}" name="id_tipo_processo">
                        <div class="form-group col-sm-12 col-12 col-md-12">
                            <label for="descricao">Descrição:</label>
                            <input type="text" minlength="5" maxlength="350" class="form-control form-control-sm" id="descricao" name="descricao" value="{{ $pergunta->descricao }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="tipo">Tipo:</label>
                            <select class="form-control form-control-sm" id="tipo" name="tipo" required>
                                <option value="" selected>Nenhum tipo cadastrado</option>
                                <option value="number">Numérico</option>
                                <option value="text">Frase</option>
                                <option value="longtext">Texto</option>
                                <option value="datetime">Data e Hora</option>
                                <option value="datetime">Data</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="ordem">Ordem:</label>
                            <input type="number" min="0" max="9999999" class="form-control form-control-sm" id="ordem" name="ordem" value="{{ $pergunta->ordem }}" required>
                        </div>
                        <div class="form-group col-sm-12 col-6 col-md-6">
                            <label for="alt_data_vencimento">Alterar data de vencimento:</label>
                            <select class="form-control form-control-sm" id="alt_data_vencimento" name="alt_data_vencimento" required>
                                @if($pergunta->alt_data_vencimento)
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
                                @if($pergunta->situacao)
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