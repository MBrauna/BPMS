<template>
    <div class="container-fluid">
        <h6 v-if="sortBy !== null" class="text-primary text-center">
          <small class="d-block">Lista ordenada por: {{ sortBy }} {{ sortDesc ? 'decrescente' : 'crescente'}} </small>
        </h6>
        <div class="card border-primary">
            <div class="card-header">
                <button type="submit" class="btn btn-success btn-sm btn-block" @click="consultaDados">
                    Atualizar lista
                </button>
            </div>
            <div class="card-body">
                <b-table
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
                  :responsive="true"
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

                console.log(vm.requisicao);

                axios.post('/api/request/lista',vm.requisicao)
                .then(function (response) {
                    vm.isBusy = false;
                    if(response.status === 200) {
                        vm.items    =   response.data;
                    }
                    else {
                        alert(response.data.erro.mensagem);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                    vm.isBusy   =   false;
                });
            },
        },
        mounted() {
            this.consultaDados();
        },
    }
</script>