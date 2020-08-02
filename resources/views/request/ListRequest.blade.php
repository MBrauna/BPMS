@extends('layouts.bpms')

@section('titulo','Solicitação de Serviço')

@section('script_principal')
@endsection

@section('corpo')
    <div>
        <div class="mb-1">
            <a href="{{ route('request.create') }}" class="btn btn-primary btn-block btn-sm">
                <i class="fas fa-pencil-alt"></i> Abrir solicitação de serviço
            </a>
        </div>
        <filtro-bpms></filtro-bpms>
        <lista-solicitacao></lista-solicitacao>
    </div>
@endsection

@section('scripts')
@endsection