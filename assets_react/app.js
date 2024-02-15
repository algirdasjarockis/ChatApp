import React from 'react';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import App from "./js/App";
import store from './js/store';
import { MemoryRouter } from 'react-router';

const element = document.getElementById('app');
const userId = element.dataset.user;
const root = createRoot(element);

const bodyEl = document.getElementsByTagName('body')[0];
bodyEl.classList.add('react-bg');

const madeWithEl = document.getElementsByClassName('made-with')[0];
madeWithEl.innerText = "Made with React";

root.render(
    <Provider store={store}>
        <MemoryRouter>
            <App userId={userId} />
        </MemoryRouter>
    </Provider>
);
