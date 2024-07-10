window.Vue = require('vue');

const Dashboard = Vue.component('Dashboard', require('../views/dashboard/dashboard.vue'));

export default [
    {name: 'Dashboard', path: '/', component: Dashboard},
    {name: 'Dashboard', path: '/dashboard', component: Dashboard},
];