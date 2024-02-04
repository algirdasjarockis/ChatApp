export default {
    state: {
        activeConversationId: -1,
        conversations: [],
        messages: [],
        sseConfig: {}
    },
    getters: {
        CONVERSATIONS: state => state.conversations.sort((a, b) =>
            new Date(b?.createdAt?.date) - new Date(a?.createdAt?.date)),
        MESSAGES: state => state.messages,
        SSE_CONFIG: state => state.sseConfig
    },
    mutations: {
        SET_CONVERSATIONS: (state, payload) => {
            state.conversations = payload;
        },
        SET_MESSAGES: (state, { payload, conversationId }) => {
            state.messages = payload;
            state.activeConversationId = Number(conversationId);
        },
        ADD_MESSAGE: (state, payload) => {
            if (state.activeConversationId === payload.conversationId)
                state.messages.push(payload);

            let conversation = state.conversations.find(i => i.conversationId === payload.conversationId) ?? {};
            conversation.content = payload.content;
            conversation.createdAt = payload.createdAt;
        },
        SET_SSE_CONFIG: (state, payload) => {
            state.sseConfig = payload;
        }
    },
    actions: {
        GET_CONVERSATIONS: ({ commit }) => {
            return fetch('/conversations')
                .then(result => result.json())
                .then(result => {
                    commit("SET_SSE_CONFIG", { hubUrl: result.hubUrl, topics: result.topics });
                    commit("SET_CONVERSATIONS", result.conversations);
                })
        },
        GET_MESSAGES: ({ commit }, conversationId) => {
            return fetch(`/messages/${conversationId}`)
                .then(result => result.json())
                .then(result => {
                    commit("SET_MESSAGES", { payload: result, conversationId });
                })
        },
        GET_MESSAGE: ({ commit }, message) => {
            commit("ADD_MESSAGE", message);
        },
        POST_MESSAGE: ({ commit }, { conversationId, content }) => {
            let formData = new FormData();
            formData.append('content', content);
            return fetch(`/newMessage/${conversationId}`, {
                method: 'POST',
                body: formData
            })
                .then(result => result.json())
                .then(result => {
                    commit("ADD_MESSAGE", result);
                })
        }
    }
}