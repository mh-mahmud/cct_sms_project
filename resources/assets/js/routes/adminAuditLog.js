window.Vue = require('vue');
// Audit Log Component
const AdminAuditLogList = Vue.component('AdminAuditLogList', require('../views/admin/auditLogList.vue'));

export default [
    // audit log route
    {name: 'AdminAuditLogList', path: '/admin/audit-log-list', component: AdminAuditLogList},
];