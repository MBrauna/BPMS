@extends('layouts.bpms')

@section('titulo','Tarefa')

@section('corpo')
    
        <filtro-bpms></filtro-bpms>
        <lista-tarefa url="{{ route('task.edit') }}"></lista-tarefa>
@endsection