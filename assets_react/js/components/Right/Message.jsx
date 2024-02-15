import React from "react";

const Message = ({message}) => {
    const time = () => {
        return message.createdAt != null
        ? new Date(message.createdAt?.date).toLocaleString()
        : ''
    };

    function avatar() {
        return `build/images/${message.userAvatar}`;
    }

    return (
        <div>
            {!message?.mine ? (
            <div className="message-item media mb-3">
                <img src={avatar()} alt="user" width="50" className="avatar"/>
                <div className="media-body ml-3">
                    <div className="bg-light rounded py-2 px-3 mb-2">
                        <p className="text-small mb-0 text-muted">{message?.content}</p>
                    </div>
                    <p className="small text-muted">{time()}</p>
                </div>
            </div>
            ) : (
            <div className="message-item media ml-auto mb-3">
                <div className="media-body">
                    <div className="bg-primary rounded py-2 px-3 mb-2">
                        <p className="text-small mb-0 text-white">{message?.content}</p>
                    </div>
                    <p className="small text-muted">{time()}</p>
                </div>
                <img src={avatar()} alt="user" width="50" className="avatar ml-3"/>
            </div>
            )}
        </div>
    );
}

export default Message;