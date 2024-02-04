import { createStore } from 'vuex';
import conversation from "./modules/conversation";

export default new createStore({
    modules: {
        conversation
    }
})
