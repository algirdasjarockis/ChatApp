import React, { createRef, useEffect, useRef } from "react";
import Message from "./Message";
import Input from "./Input";
import { useParams } from "react-router";
import { fetchMessages } from "../../actions/conversation";
import { useDispatch, useSelector } from "react-redux";

export const Right = () => {
    const chatBox = useRef(null);
    const { id: conversationId} = useParams();
    const dispatch = useDispatch();
    const messages = useSelector((state) => state.conversation.messages);

    // reload messages when changing conversation
    useEffect(() => {
        fetchMessages(dispatch, conversationId);
    }, [conversationId]);

    // scroll down when messages appear
    useEffect(() => {
        let el = chatBox.current;
        el.scrollTo(0, el.scrollHeight);
    }, [messages?.length]);

    return (
        <div className="col-7 px-0">
            <div className="px-4 py-5 chat-box bg-white" ref={chatBox}>
            { messages.map((item, index) => {
                return <Message message={item} key={index}/>
            })}
            </div>
            <Input conversationId={conversationId} />
        </div>
    );
}

export default Right;