import { addMessage, setConversations, setMessages, setSseConfig } from "../reducers/conversation"

export const fetchConversations = (dispatch) => {
    return fetch('/conversations')
        .then(result => result.json())
        .then(result => {
            dispatch(setConversations(result.conversations));
            dispatch(setSseConfig({ hubUrl: result.hubUrl, topics: result.topics }));
        })
}

export const fetchMessages = (dispatch, conversationId) => {
    return fetch(`/messages/${conversationId}`)
        .then(result => result.json())
        .then(result => {
            dispatch(setMessages({ messages: result, conversationId }));
        })
}

export const postMessage = (dispatch, { conversationId, content }) => {
    let body = new FormData();
    body.append('content', content);

    return fetch(`/newMessage/${conversationId}`, { method: 'POST', body })
        .then(result => result.json())
        .then(result => {
            dispatch(addMessage(result));
        })
}