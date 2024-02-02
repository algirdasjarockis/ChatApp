<template>
    <div v-if="!message?.mine" class="message-item media mb-3">
        <img :src="avatar" alt="user" width="50" class="avatar">
        <div class="media-body ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
                <p class="text-small mb-0 text-muted">{{ message?.content }}</p>
            </div>
            <p class="small text-muted">{{ time }}</p>
        </div>
    </div>
    <div v-else class="message-item media ml-auto mb-3">
        <div class="media-body">
            <div class="bg-primary rounded py-2 px-3 mb-2">
                <p class="text-small mb-0 text-white">{{  message?.content }}</p>
            </div>
            <p class="small text-muted">{{ time }}</p>
        </div>
        <img :src="avatar" alt="user" width="50" class="avatar ml-3">
    </div>
</template>
<script>
    export default {
        props: {
            message: Object
        },
        computed: {
            time() {
                return new Date(this.message.createdAt?.date).toLocaleString();
            },

            avatar() {
                return `build/images/${this.message.userAvatar}`;
            }
        }
    }
</script>