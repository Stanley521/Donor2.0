<template>
    <ul class="chat">
        <li class="left clearfix" v-for="message in messages" v-bind:class="{ userChat: (message.user.id == user.id) }">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font">
                        {{ message.user.name }}
                    </strong>
                </div>
                <p>
                    {{ message.message }}
                </p>
            </div>
        </li>
        {{ log(user)}}
    </ul>
</template>

<script>
    export default {
        props: ['user', 'messages', 'friendId'],
        methods: {
            debug (event) {
                console.log( user)
            },
            log( message) {
                console.log(message);
            }
        },
        created() {
            this.$emit('initialized', {
                user: this.user,
                friend_id: this.friendId
            });
        }
        
    };
    
</script>
