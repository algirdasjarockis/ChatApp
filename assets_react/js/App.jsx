import React, { useEffect } from "react";
import Left from "./components/Left/Left";
import Right from "./components/Right/Right";
import Blank from "./components/Right/Blank";
import { Route, Routes } from "react-router-dom"
import { useDispatch, useSelector } from "react-redux";
import { addMessage } from "./reducers/conversation";

const App = ({ userId }) => {
    const sseConfig = useSelector((state) => state.conversation.sseConfig);
    const dispatch = useDispatch();

    useEffect(() => {
        if (Object.keys(sseConfig).length == 0)
            return;

        let currentUserId = userId;
        let hub = new URL(sseConfig.hubUrl, window.origin);

        sseConfig.topics.forEach((topic) => hub.searchParams.append('topic', topic));
        console.log(`Listening on ${hub}, topics: ${sseConfig.topics.join(', ')}`);

        const eventSource = new EventSource(hub, { withCredentials: true });
        eventSource.onmessage = event => {
            let message = JSON.parse(event.data);

            if (message?.userId == currentUserId) {
                return;
            }

            message.mine = false;

            dispatch(addMessage(message));
            console.log(`Mercure event: ${event.data}`);
        }
    }, [sseConfig.hubUrl, sseConfig.topics]);

    return (
        <div className="container py-5 px-4">
            <div className="row rounded-lg overflow-hidden shadow">
                <Left />
                <Routes>
                    <Route path="/" Component={Blank}/>
                    <Route path="/conversation/:id" Component={Right}/>
                </Routes>
            </div>
        </div>
    )
}

export default App;