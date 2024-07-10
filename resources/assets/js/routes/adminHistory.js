window.Vue = require('vue');
// History Component
const AdminHistoryList = Vue.component('AdminHistoryList', require('../views/admin/history_list.vue'));
const AdminHistoryDetail = Vue.component('AdminHistoryDetail', require('../views/admin/history_detail.vue'));

export default [
    // users route
    {name: 'AdminHistoryList', path: '/admin/history-list', component: AdminHistoryList},
    {name: 'AdminHistoryDetail', path: '/admin/history-detail/:id', component: AdminHistoryDetail},
];
