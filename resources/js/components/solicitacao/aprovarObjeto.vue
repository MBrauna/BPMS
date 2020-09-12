 <template>
    <div class="row">
        <!-- Conteúdo de processos -->
        <form method="POST" action="/agendamento/aprovarSub" class="row col-12 d-flex justify-content-center">
            <input type="hidden" v-bind:value="id" name="id_entrada_solicitacao">
            <input type="hidden" name="_token" v-model="metaCSRF">
        
            <div class="row" v-for="conteudo in listaObjetos" v-bind:key="conteudo.id_entrada_solicitacao">

                <input type="hidden" name="entrada" v-bind:value="conteudo.sla_cliente ? 1 : 0">
                <input type="hidden" name="destino" v-bind:value="conteudo.sla_fornecedor ? 1 : 0">

                <div v-bind:class="conteudo.tipo == 1 ? 'col-12 col-sm-12 col-md-12' : 'col-12 col-sm-6 col-md-6'">
                    <div class="form-group">
                        <label for="entrada"><small>Cliente</small></label>
                        <div v-if="conteudo.entradaDono > 0" class="btn-group-toggle" data-toggle="buttons">
                            <button v-if="conteudo.sla_cliente" type="submit" class="btn btn-danger btn-block btn-sm" @click="conteudo.sla_cliente = false;">Recusar</button>
                            <button v-else type="submit" class="btn btn-success btn-block btn-sm" @click="conteudo.sla_cliente = true;">Aprovar</button>
                        </div>
                        <div v-else class="btn-group-toggle" data-toggle="buttons">
                            <button class="btn btn-secondary btn-block btn-sm">{{ conteudo.sla_cliente ? 'Aprovado' : 'Recusado' }}</button>
                        </div>
                    </div>
                </div>
                <div v-if="conteudo.tipo == 2" class="col-12 col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="entrada"><small>Fornecedor</small></label>
                        <div v-if="conteudo.destinoDono > 0" class="btn-group-toggle" data-toggle="buttons">
                            <button v-if="conteudo.sla_fornecedor" type="submit" class="btn btn-danger btn-block btn-sm" @click="conteudo.sla_fornecedor = false;">Recusar</button>
                            <button v-else type="submit" class="btn btn-success btn-block btn-sm" @click="conteudo.sla_fornecedor = true;">Aprovar</button>
                        </div>
                        <div v-else class="btn-group-toggle" data-toggle="buttons">
                            <button type="button" class="btn btn-secondary btn-block btn-sm">{{ conteudo.sla_fornecedor ? 'Aprovado' : 'Recusado' }}</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</template>

<script>
    export default {
        props: ['id'],
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
                    'idUsuario' :   vm.idUsuario,
                    'idObjeto'  :   vm.id,
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
                            'Erro ao coletar os dados! Verifique para Objeto #' + vm.id,
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