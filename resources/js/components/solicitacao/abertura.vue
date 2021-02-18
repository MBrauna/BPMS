<template>
    <div>
        <!-- Conteúdo de processos -->
        <div class="row" v-if="!iniciarAbertura">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-center text-white">
                        Abertura de solicitação de serviço
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <h6 v-if="listaProcesso.length > 0" class="col-12 text-primary text-center">Selecione o processo desejado:</h6>
                            <div v-if="listaProcesso.length > 0" class="col-12 mb-4">
                                <div class="card">
                                    <ul class="list-group">
                                        <li class="list-group-item" :class="{ active : processoEscolhido === conteudo.id_processo }" v-on:click="coletaTipo(conteudo)" v-for="conteudo in listaProcesso" v-bind:key="conteudo.id_processo">
                                            <i v-bind:class="conteudo.icone"></i>
                                            <span>{{ conteudo.descricao }} - [{{ conteudo.sigla}}] - <small>[{{ conteudo.nome_responsavel}}]</small></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <h6 v-if="listaTipoProcesso.length > 0" class="col-12 text-primary text-center">Qual tipo de processo deseja iniciar?</h6>
                            <div v-if="listaTipoProcesso.length > 0" class="col-12 mb-4">
                                <div class="card">
                                    <ul class="list-group">
                                        <li class="list-group-item" :class="{ active : tipoProcessoEscolhido === conteudo.id_tipo_processo }" v-on:click="tipoProcessoEscolhido = conteudo.id_tipo_processo" v-for="conteudo in listaTipoProcesso" v-bind:key="conteudo.id_tipo_processo">
                                            <div class="row">
                                                <div class="col-12">
                                                    <H5 class="text-center text-wrap font-weight-bolder">{{ conteudo.questao }}</H5>
                                                </div>
                                                <div class="col-12 d-flex justify-content-between align-items-center">
                                                    <small :class="(tipoProcessoEscolhido === conteudo.id_tipo_processo) ? 'text-white' : 'text-primary'">SLA ~{{ conteudo.sla }} minuto(s)</small>
                                                    <small :class="(tipoProcessoEscolhido === conteudo.id_tipo_processo) ? 'text-white' : 'text-primary'">{{ conteudo.descricao }}</small>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-if="processoEscolhido != null && tipoProcessoEscolhido != null">
                        <button class="btn btn-sm btn-primary btn-block" v-on:click="marcaProximo">
                            Seguinte
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="iniciarAbertura">
            <div class="col-12">
                <form class="card was-validated" method="POST" action="/abertura" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="_token" v-model="metaCSRF">
                    <div class="card-header bg-primary text-center text-white">
                        Abertura de solicitação de serviço
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="tituloBPMS">Titulo</label>
                                <input type="text" minlength="25" maxlength="320" class="form-control form-control-sm" id="tituloBPMS" name="tituloBPMS" placeholder="Informe o título da solicitação de serviço de forma direta" v-model="titulo" @change="alterarTitulo()" required>
                            </div>

                            <input type="hidden" name="idEmpresaBPMS" v-model="finalData.idEmpresa">
                            <div class="form-group col-12">
                                <label for="empresaBPMS">Empresa</label>
                                <input type="text" class="form-control form-control-sm" id="empresaBPMS" name="empresaBPMS" v-model="finalData.nomeEmpresa" readonly>
                            </div>

                            <input type="hidden" name="idProcessoBPMS" v-model="finalData.idProcesso">
                            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="processoBPMS">Processo</label>
                                <input type="text" class="form-control form-control-sm" id="processoBPMS" name="processoBPMS" v-model="finalData.nomeProcesso" readonly>
                            </div>

                            <input type="hidden" name="idTipoBPMS" v-model="finalData.idTipo">
                            <div class="form-group col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <label for="tipoBPMS">Tipo</label>
                                <input type="text" class="form-control form-control-sm" id="tipoBPMS" name="tipoBPMS" v-model="finalData.nomeTipo" readonly>
                            </div>

                            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" v-for="conteudo in listaQuestoes" v-bind:key="conteudo.id_pergunta_tipo">
                                <label v-bind:for="'questao_' + conteudo.id_pergunta_tipo">{{ conteudo.descricao }}</label>
                                <input v-if="((conteudo.tipo !== 'datetime') && (conteudo.tipo !== 'date') && (conteudo.tipo !== 'longtext'))" v-bind:type="conteudo.tipo" minlength="1" maxlength="320" class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" v-model="questaoData[conteudo.id_pergunta_tipo]" required>
                                <input v-if="conteudo.tipo === 'date'" v-bind:min="menorHora" v-bind:type="conteudo.tipo"  class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" v-model="questaoData[conteudo.id_pergunta_tipo]" required>
                                <div class="col-12" v-if="conteudo.tipo === 'datetime'">
                                    <div class="row">
                                        <input type="date" v-bind:min="menorHora" class="form-control form-control-sm col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-bind:id="'questao_' + conteudo.id_pergunta_tipo + '_data'" v-bind:name="'questao_' + conteudo.id_pergunta_tipo + '_data'" v-model="questaoData[conteudo.id_pergunta_tipo+'_data']" required>
                                        <input type="time" class="form-control form-control-sm col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-bind:id="'questao_' + conteudo.id_pergunta_tipo + '_hora'" v-bind:name="'questao_' + conteudo.id_pergunta_tipo + '_hora'" v-model="questaoData[conteudo.id_pergunta_tipo+'_hora']" required>
                                    </div>
                                </div>
                                <textarea v-if="conteudo.tipo === 'longtext'" minlength="1" class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" v-model="questaoData[conteudo.id_pergunta_tipo]" required></textarea>
                                <select v-if="conteudo.tipo === 'user'" class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" v-model="questaoData[conteudo.id_pergunta_tipo]" required>
                                    <option v-bind:value="null">Nenhum usuário selecionado</option>
                                    <option v-for="curreg in usersdata" v-bind:key="curreg.id" v-bind:value="curreg.id">{{ curreg.name }}</option>
                                </select>
                            </div>

                            <div class="form-group col-12 border-primary">
                                <label class="col-12 control-label">
                                    Adicione os arquivos desejados: (Máx 2MB)
                                </label>
                                <div class="col-12">
                                    <b-form-file
                                            name="arquivoBPMS[]"
                                            multiple
                                            v-model="file"
                                            size="sm"
                                            :state="Boolean(file)"
                                            placeholder="Selecione o(s) arquivo(s) ..."
                                            drop-placeholder="Solte seu(s) arquivo(s) aqui ..."
                                    ></b-form-file>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="finalizar" class="card-footer">
                        <button type="submit" class="btn btn-success btn-block">Finalizar</button>
                    </div>
                </form>
            </div>
            <div class="col-12 mt-4">
                <button class="btn btn-sm btn-primary btn-block" v-on:click="marcaVolta">
                    retornar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['usersdata'],
        data: function() {
            return {
                metaCSRF: document.querySelector('meta[name="csrf-token"]').content,
                empresaEscolhido: null,
                processoEscolhido: null,
                tipoProcessoEscolhido: null,
                iniciarAbertura: false,
                finalizar: false,
                file: null,
                titulo: '',

                listaEmpresa:{},
                listaProcesso:{},
                listaTipoProcesso:{},
                listaQuestoes:{},
                menorHora: null,
                finalData:{},
                questaoData:{},
            }
        },
        methods: {
            alterarTitulo   :   function(){
                var vm                  =   this;
                try {
                    vm.titulo   =   vm.titulo.trim();
                } // try { ... }
                catch(error) {
                    vm.titulo   =   vm.titulo.substring(' ','');
                } // catch(error) { ... }
            },
            coletaProcesso: function(){
                var vm                  =   this;
                var idUsuario           =   document.getElementById("idUsuarioBPMS").value;
                vm.finalData.idUsuario  =   idUsuario;

                var vRequisicao     =   {
                    'idUsuario' :   idUsuario,
                };

                axios.post('/api/util/filtro',vRequisicao)
                .then(function (response) {
                    if(response.status === 200) {
                        vm.listaProcesso    =   response.data.processo;
                        vm.listaEmpresa     =   response.data.empresa;
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
            coletaTipo: function(conteudo){
                var vm                      =   this;
                var idUsuario               =   document.getElementById("idUsuarioBPMS").value;

                vm.processoEscolhido        =   conteudo.id_processo
                vm.tipoProcessoEscolhido    =   null;
                vm.listaTipoProcesso        =   {};
                vm.iniciarAbertura          =   false;

                var vRequisicao     =   {
                    'idUsuario' :   idUsuario,
                    'idProcesso':   vm.processoEscolhido,
                };

                try {
                    axios.post('/api/util/tipo',vRequisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.listaTipoProcesso    =   response.data.tipo;
                        }
                        else {
                            vm.$bvToast.toast(
                                (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Erro ao obter dados do tipo de processo! Verifique.',
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
                    .catch(function(retorno){
                        vm.$bvToast.toast(
                            (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Não foi possível obter os processo! Verifique.',
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
                catch(response) {
                    vm.$bvToast.toast(
                        'Não foi possível obter os processo! Verifique.',
                        {
                            title: 'Mensagem BPMS',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'warning',
                        }
                    );
                }
            },
            coletaQuestao: function(){
                var vm                      =   this;
                var idUsuario               =   document.getElementById("idUsuarioBPMS").value;

                var vRequisicao     =   {
                    'idUsuario' :   idUsuario,
                    'idProcesso':   vm.processoEscolhido,
                    'idTipo'    :   vm.tipoProcessoEscolhido,
                };

                try {
                    axios.post('/api/util/questao',vRequisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.listaQuestoes    =   response.data.questao;
                            vm.menorHora        =   response.data.menorHora;
                            vm.finalizar        =   true;
                        }
                        else {
                            vm.$bvToast.toast(
                                (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Não foi possível obter as questões! Verifique.',
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
                    .catch(function(retorno){
                        vm.$bvToast.toast(
                            (retorno.data.erro.mensagem) ? retorno.data.erro.mensagem : 'Erro ao consultar os dados, informe ao administrador do sistema! Verifique.',
                            {
                                title: 'Mensagem BPMS',
                                autoHideDelay: 5000,
                                appendToast: true,
                                solid: true,
                                variant: 'danger',
                            }
                        );
                    });
                }
                catch(erro) {
                    vm.$bvToast.toast(
                        'Erro ao consultar os dados, informe ao administrador do sistema! Verifique.',
                        {
                            title: 'Mensagem BPMS',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'danger',
                        }
                    );
                }
            },
            marcaProximo : function(){
                var vm = this;
                if(vm.processoEscolhido === null || vm.tipoProcessoEscolhido === null) {
                    vm.iniciarAbertura = false;
                }
                else {
                    vm.listaQuestoes        =   {};
                    vm.finalData            =   {};

                    vm.listaProcesso.forEach(element => {
                        if(element.id_processo === vm.processoEscolhido) {
                            vm.finalData.idProcesso     =   element.id_processo;
                            vm.finalData.nomeProcesso   =   element.descricao;
                            vm.finalData.idEmpresa      =   element.id_empresa;
                        } // if(element.id_processo = processoEscolhido) { .. }
                    });

                    vm.listaEmpresa.forEach(element => {
                        if(element.id_empresa === vm.finalData.idEmpresa) {
                            vm.finalData.nomeEmpresa    =   element.descricao;
                        } // if(element.id_empresa === vm.finalData.idEmpresa) { ... }
                    }); // vm.listaEmpresa.forEach(element => { ... }

                    vm.listaTipoProcesso.forEach(element => {
                        if(element.id_tipo_processo === vm.tipoProcessoEscolhido) {
                            vm.finalData.idTipo     =   element.id_tipo_processo;
                            vm.finalData.nomeTipo   =   element.descricao;
                        } // if(element.id_tipo_processo === vm.tipoProcessoEscolhido) { ... }
                    }); // vm.listaTipoProcesso.forEach(element => { ... }

                    vm.iniciarAbertura      = true;
                    vm.coletaQuestao();
                }
            },
            marcaVolta : function(){
                var vm = this;
                vm.iniciarAbertura          =   false;
                vm.processoEscolhido        =   null;
                vm.tipoProcessoEscolhido    =   null;
                vm.listaTipoProcesso        =   {};
                vm.listaQuestoes            =   {};
                vm.finalData                =   {};
                vm.finalizar                =   false;
            },
        },
        mounted() {
            this.coletaProcesso();
        },
    }
</script>