window.Vue = require('vue');
// Inbound Component
const TextList = Vue.component('TextList', require('../views/text/list.vue'));

export default [
    // inbound route
    {name: 'InboundList', path: '/inbound-list', component: TextList},
];