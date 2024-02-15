/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

import store from './js/store/store';
import App from './js/App';
import Blank from './js/components/Right/Blank';
import Right from './js/components/Right/Right';
import { createMemoryHistory, createRouter } from 'vue-router';
import { createApp } from 'vue';
import { h } from 'vue';

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

const madeWithEl = document.getElementsByClassName('made-with')[0];
madeWithEl.innerText = "Made with Vue";
store.state.userId = document.querySelector('#app').dataset.user;

const router = createRouter({
    routes,
    history: createMemoryHistory()
});

const app = createApp({
    store,
    router,
    render: _ => h(App)
});

app.use(store);
app.use(router);

app.mount('#app');