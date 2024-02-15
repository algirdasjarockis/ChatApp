import React from "react";
import { NavLink } from "react-router-dom";

const Conversation = ({conversation}) => {

    const date = () => {
        return conversation.createdAt != null
        ? new Date(conversation?.createdAt?.date).toLocaleTimeString()
        : ''
    };

    return (
        <NavLink to={`/conversation/${conversation.conversationId}`} 
            className="list-group-item list-group-item-action rounded-0">
            <div className="media">
                <img src={`build/images/${conversation.avatarFileName}`} alt="user" width="50" className="avatar"/>
                <div className="media-body ml-4">
                    <div className="d-flex align-items-center justify-content-between mb-1">
                        <h6 className="mb-0">{conversation.displayName || conversation.username}</h6>
                        <small className="small font-weight-bold">{date()}</small>
                    </div>
                    <p className="font-italic mb-0 text-small">{conversation.content}</p>
                </div>
            </div>
        </NavLink>
    )
}

export default Conversation;