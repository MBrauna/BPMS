<template>
    <div>
        <div class="row">
            <div class="col-12" v-if="carregamento">
                <h3 class="text-center text-primary">
                    <i class="fas fa-spinner fa-spin"></i><br/>
                    <small>Carregando, aguarde!</small>
                </h3>
            </div>
            <div v-else class="col-12">
                <div class="card border-primary" v-if="etapa === 0">
                    <div class="card-header text-center text-white bg-primary">
                        Escolha o processo de atuação
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo:</label>
                                    <select class="form-control form-control-sm" id="tipo" name="tipo" v-model="tipo" required>
                                        <option value="1" selected>Entrada</option>
                                        <option value="2">Saída</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="idProcesso">Processo:</label>
                                    <select class="form-control form-control-sm" id="idProcesso" name="idProcesso" @change="coletaTipoProcesso($event)" v-model="processo" required>
                                        <option value="" selected>Nenhum processo selecionado</option>
                                        <option v-for="conteudo in listaProcessos" v-bind:key="conteudo.id_processo" v-bind:value="conteudo.id_processo"> {{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="idTipoProcesso">Sub processo:</label>
                                    <select class="form-control form-control-sm" id="idTipoProcesso" name="idTipoProcesso" v-model="tipoProcesso" required>
                                        <option v-for="conteudoProc in listaTipoProcesso" v-bind:key="conteudoProc.id_tipo_processo" v-bind:value="conteudoProc.id_tipo_processo">[{{ conteudoProc.descricao }}] - {{conteudoProc.questao}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="idResponsavel">Responsável:</label>
                                    <select class="form-control form-control-sm" id="idResponsavel" name="idResponsavel" v-model="responsavel" required>
                                        <option v-for="conteudoProc in listaResponsavel" v-bind:key="conteudoProc.id" v-bind:value="conteudoProc.id">{{ conteudoProc.name }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm btn-block" @click="iniciarAutomacao()">Iniciar automação</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id'],
        data() {
            return {
                processo: "",
                tipoProcesso: "",
                responsavel: "",
                tipo: 1,
                carregamento: true,

                listaProcessos: {},
                listaTipoProcesso: {},
                listaResponsavel: {},
                etapa: 0,
            }
        },
        methods: {
            iniciarAutomacao : function() {
                var vm = this;

                if(vm.processo == "" || vm.processo == null) {
                    vm.$bvToast.toast(
                        'Informe qual processo será utilizado na troca de objetos.',
                        {
                            title: 'Campo obrigatório',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'warning',
                        }
                    );
                    document.getElementById('idProcesso').focus();
                    return;
                } // if(vm.processo == "" || vm.processo == null) { ... }

                if(vm.tipoProcesso == "" || vm.tipoProcesso == null) {
                    vm.$bvToast.toast(
                        'Informe qual tipo de processo será utilizado na troca de objetos.',
                        {
                            title: 'Campo obrigatório',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'warning',
                        }
                    );
                    document.getElementById('idTipoProcesso').focus();
                    return;
                }

                if(vm.responsavel == "" || vm.responsavel == null) {
                    vm.$bvToast.toast(
                        'Informe qual responsável será utilizado na troca de objetos.',
                        {
                            title: 'Campo obrigatório',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'warning',
                        }
                    );
                    document.getElementById('idResponsavel').focus();
                    return;
                }
 
                vm.etapa = vm.tipo;
            },
            coletaProcesso : function(){
                var vm = this;

                var vRequisicao     =   {
                    'idUsuario' :   document.getElementById("idUsuarioBPMS").value,
                };
                vm.carregamento = true;
                axios.post('/api/util/resp',vRequisicao)
                .then(function (response) {
                    if(response.status === 200) {
                        vm.carregamento     =   false;
                        vm.listaProcessos   =   response.data;
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
                            variant: 'warning',
                        }
                    );
                });
            },
            coletaTipoProcesso : function(evento){
                var vm = this;
                try {
                    var vRequisicao = {
                        idUsuario: document.getElementById("idUsuarioBPMS").value,
                        idProcesso: vm.processo,
                    };

                    axios.post('/api/util/tipo',vRequisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.carregamento     =   false;
                            vm.listaTipoProcesso=   response.data.tipo;
                            vm.listaResponsavel =   response.data.responsavel;
                        }
                        else {
                            vm.$bvToast.toast(
                                (typeof response.data.erro != "undefined") ? response.data.erro.mensagem : 'Erro ao obter o tipo de processo! Verifique.',
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
                                variant: 'warning',
                            }
                        );
                    });
                }
                catch(erro) {
                    vm.$bvToast.toast(
                        'Não foi possível obter os dados do tipo de processo! Verifique.',
                        {
                            title: 'Contate um administrador',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'warning',
                        }
                    );
                }
            },
        },
        mounted() {
            this.coletaProcesso();
        }
    }
</script>
