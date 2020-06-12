<template>
    <div>
        <h1>Messaging</h1>

        <div v-if="section == 'MESSAGING'">

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

            <button @click="endChatting">Close chat</button>
        
        </div>

        <div v-if="section == 'END_OF_MESSAGING'">
            <div>
                <button @click="becomeFriends" class="btn btn-success">Become friends</button>
                <button @click="stayAnonimous" class="btn btn-danger">Stay anonymous</button>
            </div>

            <br />
        </div>

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

    const MESSAGING = 'MESSAGING';
    const END_OF_MESSAGING = 'END_OF_MESSAGING';

    export default {
        data() {
            return {
                section: MESSAGING,
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
            endChatting() {
                this.section = END_OF_MESSAGING;
            },
            becomeFriends() {
                console.log('become friends');
            },
            stayAnonimous() {
                console.log('stay anonym');
            },
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

                                // TODO: send added message to firebase RD
                                // so paired user can receive it realtime too

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