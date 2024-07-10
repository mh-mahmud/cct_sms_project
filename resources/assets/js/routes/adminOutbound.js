window.Vue = require('vue');
// Inbound Component
const AdminOutboundList = Vue.component('AdminOutboundList', require('../views/admin/outboundList.vue'));

export default [
    // inbound route
    {name: 'AdminOutboundList', path: '/admin/outbound-list', component: AdminOutboundList},
];