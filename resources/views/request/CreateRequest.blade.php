@extends('layouts.bpms')

@section('titulo','Abertura de Solicitação')

@section('corpo')
    <div class="container-fluir">
        <abertura-bpms usersdata="{{ json_encode(consulta_subordinados_todos(Auth::user()->id, false)) }}"></abertura-bpms>
    </div>
@endsection



@section('scripts')
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init()
        })
    </script>
@endsection