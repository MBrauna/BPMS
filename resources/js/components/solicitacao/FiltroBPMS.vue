<template>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="accordion" id="filtroDataBPMS">
                    <div class="card shadow-sm border-primary">
                        <div class="btn btn-primary btn-block btn-sm" id="filtroData" data-toggle="collapse" data-target="#filtroBPMS" aria-expanded="true" aria-controls="filtroBPMS">
                            <span class="font-weight-bold"><i class="fas fa-search"></i> Filtros gerais</span>
                        </div>
                        <div id="filtroBPMS" class="collapse" aria-labelledby="filtroData" data-parent="#filtroDataBPMS">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroID">Código</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-hashtag"></i>
                                                </div>
                                            </div>
                                            <input type="number" min="0" max="99999" name="filtroID" class="form-control form-control-sm" id="filtroID" placeholder="ID" v-model="filtroConteudo.codigo" v-on:change="alteraData()">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroTitulo">Título</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-primary text-white">
                                                    <i class="fas fa-keyboard"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="filtroTitulo" class="form-control form-control-sm" id="filtroTitulo" placeholder="Consulta Título"  v-model="filtroConteudo.titulo" v-on:change="alteraData()">
                                        </div>
                                    </div>
                                    <div v-if="listaEmpresa.length > 0" class="form-group col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroEmpresa">Empresa</label>
                                        <select id="filtroEmpresa" name="filtroEmpresa" class="form-control form-control-sm" v-model="filtroConteudo.empresa" v-on:change="alteraData()">
                                            <option>Todas empresas</option>
                                            <option v-for="conteudo in listaEmpresa" v-bind:key="conteudo.id_empresa" v-bind:value="conteudo.id_empresa">{{ conteudo.descricao }}</option>
                                        </select>
                                    </div>
                                    <div v-if="listaProcesso.length > 0" class="form-group col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroProcesso">Processo:</label>
                                        <select id="filtroProcesso" name="filtroProcesso" class="form-control form-control-sm"  v-model="filtroConteudo.processo" v-on:change="alteraData()">
                                            <option selected>Todos processos</option>
                                            <option v-for="conteudo in listaProcesso" v-bind:key="conteudo.id_processo" v-bind:value="conteudo.id_processo">{{ conteudo.descricao }}</option>
                                        </select>
                                    </div>
                                    <div v-if="listaTipo.length > 0" class="form-group col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroTipo">Tipo</label>
                                        <select id="filtroTipo" name="filtroTipo" class="form-control form-control-sm" v-model="filtroConteudo.tipo" v-on:change="alteraData()">
                                            <option selected>Todos tipos</option>
                                            <option v-for="conteudo in listaTipo" v-bind:key="conteudo.id_tipo_processo" v-bind:value="conteudo.id_tipo_processo">{{ conteudo.descricao }}</option>
                                        </select>
                                    </div>
                                    <div v-if="listaSituacao.length > 0" class="form-group col-12 col-sm-12 col-md-6 col-lg-6">
                                        <label for="filtroSituação">Situação</label>
                                        <select id="filtroSituacao" name="filtroSituacao" class="form-control form-control-sm" v-model="filtroConteudo.situacao" v-on:change="alteraData()">
                                            <option selected>Todas situações</option>
                                            <option v-for="conteudo in listaSituacao" v-bind:key="conteudo.id_situacao" v-bind:value="conteudo.id_situacao">{{ conteudo.descricao }}</option>
                                        </select>
                                    </div>
                                </div>
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
        data: function(){
            return {
                filtroConteudo:{},
                listaEmpresa:{},
                listaProcesso:{},
                listaTipo:{},
                listaSituacao:{},
            }
        },
        methods: {
            preencheCampos  :   function() {
                var vm              =   this;
                vm.filtroConteudo   =   vm.BPMS.coletaFiltro();
            }, // preencheCampos: function() { ... }
            coletaFiltros   :   function(){
                var vm              =   this;
                var idUsuario       =   document.getElementById("idUsuarioBPMS").value;

                var vRequisicao     =   {
                    'idUsuario' :   idUsuario,
                };

                axios.post('/api/util/filtro',vRequisicao)
                .then(function (response) {
                    if(response.status === 200) {
                        vm.listaEmpresa     =   response.data.empresa;
                        vm.listaProcesso    =   response.data.processo;
                        vm.listaTipo        =   response.data.tipo;
                        vm.listaSituacao    =   response.data.situacao;
                    }
                    else {
                        vm.$bvToast.toast(
                            (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Erro ao obter filtros! Verifique.',
                            {
                                title: 'Mensagem BPMS',
                                autoHideDelay: 5000,
                                appendToast: true,
                                solid: true,
                                variant: 'warning',
                            }
                        );
                    }
                })
                .catch(function(response){
                    vm.$bvToast.toast(
                        (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Erro ao obter filtros! Verifique.',
                        {
                            title: 'Mensagem BPMS',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'danger',
                        }
                    );
                });
            }, // coletaFiltros   :   function(){ ... }
            alteraData      :   function(){
                var vm = this;
                try {
                    if(vm.filtroConteudo.codigo === null) {
                        vm.filtroConteudo.codigo = null;
                        sessionStorage.removeItem('filtroCodigo');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroCodigo',vm.filtroConteudo.codigo);
                    }

                    if(vm.filtroConteudo.titulo === null) {
                        vm.filtroConteudo.titulo = null;
                        sessionStorage.removeItem('filtroTitulo');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroTitulo',vm.filtroConteudo.titulo);
                    }

                    if(vm.filtroConteudo.empresa === null) {
                        vm.filtroConteudo.empresa = null;
                        sessionStorage.removeItem('filtroEmpresa');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroEmpresa',vm.filtroConteudo.empresa);
                    }

                    if(vm.filtroConteudo.processo === null) {
                        vm.filtroConteudo.processo = null;
                        sessionStorage.removeItem('filtroProcesso');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroProcesso',vm.filtroConteudo.processo);
                    }

                    if(vm.filtroConteudo.tipo === null) {
                        vm.filtroConteudo.tipo = null;
                        sessionStorage.removeItem('filtroTipo');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroTipo',vm.filtroConteudo.tipo);
                    }

                    if(vm.filtroConteudo.situacao === null) {
                        vm.filtroConteudo.situacao = null;
                        sessionStorage.removeItem('filtroSituacao');
                    } // if(filtroConteudo.codigo === null || filtroConteudo.codigo.toString().trim() === '') { ... }
                    else {
                        sessionStorage.setItem('filtroSituacao',vm.filtroConteudo.situacao);
                    }

                    vm.preencheCampos();
                } // try { ... }
                catch(erro) {
                    console.log(erro);
                } // catch(erro) { ... }
            }, // alteraData     :   function(item, event){ ... }
        },
        mounted() {
            this.preencheCampos();
            this.coletaFiltros();
        }
    }
</script>
