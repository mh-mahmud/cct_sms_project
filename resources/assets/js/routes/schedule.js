window.Vue = require('vue');
// Users Component
const ScheduleList = Vue.component('ScheduleList', require('../views/schedule/list.vue'));
const ScheduleDetail = Vue.component('ScheduleDetail', require('../views/schedule/detail.vue'));

export default [
    // users route
    {name: 'ScheduleList', path: '/schedule-list', component: ScheduleList},
    {name: 'ScheduleDetail', path: '/schedule-detail/:id', component: ScheduleDetail},
];
