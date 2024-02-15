import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    activeConversationId: -1,
    conversations: [],
    messages: [],
    sseConfig: {}
};

const slice = createSlice({
    name: 'conversations',
    initialState,
    reducers: {
        setConversations: (state, { payload }) => {
            state.conversations = payload;
        },
        setMessages: (state, { payload }) => {
            state.messages = payload.messages;
            state.activeConversationId = Number(payload.conversationId);
        },
        addMessage: (state, { payload }) => {
            if (state.activeConversationId === payload.conversationId)
                state.messages.push(payload);

            let conversation = state.conversations.find(i => i.conversationId === payload.conversationId) ?? {};
            conversation.content = payload.content;
            conversation.createdAt = payload.createdAt;

            state.conversations = state.conversations.sort((a, b) =>
                new Date(b?.createdAt?.date) - new Date(a?.createdAt?.date))
        },
        setSseConfig: (state, { payload }) => {
            state.sseConfig = payload;
        }
    }
});

export const { setConversations, setMessages, addMessage, setSseConfig } = slice.actions;

export default slice.reducer;
