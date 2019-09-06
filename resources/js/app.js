/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');


import VueRouter from 'vue-router';
import VueAxios from 'vue-axios';
import axios from 'axios';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('user1-component' , require('./components/user1Component.vue'));
Vue.component('user2-component' , require('./components/user2Component.vue'));
import user1Component from './components/user1Component.vue';
import user2Component from './components/user2Component.vue';
import exampleComponent from './components/ExampleComponent.vue';
var routes = [
{
    name: "example",
        path: "/testsocket",
    component: exampleComponent
},
{
    name:"user1",
    path:"/user1",
    component: user1Component
},
{
    name: "user2",
    path: "/user2",
    component: user2Component
}];
const router = new VueRouter({ mode: 'history', routes: routes });
const app = new Vue({
    router
}).$mount('#app')