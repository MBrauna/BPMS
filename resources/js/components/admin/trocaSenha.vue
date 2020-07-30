<template>
    <div class="container">
        <form class="row justify-content-center" method="POST" v-bind:action="url" autocomplete="off" ref="trocaSenhaForm">
            <input type="hidden" name="_token" v-bind:value="hash">
            <div class="form-group col-12">
                <label for="novaSenha">Informe a nova senha:</label>
                <input type="password" class="form-control form-control-sm" id="novaSenha" name="novaSenha" aria-describedby="novaSenha" placeholder="Informe a nova senha" onkeypress="return noenter()" v-model="senhaNova" required>
            </div>
            <div class="form-group col-12">
                <label for="senhaRepete">Repita a nova senha:</label>
                <input type="password" class="form-control form-control-sm" id="senhaRepete" name="senhaRepete" aria-describedby="senhaRepete" placeholder="Repita a nova senha" onkeypress="return noenter()" v-model="senhaConfirmacao" required>
            </div>
            <button type="button" class="btn btn-sm btn-block btn-primary" v-on:click="validaFormulario()">
                Alterar senha
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['url','hash'],
        data() {
            return {
                senhaNova: null,
                senhaConfirmacao: null,
            }
        },
        methods: {
            validaFormulario : function(){
                var vm = this;
                if(!vm.senhaNova || vm.senhaNova.trim() === "") {
                    vm.$bvToast.toast(
                        'O campo da senha atual não foi preenchida! Verifique',
                        {
                            title: 'Troca de senhas',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'danger',
                        }
                    );
                    document.getElementById('novaSenha').focus();
                    return;
                } // if(!vm.senhaNova) { ... }

                if(!vm.senhaConfirmacao || vm.senhaConfirmacao.trim() === "") {
                    vm.$bvToast.toast(
                        'A confirmação para a nova senha não foi preenchida! Verifique',
                        {
                            title: 'Troca de senhas',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'danger',
                        }
                    );
                    document.getElementById('senhaRepete').focus();
                    return;
                } // if(!vm.senhaConfirmacao) { ... }

                if(vm.senhaNova !== vm.senhaConfirmacao) {
                    vm.$bvToast.toast(
                        'Atenção! As senhas informadas não coincidem! Verifique.',
                        {
                            title: 'Troca de senhas',
                            autoHideDelay: 5000,
                            appendToast: true,
                            solid: true,
                            variant: 'danger',
                        }
                    );
                    return;
                }

                vm.$refs.trocaSenhaForm.submit();
            } // validaFormulario : function(){ ... }
        },
        mounted() {
        }
    }
</script>
