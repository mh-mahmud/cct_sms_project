window.Vue = require('vue');
const GroupDetail = Vue.component('GroupDetail', require('../views/group/detail.vue'));
const GroupCreate = Vue.component('GroupCreate', require('../views/group/create.vue'));
const GroupEdit = Vue.component('GroupEdit', require('../views/group/edit.vue'));
const GroupList = Vue.component('GroupList', require('../views/group/list.vue'));

export default [
    {name: 'GroupCreate', path: '/group-create/', component: GroupCreate},
    {name: 'GroupList', path: '/group-list/', component: GroupList},
    {name: 'GroupEdit', path: '/group-edit/:id', component: GroupEdit},
    {name: 'GroupDetail', path: '/group-detail/:id', component: GroupDetail},
];
