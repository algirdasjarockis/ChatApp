import React from 'react';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import App from "./js/App";
import store from './js/store';
import { MemoryRouter } from 'react-router';

const element = document.getElementById('app');
const userId = element.dataset.user;
const root = createRoot(element);

root.render(
    <Provider store={store}>
        <MemoryRouter>
            <App userId={userId} />
        </MemoryRouter>
    </Provider>
);
