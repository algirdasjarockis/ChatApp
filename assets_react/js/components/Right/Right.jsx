import React, { useEffect } from "react";
import Message from "./Message";
import Input from "./Input";
import { useParams } from "react-router";
import { fetchMessages } from "../../actions/conversation";
import { useDispatch, useSelector } from "react-redux";

export const Right = () => {
    const { id: conversationId} = useParams();
    const dispatch = useDispatch();
    const messages = useSelector((state) => state.conversation.messages);

    useEffect(() => {
        fetchMessages(dispatch, conversationId);
    }, [conversationId]);

    return (
        <div className="col-7 px-0">
            <div className="px-4 py-5 chat-box bg-white" refxxx="chatBox">
            { messages.map((item, index) => {
                return <Message message={item} key={index}/>
            })}
            </div>
            <Input />
        </div>
    );
}

export default Right;