window.Vue = require('vue');
// Users Component
const ContactList = Vue.component('ContactList', require('../views/contacts/list.vue'));
const ContactGroupList = Vue.component('ContactList', require('../views/contacts/group_list.vue'));
const ContactCreate = Vue.component('ContactCreate', require('../views/contacts/create.vue'));
const ContactEdit = Vue.component('ContactEdit', require('../views/contacts/edit.vue'));
const ContactImport = Vue.component('ContactImport', require('../views/contacts/import.vue'));

export default [
    // users route
    {name: 'ContactList', path: '/contact-list', component: ContactList},
    {name: 'ContactGroupList', path: '/contact-group-list/:group_id', component: ContactGroupList},
    {name: 'ContactCreate', path: '/contact-create', component: ContactCreate},
    {name: 'ContactEdit', path: '/contact-edit//:id', component: ContactEdit},
    {name: 'ContactImport', path: '/contact-import/', component: ContactImport},

];
