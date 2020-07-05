<template>
    <div class="row">
        <div class="col-12">
            <button class="btn btn-block btn-sm btn-success" @click="consultaDados()">
                Atualizar lista
            </button>
        </div>
        <div class="col-12 mt-1">
            <div class="card border-primary">
                <div class="card-header bg-primary">
                    <div class="row">
                        <div class="col-10 text-white">
                            <h6>Usuários BPMS</h6>
                        </div>
                        <div class="col-2 text-center text-white">
                            <button class="btn btn-primary btn-block">
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>
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
                        <template v-slot:cell(acao)>
                            <b-button size="sm" @click="isBusy = true" class="bg-primary">
                                teste
                            </b-button>
                        </template>

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
    </div>
</template>


<script>
    export default {
        data() {
            return {
                isBusy: true,
                sortBy: '#',
                sortDesc: false,
                filtroConteudo: {},
                fields: [
                  { key: 'id',              label: '#ID',               sortable: true },
                  { key: 'nome',            label: 'Nome',              sortable: true },
                  { key: 'email',           label: 'E-mail',            sortable: true },
                  { key: 'admin',           label: 'Administrador',     sortable: true },
                  { key: 'dataCria',        label: 'Data Criação',      sortable: true },
                  { key: 'dataAlt',         label: 'Data Alteração',    sortable: true },
                  { key: 'acao',            label: 'Ações',             sortable: false},
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

                axios.post('/api/admin/usuario',vm.requisicao)
                .then(function (response) {
                    vm.isBusy = false;
                    if(response.status === 200) {
                        vm.items    =   response.data;
                    }
                    else {
                        vm.$bvToast.toast(
                        (response.data.erro.mensagem) ? response.data.erro.mensagem : 'Não foi possível obter a lsita de usuários.',
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
            }
        },
        mounted() {
            this.consultaDados();
        }
    }
</script>