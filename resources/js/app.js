import axios from 'axios';
import Vue from 'vue';
/*import VueRouter from 'vue-router';
import store from './store/store';
import BootstrapVue from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'*/

window.axios = axios;
//console.log(window.axios.defaults.headers.common);

window.Vue = require('vue');

//Vue.use(VueRouter);
//Vue.use(BootstrapVue);

/*const routes = [
    { path: '/', component: require('./components/sample/sample.vue') .default },
];*/

/*const router = new VueRouter({
    //mode:'history',
    routes
});*/

var $ = jQuery;
for ( var k in ufe_vueobject ) {
    new Vue({
        //store: store,
        //router:router,
        el: '#' + k,//#fael_app
        data: fael_vuedata.data,
        methods: fael_vuedata.methods,
        created: fael_vuedata.created,
        mounted: fael_vuedata.mounted
    });
}
/*const fael_app = */

