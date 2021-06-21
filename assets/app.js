
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from "./store/store";

import App from "./components/App.vue";
import Blank from './components/right/Blank';
import Right from './components/right/Right';

Vue.use(VueRouter);

const routes = [
    {
        name: 'blank',
        path: '/',
        component: Blank
    },
    {
        name:'conversation',
        path:'/conversat/:id',
        component: Right
    }
];

const router = new VueRouter({
    mode:"abstract",
    routes
})

store.commit("SET_USERNAME", document.querySelector('#app').dataset.username);

new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app')

router.replace('/');