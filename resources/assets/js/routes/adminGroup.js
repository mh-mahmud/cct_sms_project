window.Vue = require('vue');
const AdminGroupDetail = Vue.component('AdminGroupDetail', require('../views/admin/group_detail.vue'));
const AdminGroupCreate = Vue.component('AdminGroupCreate', require('../views/admin/group_create.vue'));
const AdminGroupEdit = Vue.component('AdminGroupEdit', require('../views/admin/group_edit.vue'));
const AdminGroupList = Vue.component('AdminGroupList', require('../views/admin/group_list.vue'));

export default [
    {name: 'AdminGroupCreate', path: '/admin/group-create/', component: AdminGroupCreate},
    {name: 'AdminGroupList', path: '/admin/group-list/', component: AdminGroupList},
    {name: 'AdminGroupEdit', path: '/admin/group-edit/:id', component: AdminGroupEdit},
    {name: 'AdminGroupDetail', path: '/admin/group-detail/:id', component: AdminGroupDetail},
];
