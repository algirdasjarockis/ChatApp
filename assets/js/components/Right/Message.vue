<template>
    <!-- Received Message-->
    <div v-if="!message?.mine" class="media w-50 mb-3">
        <img src="https://bootstrapious.com/i/snippets/sn-chat/avatar.svg" alt="user" width="50" class="rounded-circle">
        <div class="media-body ml-3">
            <div class="bg-light rounded py-2 px-3 mb-2">
                <p class="text-small mb-0 text-muted">{{ message?.content }}</p>
            </div>
            <p class="small text-muted">{{ time }}</p>
        </div>
    </div>
    <div v-else class="media w-50 ml-auto mb-3">
        <div class="media-body">
            <div class="bg-primary rounded py-2 px-3 mb-2">
                <p class="text-small mb-0 text-white">{{  message?.content }}</p>
            </div>
            <p class="small text-muted">{{ time }}</p>
        </div>
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
        }
    }
</script>