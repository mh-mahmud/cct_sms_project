window.Vue = require('vue');
// Schedule Component
const AdminScheduleList = Vue.component('AdminScheduleList', require('../views/admin/schedule_list.vue'));
const AdminScheduleDetail = Vue.component('AdminScheduleDetail', require('../views/admin/schedule_detail.vue'));

export default [
    // users route
    {name: 'AdminScheduleList', path: '/admin/schedule-list', component: AdminScheduleList},
    {name: 'AdminScheduleDetail', path: '/admin/schedule-detail/:id', component: AdminScheduleDetail},
];
