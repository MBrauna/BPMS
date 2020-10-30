<template>
    <div>
        <!-- Conteúdo de processos -->
        <div class="row">
            <div class="col-12">
                <div class="card border-primary">
                    <div class="card-header">
                        <button type="submit" class="btn btn-success btn-sm btn-block" @click="coletaTarefa()">
                            Atualizar lista
                        </button>
                    </div>

                    <div class="card-body">
                        <h6 v-if="listaTarefa.length <= 0" class="text-primary text-center">
                            <i class="far fa-frown-open"></i><br/>
                            <small>Nenhuma tarefa disponível para esta preferência</small>
                        </h6>
                        <ul v-else class="list-group list-group-flush">
 
                            <li class="list-group-item border-primary" v-for="conteudo in listaTarefa" v-bind:key="conteudo.tarefa.id_chamado">
                                <form method="POST" v-bind:action="url" class="row" autocomplete="off" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" v-model="metaCSRF">
                                    <input type="hidden" name="id_chamado" v-bind:value="conteudo.tarefa.id_chamado">
                                    <input type="hidden" name="id_usuario" v-bind:value="idUsuario" required>
                                    <a class="col-12 text-center" v-bind:href="'/solicitacao/' + conteudo.tarefa.id_chamado">
                                        #{{ conteudo.tarefa.id_chamado }} - {{ conteudo.tarefa.titulo }}
                                        <br/>
                                        <p class="text-danger font-weight-bold">Vencimento em {{ conteudo.venc }}</p>
                                    </a>

                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="row">
                                                    <div class="form-group col-12">
                                                        <label for="id_situacao">
                                                            <small>Etapa designada:</small>
                                                        </label>
                                                        <select class="form-control form-control-sm" id="id_situacao" name="id_situacao" v-model="situacaoEscolhida[conteudo.tarefa.id_chamado]" required>
                                                            <option v-for="situacao in conteudo.situacao" v-bind:key="situacao.id_situacao" v-bind:value="situacao.id_situacao">
                                                                {{ (situacao.id_situacao === conteudo.tarefa.id_situacao) ? 'MANTER:'+situacao.descricao : situacao.descricao }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="form-group col-12" v-if="conteudo.config.marca_responsavel">
                                                        <label for="id_responsavel">
                                                            <small>Responsável pelo atendimento:</small>
                                                        </label>
                                                        <select class="form-control form-control-sm" name="id_responsavel" id="id_responsavel" required>
                                                            <option value="" selected>Nenhum usuário selecionado</option>
                                                            <option v-for="usuario in conteudo.sub" v-bind:key="usuario.id" v-bind:value="usuario.id">{{ usuario.name }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-12" v-if="conteudo.config.alterar_data_vencimento">
                                                        <label for="data_vencimento">
                                                            <small>Data limite:</small>
                                                        </label>
                                                        <div class="row d-flex justify-content-center">
                                                            <input type="date" class="form-control form-control-sm col-12 col-sm-12 col-md-5" id="data_vencimento" name="data_vencimento" v-bind:value="conteudo.dataVenc" v-bind:min="conteudo.menorData" required>
                                                            <input type="time" class="form-control form-control-sm col-12 col-sm-12 col-md-6" id="hora_vencimento" name="hora_vencimento" v-bind:value="conteudo.horaVenc" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-check">
                                                    <input type="checkbox" name="abrirArquivo" class="form-check-input" v-bind:id="'abrirArquivo' + conteudo.tarefa.id_chamado" v-model="checked[conteudo.tarefa.id_chamado]">
                                                    <label class="form-check-label" for="abrirArquivo">Deseja anexar arquivo(s)? Máximo 2MB</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                                <div class="row">
                                                    <div class="form-group col-12">
                                                        <label for="entrada">
                                                            <small>Anotação:</small>
                                                        </label>
                                                        <textarea class="form-control form-control-sm" name="entrada" id="entrada" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12" v-if="checked[conteudo.tarefa.id_chamado]">
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
                                    <div class="col-12 mt-1">
                                        <button type="submit" class="btn btn-block btn-sm btn-primary">
                                            Aplicar tarefa #{{ conteudo.tarefa.id_chamado }}
                                        </button>
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
        props: ['url'],
        data: function() {
            return {
                situacaoEscolhida: {},
                metaCSRF: document.querySelector('meta[name="csrf-token"]').content,
                listaTarefa: [],
                idUsuario: null,
                checked: {},
            }
        },
        methods: {
            preencheCampos  :   function() {
                var vm              =   this;
                vm.filtroConteudo   =   vm.BPMS.coletaFiltro();
            }, // preencheCampos: function() { ... }
            coletaTarefa    :   function(){
                var vm      =   this;

                vm.isBusy   =   true;
                vm.preencheCampos();
                vm.idUsuario=   document.getElementById("idUsuarioBPMS").value

                vm.requisicao = {
                    'idUsuario'       : document.getElementById("idUsuarioBPMS").value,
                    'idBPMS'          : vm.filtroConteudo.codigo, 
                    'tituloBPMS'      : vm.filtroConteudo.titulo,
                    'idEmpresaBPMS'   : vm.filtroConteudo.empresa,
                    'idProcessoBPMS'  : vm.filtroConteudo.processo,
                    'idTipoBPMS'      : vm.filtroConteudo.tipo,
                    'idSituacaoBPMS'  : vm.filtroConteudo.situacao,
                    'idResponsavelBPMS' : vm.filtroConteudo.responsavel,
                    'idSolicitanteBPMS' : vm.filtroConteudo.solicitante,
                };

                try {
                    axios.post('/api/task/lista',vm.requisicao)
                    .then(function (response) {
                        if(response.status === 200) {
                            vm.listaTarefa    =   response.data;

                            vm.listaTarefa.forEach(element => {
                                vm.situacaoEscolhida[element.tarefa.id_chamado] =   element.tarefa.id_situacao;
                            });
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
            this.coletaTarefa();
        },
    }
</script>