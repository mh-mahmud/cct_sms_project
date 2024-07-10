window.Vue = require('vue');
// Campaign Profile component
const CampaignProfileList = Vue.component('CampaignProfileList', require('../views/campaign/profile/list.vue'));
const CampaignProfileCreate = Vue.component('CampaignProfileCreate', require('../views/campaign/profile/create.vue'));
const CampaignProfileEdit = Vue.component('CampaignProfileEdit', require('../views/campaign/profile/edit.vue'));
const CampaignProfileDetail = Vue.component('CampaignProfileDetail', require('../views/campaign/profile/detail.vue'));
const emailList = Vue.component('emailList', require('../views/campaign/profile/email_list.vue'));

export default [
    // campaign profile route
    {name: 'CampaignProfileList', path: '/campaign-profile-list', component: CampaignProfileList},
    {name: 'CampaignProfileCreate', path: '/campaign-profile-create', component: CampaignProfileCreate},
    {name: 'CampaignProfileEdit', path: '/campaign-profile-edit/:id', component: CampaignProfileEdit},
    {name: 'CampaignProfileDetail', path: '/campaign-profile-detail/:id', component: CampaignProfileDetail},
    {name: 'emailList', path: '/email-list/:id', component: emailList},

];