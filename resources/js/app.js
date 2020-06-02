/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
const VueRouter = require('vue-router').default;
const Vuex = require('vuex').default;
window.Vue.use(VueRouter);
window.Vue.use(Vuex);

// Components
const ExampleComponent = require('./components/ExampleComponent.vue').default;
const LoginComponent = require('./components/LoginComponent.vue').default;
const HomeComponent = require('./components/HomeComponent.vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', ExampleComponent);
Vue.component('login-component', LoginComponent);
Vue.component('home-component', HomeComponent);

/**
 * Creating routes for Vue SPA
 */
const routes = [
    { path: '/example', component: ExampleComponent },
    { path: '/login', component: LoginComponent }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

/**
 * Implementing Vuex store
 */
const store = new Vuex.Store({
    state: {
        messages: [
            'first message'
        ]
    },
    mutations: {
        ADD_MESSAGE (state, payload) {
            state.messages.push(payload.message);
        }
    },
    actions: {
        addMessageAction({ commit }, message) {
            commit('ADD_MESSAGE', { message })
        }
    }
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store
});
