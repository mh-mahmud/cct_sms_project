window.Vue = require('vue');
// Inbound Component
const AuditLogList = Vue.component('AuditLogList', require('../views/auditLog/list.vue'));

export default [
    // audit log route
    {name: 'AuditLogList', path: '/audit-log-list', component: AuditLogList},
];