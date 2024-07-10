window.Vue = require('vue');
// Users Component
const AdminTemplateList = Vue.component('AdminTemplateList', require('../views/admin/template_list.vue'));
const AdminTemplateCreate = Vue.component('AdminTemplateCreate', require('../views/admin/template_create.vue'));
const AdminTemplateEdit = Vue.component('AdminTemplateEdit', require('../views/admin/template_edit.vue'));
// const UserDetail = Vue.component('UserDetail', require('../views/users/detail.vue'));

export default [
    // users route
    {name: 'AdminTemplateList', path: '/admin/template-list', component: AdminTemplateList},
    {name: 'AdminTemplateCreate', path: '/admin/template-create', component: AdminTemplateCreate},
    {name: 'AdminTemplateEdit', path: '/admin/template-edit/:id', component: AdminTemplateEdit},
    // {name: 'UserDetail', path: '/user-detail/:id', component: UserDetail},    

];
