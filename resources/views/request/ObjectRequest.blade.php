@extends('layouts.bpms')

@section('titulo','Troca de objetos')

@section('corpo')
    <troca-objeto id="{{ Auth::user()->id }}" url="{{ route('object.entry') }}"></troca-objeto>
@endsection
