window.Vue = require('vue');
// Users Component
const UserList = Vue.component('UserList', require('../views/users/list.vue'));
const UserCreate = Vue.component('UserCreate', require('../views/users/create.vue'));
const UserEdit = Vue.component('UserEdit', require('../views/users/edit.vue'));
const UserDetail = Vue.component('UserDetail', require('../views/users/detail.vue'));
const UserResetPassword = Vue.component('UserResetPassword', require('../views/users/reset_password.vue'));

export default [
    // users route
    {name: 'UserList', path: '/user-list', component: UserList},
    {name: 'UserCreate', path: '/user-create', component: UserCreate},
    {name: 'UserEdit', path: '/user-edit/:id', component: UserEdit},
    {name: 'UserDetail', path: '/user-detail/:id', component: UserDetail},
    {name: 'UserResetPassword', path: '/user-reset-password', component: UserResetPassword},

];
