 <template>
    <div>
        <!-- Conteúdo de processos -->
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                Aprovação de objetos
            </div>
            <div class="card-body">
                <h6 v-if="listaObjetos.length <= 0" class="text-primary text-center">
                    <i class="far fa-frown-open"></i><br/>
                    <small>Nenhum objeto disponível para aprovação</small>
                </h6>
                <ul v-else class="list-group list-group-flush">
                    <li class="list-group-item" v-for="conteudo in listaObjetos" v-bind:key="conteudo.id_entrada_solicitacao">
                        <form method="POST" action="/agendamento/aprovarSub" class="row col-12 d-flex justify-content-center">
                            <input type="hidden" v-bind:value="conteudo.id_entrada_solicitacao" name="id_entrada_solicitacao">
                            <input type="hidden" name="_token" v-model="metaCSRF">

                            <div class="card border-primary col-12">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="procEntrada">Processo de entrada</label>
                                                <input type="text" class="form-control" id="procEntrada" v-bind:value="conteudo.entradaData.descricao" disabled>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label for="procSaida">Processo de destino</label>
                                                <input type="text" class="form-control" id="procSaida" v-bind:value="conteudo.destinoData.descricao" disabled>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="procSaida">Entregável</label>
                                                <input type="text" class="form-control" id="procSaida" v-bind:value="conteudo.titulo" disabled>
                                            </div>
                                        </div>
                                        <!-- Lista de aprovação -->
                                        <div class="col-12 col-sm-6 col-md-6">
                                            <h6 class="text-primary text-center font-weight-bold">
                                                Cliente<br/><small v-bind:class="conteudo.sla_cliente ? 'text-success' : 'text-danger'">Situação atual: {{ conteudo.sla_cliente ? 'Aprovado' : 'Recusado' }}</small>
                                            </h6>
                                            <div v-if="conteudo.entradaDono > 0" class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-success btn-block">
                                                    <input type="radio" name="entrada" value="1"> Aprovar
                                                </label>
                                                <label class="btn btn-danger btn-block">
                                                    <input type="radio" name="entrada" value="0" checked> Recusar
                                                </label>
                                            </div>
                                            <div v-else class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-danger btn-block active">
                                                    <input type="radio" name="entrada" v-bind:value="conteudo.sla_cliente ? 1 : 0" checked> {{ conteudo.sla_cliente ? 'Aprovado' : 'Recusado' }}
                                                </label>
                                            </div>
                                        </div>


                                        <div class="col-12 col-sm-6 col-md-6">
                                            <h6 class="text-primary text-center font-weight-bold">
                                                Fornecedor<br/><small v-bind:class="conteudo.sla_fornecedor ? 'text-success' : 'text-danger'">Situação atual: {{ conteudo.sla_fornecedor ? 'Aprovado' : 'Recusado' }}</small>
                                            </h6>
                                            <div v-if="conteudo.destinoDono > 0" class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-success btn-block">
                                                    <input type="radio" name="destino" value="1"> Aprovar
                                                </label>
                                                <label class="btn btn-danger btn-block">
                                                    <input type="radio" name="destino" value="0" checked> Recusar
                                                </label>
                                            </div>
                                            <div v-else class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-danger btn-block active">
                                                    <input type="radio" name="destino" v-bind:value="conteudo.sla_fornecedor ? 1 : 0" checked> {{ conteudo.sla_fornecedor ? 'Aprovado' : 'Recusado' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success btn-block">
                                        Confirmar ação
                                    </button>
                                </div>
                            </div>
                            
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url'],
        data: function() {
            return {
                listaObjetos: {},
                metaCSRF: document.querySelector('meta[name="csrf-token"]').content,
                idUsuario: document.getElementById("idUsuarioBPMS").value,
            }
        },
        methods: {
            coletaObjetos   :   function(){
                var vm      =   this;

                vm.requisicao = {
                    'idUsuario'       : vm.idUsuario,
                };

                try {
                    axios.post('/api/util/aproveObj',vm.requisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.listaObjetos    =   response.data;
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
            this.coletaObjetos();
        },
    }
</script>