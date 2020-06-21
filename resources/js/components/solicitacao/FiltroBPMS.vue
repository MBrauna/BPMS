<template>
    <div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="accordion" id="filtroDataBPMS">
                    <div class="card shadow-sm border-primary">
                        <div class="btn btn-primary btn-block btn-sm" id="filtroData" data-toggle="collapse" data-target="#filtroBPMS" aria-expanded="true" aria-controls="filtroBPMS">
                            <span class="font-weight-bold">Filtros gerais</span>
                        </div>
                        <div id="filtroBPMS" class="collapse" aria-labelledby="filtroData" data-parent="#filtroDataBPMS">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                            </div>
                                            <input type="number" min="0" max="99999999" name="filtroID" class="form-control form-control-sm" id="filtroID" placeholder="ID">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-10 col-md-10 col-lg-10">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-search"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="filtroTitulo" class="form-control form-control-sm" id="filtroTitulo" placeholder="Consulta Título">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        123
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        123
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-success btn-block btn-sm">
                                    <span class="font-weight-bold">Aplicar filtro</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
