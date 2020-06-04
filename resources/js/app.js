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
const RegistrationComponent = require('./components/registration/RegistrationComponent.vue').default;
const ConnectionPurposeComponent = require('./components/registration/ConnectionPurposeComponent.vue').default;
const PersonalInfoComponent = require('./components/registration/PersonalInfoComponent.vue').default;
const ThreeQsComponent = require('./components/registration/ThreeQsComponent.vue').default;
const CurrentStatusComponent = require('./components/registration/CurrentStatusComponent.vue').default;
const DashboardComponent = require('./components/DashboardComponent.vue').default

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
Vue.component('registration-component', RegistrationComponent);
Vue.component('connection-purpose-component', ConnectionPurposeComponent);
Vue.component('personal-info-component', PersonalInfoComponent);
Vue.component('three-qs-component', ThreeQsComponent);
Vue.component('current-status-component', CurrentStatusComponent);
Vue.component('dashboard-component', DashboardComponent);

/**
 * Creating routes for Vue SPA
 */
const routes = [
    { path: '/example', component: ExampleComponent },
    { path: '/login', component: LoginComponent },
    { path: '/register', component: RegistrationComponent },
    { path: '/dashboard', component: DashboardComponent }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

// import Vuex modules
const currentUserModule = require('./store/modules/currentUser');
const pairingUserModule = require('./store/modules/pairingUser');

/**
 * Implementing Vuex store
 */
const store = new Vuex.Store({
    modules: {
        currentUser: currentUserModule,
        pairingUser: pairingUserModule
    },
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
