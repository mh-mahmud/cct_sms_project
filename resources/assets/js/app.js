
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap'); 

window.Vue = require('vue');
window.VueRouter=require('vue-router').default;
window.VueAxios=require('vue-axios').default;
window.axios=require('axios').default;
window.moment = require('moment');
// registering Modules
Vue.use(VueRouter,VueAxios, axios);
// vue axios redirect to login page if it is unautenticated
axios.interceptors.response.use((response) => response, (error) => {
    if(error.response.status == 401){ 
        window.location.href = BASE_URL+"login";
    }else{
        throw error;
    }
});

// bootstrap vue
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);

// vue validate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);
// js plugin
Vue.component('JsPlugin', require('./components/JsPlugin.vue'));
// datepicker
import datePicker from 'vue-bootstrap-datetimepicker';
Vue.use(datePicker);
// vue js pagination
Vue.component('VuePagination', require('./components/Pagination.vue'));
// breadcrumb
Vue.component('Breadcrumb', require('./components/Breadcrumb.vue'));


// import routes
import router from './routes/index.js';
import moment from 'moment';
import customPluginLib from './plugins/custom_plugin_lib.js';
const app = new Vue({ router }).$mount('#app')
Vue.use(customPluginLib);




