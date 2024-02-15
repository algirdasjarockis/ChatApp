import { configureStore } from '@reduxjs/toolkit'
import conversationReducer from './reducers/conversation'

const store = configureStore({
    reducer: {
        conversation: conversationReducer
    }
});

export default store;