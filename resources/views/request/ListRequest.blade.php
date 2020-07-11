@extends('layouts.bpms')

@section('titulo','Solicitação de Serviço')

@section('corpo')
    <div class="container-fluid">
        <div class="col-12 mb-2">
            <a href="{{ route('request.create') }}" class="btn btn-primary btn-block btn-sm">
                <i class="fas fa-pencil-alt"></i> Abrir solicitação de serviço
            </a>
        </div>
        <filtro-bpms></filtro-bpms>
        <lista-solicitacao></lista-solicitacao>
    </div>
@endsection