window.Vue = require('vue');

const Dashboard = Vue.component('Dashboard', require('../views/admin/dashboard.vue'));

export default [
    {name: 'Dashboard', path: '/admin', component: Dashboard},
    {name: 'Dashboard', path: '/admin/dashboard', component: Dashboard},
];