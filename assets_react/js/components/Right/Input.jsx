import React, { useState } from "react";
import { postMessage } from "../../actions/conversation";
import { useDispatch } from "react-redux";

const Input = ({ conversationId }) => {
    const [content, setContent] = useState('');
    const dispatch = useDispatch();

    function executePost(event) {
        event.preventDefault();

        if (content.trim() === '')
            return;

        postMessage(dispatch, { conversationId, content});
        setContent('');
    }

    function handleKeyPress(event) {
        if (event.key == "Enter")
            executePost(event);
    }

    return (
        <div className="input-group bg-light">
            <input type="text" placeholder="Type a message" className="form-control rounded-0 border-0 py-4 bg-light"
                value={content}
                onChange={e => setContent(e.target.value)}
                onKeyUp={handleKeyPress}/>
            <div className="input-group-append">
            <button 
                type="button" className="btn btn-link"
                onClick={executePost}> <i className="fa fa-paper-plane"></i></button>
            </div>
        </div>
    );
}

export default Input;