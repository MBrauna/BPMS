@extends('layouts.bpms')

@section('titulo','Desempenho')

@section('corpo')
    <div class="row">
        @foreach($empresas as $empresa)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-center text-white border-primary">
                        <small class="fonte-weight-bold"><i class="fas fa-chart-pie"></i> {{ $empresa->sigla}}</small> - {{ $empresa->descricao }}
                    </div>
                    <ul class="list-group list-group-flush login-card border-primary">
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6" style="height: 20em">
                                    <canvas id="{{ $empresa->id_empresa }}_grafico1"></canvas>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6" style="height: 20em">
                                    <canvas id="{{ $empresa->id_empresa }}_grafico2"></canvas>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6" style="height: 20em">
                                    <canvas id="{{ $empresa->id_empresa }}_grafico3"></canvas>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6" style="height: 20em">
                                    <canvas id="{{ $empresa->id_empresa }}_grafico4"></canvas>
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12 col-sm-12 col-md-6">
                                    <canvas id="{{ $empresa->id_empresa }}_grafico5"></canvas>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('script_principal')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection

@section('scripts')
    @foreach($empresas as $empresa)
        <script>
            new Chart(document.getElementById("{{ $empresa->id_empresa }}_grafico1"),{!! consulta_grafico($empresa->id_empresa, 1) !!});
        </script>
        <script>
            new Chart(document.getElementById("{{ $empresa->id_empresa }}_grafico2"),{!! consulta_grafico($empresa->id_empresa, 2) !!});
        </script>
        <script>
            new Chart(document.getElementById("{{ $empresa->id_empresa }}_grafico3"),{!! consulta_grafico($empresa->id_empresa, 3) !!});
        </script>
        <script>
            new Chart(document.getElementById("{{ $empresa->id_empresa }}_grafico4"),{!! consulta_grafico($empresa->id_empresa, 4) !!});
        </script>
        <script>
            new Chart(document.getElementById("{{ $empresa->id_empresa }}_grafico5"),{!! consulta_grafico($empresa->id_empresa, 5) !!});
        </script>

    @endforeach
@endsection

