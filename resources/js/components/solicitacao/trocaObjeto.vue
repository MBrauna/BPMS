<template>
    <div>
        <div class="row pt-3">
            <div class="col-12">
                <form class="card border-primary was-validated" method="POST" v-bind:action="url" autocomplete="off" enctype="multipart/form-data">
                    <div class="card-header bg-primary text-center text-white">
                        <small>Cadastro de troca de objetos </small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <input type="hidden" name="_token" v-model="metaCSRF">
                            <!-- Primeira etapa - Seleção do tipo -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="idProcessoReferencia" class="text-success font-weight-bold">Processo de referência:</label>
                                    <select class="form-control form-control-sm" id="idProcessoReferencia" name="idProcessoReferencia" v-model="processoReferencia" required>
                                        <option value="">Nenhum processo de referência escolhido</option>
                                        <option v-for="conteudo in listaProcessoOrigem" v-bind:key="conteudo.id_processo" v-bind:value="conteudo.id_processo">[{{ conteudo.sigla_empresa }}] - {{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="tipoObjeto">Tipo:</label>
                                    <select class="form-control form-control-sm" id="idTipo" name="idTipo" v-model="opcao" @change="selectOption()" required>
                                        <option value="">Nenhum tipo escolhido</option>
                                        <option v-for="conteudo in listaOpcoes" v-bind:key="conteudo.id" v-bind:value="conteudo.id">{{ conteudo.description }}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Primeira etapa - Seleção do tipo -->

                            <!-- Segunda etapa - Processo alvo -->
                            <!-- Entrada -->
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4" v-if="opcaoEntrada">
                                <div class="form-group">
                                    <label for="idProcessoOrigem" class="text-success font-weight-bold">Processo de origem:</label>
                                    <select class="form-control form-control-sm" id="idProcessoOrigem" name="idProcessoOrigem" v-model="processoOrigem" @change="selectProcess(processoOrigem,null,1)" required>
                                        <option value="">Nenhum processo de origem escolhido</option>
                                        <option v-for="conteudo in listaProcessoOrigem" v-bind:key="conteudo.id_processo" v-bind:value="conteudo.id_processo">[{{ conteudo.sigla_empresa }}] - {{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4" v-if="opcaoEntrada">
                                <div class="form-group">
                                    <label for="idSubProcessoOrigem" class="text-success font-weight-bold">Tipo de solicitação de serviço:</label>
                                    <select class="form-control form-control-sm" id="idSubProcessoOrigem" name="idSubProcessoOrigem" v-model="tipoOrigem" @change="selectTipo(1)" required>
                                        <option value="">Nenhum tipo de processo de origem escolhido</option>
                                        <option v-for="conteudo in listaTipoOrigem" v-bind:key="conteudo.id_tipo_processo" v-bind:value="conteudo.id_tipo_processo">{{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4" v-if="opcaoEntrada">
                                <div class="form-group">
                                    <label for="responsavelOrigem"  class="text-success font-weight-bold">Responsável pela Origem:</label>
                                    <select class="form-control form-control-sm" id="responsavelOrigem" name="responsavelOrigem" v-model="subordinadoOrigem" @change="selectResponsavel()" required>
                                        <option value="">Nenhum responsável atribuído</option>
                                        <option v-for="conteudo in listaSubordinadoOrigem" v-bind:key="conteudo.id" v-bind:value="conteudo.id">{{ conteudo.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Saída -->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6" v-if="opcaoSaida">
                                <div class="form-group">
                                    <label for="idProcessoDestino" class="text-danger font-weight-bold">Processo de destino:</label>
                                    <select class="form-control form-control-sm" id="idProcessoDestino" name="idProcessoDestino" v-model="processoDestino" @change="selectProcess(null, processoDestino,2)" required>
                                        <option value="">Nenhum processo de destino escolhido</option>
                                        <option v-for="conteudo in listaProcessoDestino" v-bind:key="conteudo.id_processo" v-bind:value="conteudo.id_processo">[{{ conteudo.sigla_empresa }}] - {{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>
                            <!--<div class="col-12 col-sm-6 col-md-6 col-lg-6" v-if="opcaoSaida">
                                <div class="form-group">
                                    <label for="idSubProcessoDestino" class="text-danger font-weight-bold">Tipo de solicitação de serviço:</label>
                                    <select class="form-control form-control-sm" id="idSubProcessoDestino" name="idSubProcessoDestino" v-model="tipoDestino" @change="selectTipo(2)" required>
                                        <option value="">Nenhum tipo de processo de destino escolhido</option>
                                        <option v-for="conteudo in listaTipoDestino" v-bind:key="conteudo.id_tipo_processo" v-bind:value="conteudo.id_tipo_processo">{{ conteudo.descricao }}</option>
                                    </select>
                                </div>
                            </div>-->
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6" v-if="opcaoSaida">
                                <div class="form-group">
                                    <label for="responsavelDestino"  class="text-danger font-weight-bold">Responsável pelo Destino:</label>
                                    <select class="form-control form-control-sm" id="responsavelDestino" name="responsavelDestino" v-model="subordinadoDestino" @change="selectResponsavel()" required>
                                        <option value="">Nenhum responsável atribuído</option>
                                        <option v-for="conteudo in listaSubordinadoDestino" v-bind:key="conteudo.id" v-bind:value="conteudo.id">{{ conteudo.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Segunda etapa - Processo alvo -->


                            <!-- Dados do entregável -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12" v-if="opcaoDados">
                                <div class="form-group">
                                    <label for="entregavel">Entregável:</label>
                                    <input class="form-control form-control-sm" type="text" name="entregavel" minlength="10" maxlength="250" id="entregavel" placeholder="Informe o título do entregável" required>
                                </div>
                            </div>
                            <!-- Dados do entregável -->

                            <!-- Dados de periodicidade -->
                            <div class="col-12" v-if="opcaoDados">
                                <div class="form-group">
                                    <label for="periodicidade">Periodicidade:</label>
                                    <select class="form-control form-control-sm" id="periodicidade" v-model="periodicidade" required>
                                        <option value="">Nenhum período escolhido</option>
                                        <option v-for="conteudo in listaEventoEntrada" v-bind:key="conteudo.id" v-bind:value="conteudo">{{ conteudo.description }}</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="periodicidade" v-bind:value="periodicidade.id">
                            <div class="col-12" v-if="periodicidade.date">
                                <div class="form-group">
                                    <label for="periodicidade_data">Data de início:</label>
                                    <input type="date" v-bind:min="menorHora" class="form-control form-control-sm" id="periodicidade_data" name="periodicidade_data" required>
                                </div>
                            </div>
                            <div class="col-12" v-if="periodicidade.hour">
                                <div class="form-group">
                                    <label for="periodicidade_hora">Horário de início:</label>
                                    <input type="time" class="form-control form-control-sm" id="periodicidade_hora" name="periodicidade_hora" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6" v-if="periodicidade.datetime">
                                <div class="form-group">
                                    <label for="periodicidade_data">Data de início:</label>
                                    <input type="date" v-bind:min="menorHora" class="form-control form-control-sm" id="periodicidade_data" name="periodicidade_data" required>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6" v-if="periodicidade.datetime">
                                <div class="form-group">
                                    <label for="periodicidade_hora">Horário de início:</label>
                                    <input type="time" class="form-control form-control-sm" id="periodicidade_hora" name="periodicidade_hora" required>
                                </div>
                            </div>
                            <!-- Dados de periodicidade -->


                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" v-if="opcaoDados">
                                <div class="form-group" v-for="conteudo in listaQuestao" v-bind:key="conteudo.id_pergunta_tipo">
                                    <label v-bind:for="'questao_' + conteudo.id_pergunta_tipo">{{ conteudo.descricao }}</label>
                                    <input v-if="((conteudo.tipo !== 'datetime') && (conteudo.tipo !== 'date') && (conteudo.tipo !== 'longtext'))" v-bind:type="conteudo.tipo" minlength="20" maxlength="320" class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" required>
                                    <input v-if="conteudo.tipo === 'date'" v-bind:min="menorHora" v-bind:type="conteudo.tipo"  class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" required>
                                    <div class="col-12" v-if="conteudo.tipo === 'datetime'">
                                        <div class="row">
                                            <input type="date" v-bind:min="menorHora" class="form-control form-control-sm col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-bind:id="'questao_' + conteudo.id_pergunta_tipo + '_data'" v-bind:name="'questao_' + conteudo.id_pergunta_tipo + '_data'" required>
                                            <input type="time" class="form-control form-control-sm col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-bind:id="'questao_' + conteudo.id_pergunta_tipo + '_hora'" v-bind:name="'questao_' + conteudo.id_pergunta_tipo + '_hora'" required>
                                        </div>
                                    </div>
                                    <textarea v-if="conteudo.tipo === 'longtext'" minlength="20" class="form-control form-control-sm" v-bind:placeholder="conteudo.descricao" v-bind:id="'questao_' + conteudo.id_pergunta_tipo" v-bind:name="'questao_' + conteudo.id_pergunta_tipo" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer" v-if="opcaoDados">
                        <button type="submit" class="btn btn-block btn-sm btn-primary">Cadastrar troca de objetos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'url'],
        data() {
            return {
                metaCSRF: document.querySelector('meta[name="csrf-token"]').content,
                carregamento: false,
                opcaoEntrada: false,
                opcaoSaida: false,
                opcaoDados: false,
                opcao: "",
                processoReferencia: "",
                processoOrigem: "",
                processoDestino: "",
                tipoOrigem: "",
                tipoDestino: "",
                subordinadoOrigem: "",
                subordinadoDestino: "",
                menorHora: null,
                periodicidade: "",

                // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # --
                listaOpcoes: [
                    {'id': 1, 'description': 'Entrada', 'icone': 'fas fa-arrow-alt-circle-down'},
                    {'id': 2, 'description': 'Saída', 'icone': 'fas fa-sign-out-alt'},
                ],
                listaProcessoOrigem: {},
                listaProcessoDestino: {},
                listaTipoOrigem: {},
                listaTipoDestino: {},
                listaSubordinadoOrigem: {},
                listaSubordinadoDestino: {},
                listaQuestao: {},
                // -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # -- # --
                listaTipoObj: [
                    {
                        "id" : 1,
                        "description" : "Documento digitalizado",
                    },
                ],
                listaMeio: [
                    {
                        "id" : 1,
                        "description" : "e-mail",
                    },
                ],
                listaEventoEntrada: [
                    {
                        "id" : 1,
                        "description" : "Diário",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 2,
                        "description" : "Semanal",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 3,
                        "description" : "Quinzenal",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 4,
                        "description" : "Mensal",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 5,
                        "description" : "Bimestral",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 6,
                        "description" : "Semestral",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                    {
                        "id" : 7,
                        "description" : "Anual",
                        "date" : false,
                        "hour" : false,
                        "datetime" : true,
                    },
                ],
            }
        },
        methods: {
            selectOption: function(){
                var vm = this;
                vm.processoOrigem           =   "";
                vm.processoDestino          =   "";
                vm.tipoOrigem               =   "";
                vm.tipoDestino              =   "";
                vm.subordinadoOrigem        =   "";
                vm.subordinadoDestino       =   "";
                vm.menorHora                =   null;
                vm.periodicidade            =   "";

                vm.listaTipoOrigem          =   {};
                vm.listaTipoDestino         =   {};
                vm.listaSubordinadoOrigem   =   {};
                vm.listaSubordinadoDestino  =   {};
                vm.listaQuestao             =   {};

                if(vm.opcao == 1) {
                    vm.opcaoEntrada = true;
                    vm.opcaoSaida = false;
                }
                else if(vm.opcao == 2){
                    vm.processoOrigem = vm.processoReferencia;
                    vm.selectProcess(vm.processoOrigem,null,1);
                    vm.opcaoEntrada = true;
                    vm.opcaoSaida = true;
                }
                else {
                    vm.opcaoEntrada = false;
                    vm.opcaoSaida = false;
                }
            },
            selectProcess: function(idOrigem, idDestino, id) {
                var vm      =   this;

                if(id == vm.opcao) {
                    vm.listaQuestao             =   {};
                }

                if(idOrigem != null) {
                    vm.tipoOrigem               =   "";
                    vm.listaTipoOrigem          =   {};
                    vm.subordinadoOrigem        =   "";
                    vm.listaSubordinadoOrigem   =   {};

                    vm.coletaSubProcesso(idOrigem,0);
                } // if(idOrigem != null) { ... }

                if(idDestino != null) {
                    vm.tipoDestino              =   "";
                    vm.listaTipoDestino         =   {};
                    vm.subordinadoDestino       =  "";
                    vm.listaSubordinadoDestino  =   {};

                    vm.coletaSubProcesso(idDestino,1);
                } // if(idOrigem != null) { ... }

            },
            selectTipo: function(id) {
                var vm  =   this;
                if(vm.opcao == id) {
                    vm.coletaQuestao();
                }
            },
            selectResponsavel: function() {
                var vm = this;

                if(vm.opcaoEntrada && !vm.opcaoSaida) {
                    if(vm.subordinadoOrigem != "") {
                        vm.opcaoDados = true;
                    } // if(opcaoEntrada && subordinadoOrigem != "") { ... }
                    else {
                        vm.opcaoDados = false;
                    }
                }
                else if(!vm.opcaoEntrada && vm.opcaoSaida) {
                    if(vm.opcaoSaida && vm.subordinadoDestino != "") {
                        vm.opcaoDados = true;
                    } // if(opcaoSaida && subordinadoOrigem != "") { ... }
                    else {
                        vm.opcaoDados = false;
                    }
                }
                else if(vm.opcaoEntrada && vm.opcaoSaida) {
                    if(vm.subordinadoDestino != "" && vm.subordinadoOrigem != "") {
                        vm.opcaoDados = true;
                    }
                    else {
                        vm.opcaoDados = false;
                    }
                }
                else {
                    vm.opcaoDados = false;
                }
            },
            coletaProcesso : function(){
                var vm = this;

                var vRequisicao     =   {
                    'idUsuario' :   vm.id,
                };
                vm.carregamento = true;

                axios.post('/api/util/resp',vRequisicao)
                .then(function (response) {
                    if(response.status === 200) {
                        vm.carregamento         =   false;
                        vm.listaProcessoOrigem  =   response.data.processoOrigem;
                        vm.listaProcessoDestino =   response.data.processoDestino;
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
            coletaSubProcesso : function(idProcesso, idTipo){
                var vm          = this;
                if(idProcesso != null) {

                    if(idTipo == 0) {
                        vm.listaTipoOrigem          =   {};
                        vm.tipoOrigem               =   "";
                        vm.listaSubordinadoOrigem   =   {};
                        vm.subordinadoOrigem        =   "";
                    } // if(idTipo == 0) { ... }

                    if(idTipo == 1) {
                        vm.listaTipoDestino         =   {};
                        vm.tipoDestino              =   "";
                        vm.listaSubordinadoDestino  =   {};
                        vm.subordinadoDestino       =   "";
                    } // if(idTipo == 0) { ... }

                    vm.carregamento         =   true;

                    try {
                        var vRequisicao = {
                            idUsuarioBPMS   :   vm.id,
                            idProcessoBPMS  :   idProcesso,
                        };

                        axios.post('/api/util/tipoObj',vRequisicao)
                        .then(function (response) {
                            if(response.status === 200) {
                                vm.carregamento =   false;
                                if(idTipo == 0) {
                                    vm.listaTipoOrigem          =   response.data.tipo;
                                    vm.listaSubordinadoOrigem   =   response.data.sub;
                                }

                                if(idTipo == 1) {
                                    vm.listaTipoDestino         =   response.data.tipo;
                                    vm.listaSubordinadoDestino  =   response.data.sub;
                                }
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
                }
            },
            coletaQuestao : function(){
                var vm = this;
                vm.listaQuestao         =   [];

                if(vm.opcao == 1 && vm.tipoOrigem == null) return;
                if(vm.opcao == 2 && vm.tipoDestino == null) return;

                vm.carregamento         =   true;

                try {
                    var vRequisicao = {
                        idUsuario: document.getElementById("idUsuarioBPMS").value,
                        idProcesso: (vm.opcao == 1) ? vm.processoOrigem : vm.processoDestino,
                        idTipo: (vm.opcao == 1) ? vm.tipoOrigem : vm.tipoDestino,
                    };

                    axios.post('/api/util/questao',vRequisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.carregamento =   false;
                            vm.listaQuestao =   response.data.questao;
                            vm.menorHora    =   response.data.menorHora;
                        }
                        else {
                            vm.carregamento =   false;
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
