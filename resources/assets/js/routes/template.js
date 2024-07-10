window.Vue = require('vue');
// Users Component
const TemplateList = Vue.component('TemplateList', require('../views/template/list.vue'));
const TemplateCreate = Vue.component('TemplateCreate', require('../views/template/create.vue'));
const TemplateEdit = Vue.component('TemplateEdit', require('../views/template/edit.vue'));
// const UserDetail = Vue.component('UserDetail', require('../views/users/detail.vue'));

export default [
    // users route
    {name: 'TemplateList', path: '/template-list', component: TemplateList},
    {name: 'TemplateCreate', path: '/template-create', component: TemplateCreate},
    {name: 'TemplateEdit', path: '/template-edit/:id', component: TemplateEdit},
    // {name: 'UserDetail', path: '/user-detail/:id', component: UserDetail},    

];
