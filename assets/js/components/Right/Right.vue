<template>
    <div class="col-7 px-0">
      <div class="px-4 py-5 chat-box bg-white" ref="chatBox">
        <template v-for="(message, index, key) in messages">
          <Message :message="message"/>
        </template> 
      </div>
      <Input />
    </div>
</template>

<script>
    import Message from './Message.vue';
    import Input from './Input.vue';

    export default {
        components: { Message, Input },
        computed: {
            messages() {
                return this.$store.getters.MESSAGES;
            }
        },
        methods: {
            scrollDown() {
                let chatBox = this.$refs.chatBox;
                chatBox.scrollTo(0, chatBox.scrollHeight);
            }
        },  
        mounted() {
            this.$store.dispatch('GET_MESSAGES', this.$route.params.id);
        },
        watch: {
            messages: {
                handler() {
                    this.$nextTick(() => {
                        this.scrollDown();
                    })
                },
                deep: true
            }
        }
    }
</script>