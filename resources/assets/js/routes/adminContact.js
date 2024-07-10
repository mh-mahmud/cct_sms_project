window.Vue = require('vue');
// Users Component
const AdminContactList = Vue.component('AdminContactList', require('../views/admin/contact_list.vue'));
const AdminContactGroupList = Vue.component('AdminContactGroupList', require('../views/admin/contact_group_list.vue'));
const AdminContactCreate = Vue.component('AdminContactCreate', require('../views/admin/contact_create.vue'));
const AdminContactEdit = Vue.component('AdminContactEdit', require('../views/admin/contact_edit.vue'));
const AdminContactImport = Vue.component('AdminContactImport', require('../views/admin/contact_import.vue'));

export default [
    // users route
    {name: 'AdminContactList', path: '/admin/contact-list', component: AdminContactList},
    {name: 'AdminContactGroupList', path: '/admin/contact-group-list/:group_id', component: AdminContactGroupList},
    {name: 'AdminContactCreate', path: '/admin/contact-create', component: AdminContactCreate},
    {name: 'AdminContactEdit', path: '/admin/contact-edit//:id', component: AdminContactEdit},
    {name: 'AdminContactImport', path: '/admin/contact-import/', component: AdminContactImport},

];
