<template>
    <div class="input-group bg-light">
        <input 
            v-model="content" @keyup.enter="sendMessage"
            type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-0 border-0 py-4 bg-light">
        <div class="input-group-append">
        <button 
            @click="sendMessage"
            id="button-addon2" type="submit" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</template>
<script>
    export default {
        data: () => ({
            content: ''
        }),
        methods: {
            sendMessage() {
                if (this.content === '')
                    return;

                this.$store.dispatch("POST_MESSAGE", { 
                    conversationId: this.$route.params.id,
                    content: this.content
                })
                    .then(() => {
                        this.content = ''
                    });
            }
        }
    }
</script>