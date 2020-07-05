<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 mt-1">
                <button type="submit" class="btn btn-success btn-sm btn-block" @click="consultaDados">
                    Atualizar lista
                </button>
            </div>
        </div>
        <div class="card border-primary mt-1">
            <div class="card-header">
                <small class="d-block text-primary text-center">Lista ordenada por: {{ sortBy }} {{ sortDesc ? 'decrescente' : 'crescente'}} </small>
            </div>
            <div class="card-body">
                <b-table
                    responsive
                  :striped="false"
                  :small="true"
                  :busy="isBusy"
                  :hover="true"
                  :dark="false"
                  :fixed="false"
                  :foot-clone="true"
                  :no-border-collapse="true"
                  :items="items"
                  :fields="fields"
                  :per-page="10"  
                  :sort-by.sync="sortBy"
                  :sort-desc.sync="sortDesc"
                  
                >
                    <template v-slot:table-busy>
                      <div class="text-center text-primary my-2">
                        <b-spinner class="align-middle"></b-spinner>
                        <strong>Carregando ...</strong>
                      </div>
                    </template>
                </b-table>
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
                sortBy: '#',
                sortDesc: false,
                requisicao : {},
                fields: [
                  { key: 'id',                label: '#ID',               sortable: true },
                  { key: 'titulo',            label: 'Titulo',            sortable: true },
                  { key: 'solicitante',       label: 'Solicitante',       sortable: true },
                  { key: 'situacao',          label: 'Situação',          sortable: true },
                  { key: 'responsavel',       label: 'Responsável',       sortable: true },
                  { key: 'empresa',           label: 'Empresa',           sortable: true },
                  { key: 'processo',          label: 'Processo',          sortable: true },
                  { key: 'dataSolicitacao',   label: 'Data solicitação',  sortable: true },
                  { key: 'dataVencimento',    label: 'Data vencimento',   sortable: true },
                  { key: 'dataConclusao',     label: 'Data conclusão',    sortable: true },
                  { key: 'prazoContratado',   label: 'Prazo contratado',  sortable: true },
                  { key: 'prazoAtribuido',    label: 'Prazo atribuído',   sortable: true },
                  { key: 'prazo',             label: 'Prazo',             sortable: true },
                  { key: 'atraso',            label: 'Atraso',            sortable: true },
                ],
                items: [
                ]
            }
        },
        methods: {
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