require('./bootstrap');
import BootstrapVue from 'bootstrap-vue' //Importing

window.Vue = require('vue');
Vue.use(BootstrapVue) // Telling Vue to use this in whole application


/// [GLOBAIS] - Dados globais
import {BPMS} from './globais.js'
Vue.prototype.BPMS  =   BPMS;

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('filtro-bpms', require('./components/solicitacao/FiltroBPMS.vue').default);
Vue.component('abertura-bpms', require('./components/solicitacao/abertura.vue').default);
Vue.component('lista-solicitacao', require('./components/solicitacao/lista.vue').default);
Vue.component('lista-tarefa', require('./components/tarefa/tarefa.vue').default);

Vue.component('admin-usuario', require('./components/admin/usuario.vue').default);

const app = new Vue({
    el: '#app',
});