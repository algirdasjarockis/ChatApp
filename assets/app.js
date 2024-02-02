/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './js/store/store';
import App from './js/App';
import Blank from './js/components/Right/Blank';
import Right from './js/components/Right/Right';

Vue.use(VueRouter);

const routes = [
    {
        name: 'blank',
        path: '/',
        component: Blank
    },
    {
        name: 'conversation',
        path: '/conversation/:id',
        component: Right
    }
];

const router = new VueRouter({
    mode: "abstract",
    routes
});

store.state.userId = document.querySelector('#app').dataset.user;

new Vue({
    store,
    router,
    render: h => h(App)
}).$mount('#app');

router.replace('')