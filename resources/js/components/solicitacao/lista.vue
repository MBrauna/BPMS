<template>
    <div>
        <div class="card border-primary">
            <div class="card-header">
                <button type="submit" class="btn btn-success btn-sm btn-block" @click="consultaDados">
                    Atualizar lista
                </button>
            </div>
            <div class="card-body">
                <b-table
                    id="tabela-solicitacao"
                    responsive
                    fixed
                    :striped="false"
                    :small="true"
                    :busy="isBusy"
                    :hover="true"
                    :items="items"
                    :fields="fields"
                    :per-page="perPage"  
                    :current-page="currentPage"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                >
                    <template v-slot:table-busy>
                      <div class="text-center text-primary my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Carregando ...</strong>
                      </div>
                    </template>

                    <template v-slot:cell(id)="data">
                        <span v-html="data.value"></span>
                    </template>

                    <template v-slot:cell(titulo)="data">
                        <span v-html="data.value"></span>
                    </template>

                    <template v-slot:cell(prazo)="data">
                        <span v-html="data.value"></span>
                    </template>
                </b-table>
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="right"
                    size="sm"
                    class="my-0"
                    aria-controls="tabela-solicitacao"
                ></b-pagination>
            </div>
            <div class="card-footer bg-primary">
                <small class="d-block text-white text-center">Ordenado por {{ coletaNome(sortBy) }} - {{ sortDesc ? 'decrescente' : 'crescente'}} </small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                filtroConteudo:{},
                filter:{},
                isBusy: true,
                sortBy: 'dataVencimento',
                sortDesc: false,
                requisicao : {},
                perPage: 7,
                currentPage: 1,
                totalRows: 0,
                fields: [
                    { key: 'id',                label: '#ID',                   sortable: true,   thStyle: { width: '5em !important'},  },
                    { key: 'titulo',            label: 'O que foi solicitado',  sortable: true,   thStyle: { width: '20em !important'}, stickyColumn: true, },
                    { key: 'solicitante',       label: 'Quem solicitou',        sortable: true,   thStyle: { width: '14em !important'}, },
                    { key: 'dataSolicitacao',   label: 'Data solicitação',      sortable: true,   thStyle: { width: '10em !important'}, class: 'text-center'},
                    { key: 'dataVencimento',    label: 'Data vencimento',       sortable: true,   thStyle: { width: '10em !important'}, class: 'text-center'},
                    { key: 'prazo',             label: 'Prazo',                 sortable: true,   thStyle: { width: '12em !important'}, class: 'text-center' },
                    { key: 'situacao',          label: 'Situação atual',              sortable: true,   thStyle: { width: '30em !important'}, },
                    { key: 'responsavel',       label: 'Responsável',           sortable: true,   thStyle: { width: '14em !important'}, },
                    { key: 'processo',          label: 'Processo',              sortable: true,   thStyle: { width: '20em !important'}  },
                    { key: 'empresa',           label: 'Empresa',               sortable: true,   thStyle: { width: '6em !important'},  },
                ],
                items: [
                ]
            }
        },
        methods: {
            coletaNome      :   function(chave){
                var retorno     =   'padrão';

                this.fields.forEach(element => {
                    if(element.key === chave) retorno = element.label;
                });

                return retorno;
            },
            preencheCampos  :   function() {
                var vm              =   this;
                vm.filtroConteudo   =   vm.BPMS.coletaFiltro();
            }, // preencheCampos: function() { ... }
            consultaDados   :   function(){
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

                axios.post('/api/request/lista',vm.requisicao)
                .then(function (response) {
                    vm.isBusy = false;
                    if(response.status === 200) {
                        vm.items    =   response.data;
                        vm.totalRows=   vm.items.length;
                    }
                    else {
                        vm.$bvToast.toast(
                        (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Não foi possível obter a lsita de solicitações de serviço.',
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
                .catch(function (error) {
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
                    vm.isBusy   =   false;
                });
            },
        },
        mounted() {
            this.consultaDados();
        },
    }
</script>