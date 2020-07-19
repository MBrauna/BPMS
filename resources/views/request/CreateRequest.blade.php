@extends('layouts.bpms')

@section('titulo','Abertura de Solicitação')

@section('corpo')
    <div class="container-fluir">
        <abertura-bpms></abertura-bpms>
    </div>
@endsection



@section('scripts')
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init()
        })
    </script>
@endsection