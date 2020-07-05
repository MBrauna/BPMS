<template>
    <div class="container-fluid mt-1">
        <!-- Conteúdo de processos -->
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-success btn-sm btn-block" @click="coletaTarefa()">
                    Atualizar lista
                </button>
            </div>
            <div class="col-12 mt-1">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        Tarefas disponíveis para execução
                    </div>
                    <div class="card-body">
                        <h6 v-if="listaTarefa.length <= 0" class="text-primary text-center">
                            <i class="far fa-frown-open"></i><br/>
                            <small>Nenhuma tarefa disponível para esta preferência</small>
                        </h6>
                        <ul v-else class="list-group list-group-flush">
                            <li class="list-group-item" v-for="conteudo in listaTarefa" v-bind:key="conteudo.id_chamado">
                                <form method="POST" action="/" class="col-12 col-sm-12 col-md-12 col-lg-6 mt-2 mb-2">
                                    <input type="hidden" name="_token" v-model="metaCSRF">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white text-center">
                                            Solicitação de serviço #{{ conteudo.id_chamado}}<br/>
                                            <small>
                                                <a v-bind:href="'/solicitacao/' + conteudo.id_chamado">
                                                    {{ conteudo.titulo }}
                                                </a>
                                            </small>
                                        </div>
                                        <div class="card-body">
                                            {{ conteudo }}
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-block btn-sm btn-success">
                                                Aplicar tarefa #{{ conteudo.id_chamado }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                metaCSRF: document.querySelector('meta[name="csrf-token"]').content,
                listaTarefa: [],
            }
        },
        methods: {
            preencheCampos  :   function() {
                var vm              =   this;
                vm.filtroConteudo   =   vm.BPMS.coletaFiltro();
            }, // preencheCampos: function() { ... }
            coletaTarefa    :   function(){
                var vm      = this;

                vm.isBusy   = true;
                vm.preencheCampos();

                vm.requisicao = {
                    'idUsuario'       : document.getElementById("idUsuarioBPMS").value,
                    'idBPMS'          : vm.filtroConteudo.codigo, 
                    'tituloBPMS'      : vm.filtroConteudo.titulo,
                    'idEmpresaBPMS'   : vm.filtroConteudo.empresa,
                    'idProcessoBPMS'  : vm.filtroConteudo.processo,
                    'idTipoBPMS'      : vm.filtroConteudo.tipo,
                    'idSituacaoBPMS'  : vm.filtroConteudo.situacao,
                };

                try {
                    axios.post('/api/task/lista',vm.requisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.listaTarefa    =   response.data;
                        }
                        else {
                            vm.$bvToast.toast(
                                response.data.erro.mensagem,
                                {
                                    title: 'Mensagem BPMS',
                                    autoHideDelay: 5000,
                                    appendToast: true,
                                    solid: true,
                                    variant: 'danger',
                                }
                            );
                        }
                    })
                    .catch(function(retorno){
                        vm.$bvToast.toast(
                            response.data.erro.mensagem,
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
                    console.log('erroo --> ' + erro);
                    vm.$bvToast.toast(
                        'Não foi possível carregar o componente! Verifique.',
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
        },
        mounted() {
            this.coletaTarefa();
        },
    }
</script>