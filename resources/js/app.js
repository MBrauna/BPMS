require('./bootstrap');
require('./globais');
window.Vue = require('vue');

/// [GLOBAIS] - Dados globais
//Vue.prototype.vgbFiltro    =   {};

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('filtro1-bpms', require('./components/solicitacao/FiltroBPMS.vue').default);

const app = new Vue({
    el: '#app',
});