 <template>
    <div class="row">
        <!-- Conteúdo de processos -->
        <form method="POST" action="/agendamento/aprovarSub" class="row col-12 d-flex justify-content-center">
            <input type="hidden" v-bind:value="id" name="id_entrada_solicitacao">
            <input type="hidden" name="_token" v-model="metaCSRF">
        
            <div class="col-10 row" v-for="conteudo in listaObjetos" v-bind:key="conteudo.id_entrada_solicitacao">

                <input type="hidden" name="entrada" v-bind:value="conteudo.sla_cliente ? 1 : 0">
                <input type="hidden" name="destino" v-bind:value="conteudo.sla_fornecedor ? 1 : 0">

                <div class="col-12">
                    <div class="form-group d-flex justify-content-center">
                        <div v-if="conteudo.tipo == 1">
                            <button v-if="conteudo.entradaDono > 0 && conteudo.sla_cliente" type="submit" class="btn btn-danger btn-block btn-sm" @click="conteudo.sla_cliente = false;"><i class="fas fa-thumbs-down"></i> Cancelar cliente</button>
                            <button v-else-if="conteudo.entradaDono > 0 && !conteudo.sla_cliente" type="submit" class="btn btn-success btn-block btn-sm" @click="conteudo.sla_cliente = true;"><i class="fas fa-thumbs-up"></i> Firmar cliente</button>
                            <button v-else class="btn btn-secondary btn-block btn-sm">{{ conteudo.sla_cliente ? 'Firmado cliente' : 'Cancelado cliente' }}</button>
                        </div>
                        <div v-else>
                            <button v-if="conteudo.entradaDono > 0 && conteudo.sla_cliente" type="submit" class="btn btn-danger btn-block btn-sm" @click="conteudo.sla_cliente = false;"><i class="fas fa-thumbs-down"></i> Cancelar fornec.</button>
                            <button v-else-if="conteudo.entradaDono > 0 && !conteudo.sla_cliente" type="submit" class="btn btn-success btn-block btn-sm" @click="conteudo.sla_cliente = true;"><i class="fas fa-thumbs-up"></i> Firmar fornec.</button>
                            <button v-else class="btn btn-secondary btn-block btn-sm">{{ conteudo.sla_cliente ? 'Firmado fornec.' : 'Cancelado fornec.' }}</button>
                        </div>
                    </div>
                </div>




                <div v-if="conteudo.tipo == 2" class="col-12">
                    <div class="form-group d-flex justify-content-center">
                        <button v-if="conteudo.destinoDono > 0 && conteudo.sla_fornecedor" type="submit" class="btn btn-danger btn-block btn-sm" @click="conteudo.sla_fornecedor = false;"><i class="fas fa-thumbs-down"></i> Cancelar Cliente</button>
                        <button v-else-if="conteudo.destinoDono > 0 && !conteudo.sla_fornecedor" type="submit" class="btn btn-success btn-block btn-sm" @click="conteudo.sla_fornecedor = true;"><i class="fas fa-thumbs-up"></i>Firmar Cliente</button>
                        <button v-else type="button" class="btn btn-secondary btn-block btn-sm">{{ conteudo.sla_fornecedor ? 'Firmado cliente' : 'Cancelado cliente' }}</button>
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