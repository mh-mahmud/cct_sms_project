window.Vue = require('vue');
// Inbound Component
const OutboundList = Vue.component('OutboundList', require('../views/outbound/list.vue'));

export default [
    // inbound route
    {name: 'OutboundList', path: '/outbound-list', component: OutboundList},
];