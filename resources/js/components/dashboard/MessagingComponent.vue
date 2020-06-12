<template>
    <div>
        <h1>Messaging</h1>

        <div v-if="chatMessages">
            <ul>
                <li v-for="msg in chatMessages" :key="msg.id">
                    {{ msg.content }}
                </li>
            </ul>
        </div>

        <input v-model="currentMessage" placeholder="Type a message to send..." />
        <button @click="send" class="btn btn-success">Send</button>

        <br />
        <p v-if="error" style="color:red;">{{ error }}</p>
    </div>
</template>

<script>
    import axios from 'axios';
    import {
        mapState,
        mapActions
    } from 'vuex';

    import {
        API_BASE_URI,
        CR_USER_TOKEN
    } from '../../statics';

    export default {
        data() {
            return {
                currentMessage: '',
                error: ''
            }
        },

        computed: {
            ...mapState({
                pairingUser: state => state.pairingUser.pairingUser,
                chatMessages: state => state.pairingUser.chatMessages
            })
        },

        methods: {
            send() {
                if (this.currentMessage) {
                    const storeMessageUri = `${API_BASE_URI}/messages`;

                    const token = localStorage.getItem(CR_USER_TOKEN);
                    if (!token) {
                        return this.$router.replace('/login');
                    }

                    const config = {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': token
                        }
                    };

                    const payload = {
                        content: this.currentMessage,
                        addressed_to: this.pairingUser.id
                    };

                    axios.post(storeMessageUri, payload, config)
                        .then(res => {
                            if (res.status == 201) {
                                this.addNewChatMessage(res.data);

                                this.error = '';
                                this.currentMessage = '';
                            } else {
                                this.error = 'something weird happened. Try one more time, please';
                            }
                        })
                        .catch(err => {
                            if (err.response.status == 400) {
                                this.error = err.response.data.msg;
                            } else {
                                this.error = err.message;
                            }
                        });

                } else {
                    this.error = 'can\'t send empty message';
                }
            },
            ...mapActions([
                'addNewChatMessage'
            ])
        }
    }
</script>