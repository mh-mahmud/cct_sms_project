window.Vue = require('vue');
// Inbound Component
const AdminTextList = Vue.component('AdminTextList', require('../views/admin/textList.vue'));

export default [
    // inbound route
    {name: 'AdminInboundList', path: '/admin/inbound-list', component: AdminTextList},
];