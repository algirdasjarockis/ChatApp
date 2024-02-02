<template>
<div class="container py-5 px-4">
  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <Left />
    <!-- Chat Box-->
    <router-view :key="$route.fullPath"></router-view>
  </div>
</div>
</template>

<script>
    import Left from "./components/Left/Left"
    import Right from "./components/Right/Right"
    import { mapGetters } from 'vuex';

    export default {
        components: { Left, Right },
        computed: {
            ...mapGetters(["SSE_CONFIG"])
        },
        watch: {
            SSE_CONFIG(config) {
                let currentUserId = this.$store.state.userId;
                let hub = new URL(config.hubUrl, window.origin);

                config.topics.forEach((topic) => hub.searchParams.append('topic', topic));
                console.log(`Listening on ${hub}, topics: ${config.topics.join(', ')}`);

                const eventSource = new EventSource(hub, { withCredentials: true });
                eventSource.onmessage = event => {
                    let message = JSON.parse(event.data);

                    if (message?.userId == currentUserId) {
                        return;
                    }

                    message.mine = false;

                    this.$store.dispatch('GET_MESSAGE', message);
                    console.log(`Mercure event: ${event.data}`);
                }
            }
        }
    }
</script>