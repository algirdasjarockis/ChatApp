import React, { useEffect } from "react";
import { fetchConversations } from "../../actions/conversation";
import Conversation from "./Conversation";
import { useDispatch, useSelector } from "react-redux";

export const Left = () => {
    const dispatch = useDispatch();
    const conversations = useSelector((state) => state.conversation.conversations);

    useEffect(() => {
        fetchConversations(dispatch);
    }, []);

    return (
        <div className="col-5 px-0">
            <div className="bg-white">           
                <div className="bg-gray px-4 py-2 bg-light">
                    <p className="h5 mb-0 py-1">Recent</p>
                </div>
            
                <div className="messages-box">
                    <div className="list-group rounded-0">
                    {
                        conversations.map((conversation, index) => {
                            return <Conversation conversation={conversation} key={index}/>
                        })
                    }
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Left;