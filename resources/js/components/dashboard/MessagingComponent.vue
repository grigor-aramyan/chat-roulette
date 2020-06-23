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
        mounted() {
            window.RD.ref('chatMessage').on('value', (snapshot) => {
                const snapData = snapshot.val();

                if (this.currentUser && this.pairingUser) {
                    if ((snapData.sendedTo == this.currentUser.id) &&
                        (snapData.sendedFrom == this.pairingUser.id)) {
                            const payload = {
                                content: snapData.content,
                                addressed_to: snapData.sendedTo,
                                user_id: snapData.sendedFrom,
                                created_at: snapData.date,
                                id: snapData.id
                            };

                            this.addNewChatMessage(payload);
                        }
                }

            });

            window.RD.ref('friendingReply').on('value', (snapshot) => {
                const snapData = snapshot.val();

                if (this.currentUser && this.pairingUser) {
                    if ((snapData.friendingTo == this.currentUser.id) &&
                        (snapData.friendingFrom == this.pairingUser.id)) {
                            if (snapData.status == 1) {
                                if (this.friendingDecision == 1) {
                                    this.addConnection(this.pairingUser.id);
                                    this.error = 'you\'ve just become friends))';
                                } else if (this.friendingDecision == null) {
                                    this.error = 'your connection wants to become friends with you)';
                                    this.friendingReply = 1;
                                }
                            } else if (snapData.status == 0) {
                                this.error = 'pair rejected friending';

                                setTimeout(() => {
                                    
                                    if (this.friendingDecision == 1) {
                                        // TODO: clear connection from backend
                                        // set IDLE mode in backend
                                    }

                                    this.removePairingUser();
                                    this.removePairingUserAnswers();
                                    this.removeChatMessages();
                                    this.setCurrentUserMode('IDLE');

                                    this.$emit('friending-rejected');
                                }, 10000);
                            }
                    }
                }
            });
        },

        data() {
            return {
                section: MESSAGING,
                currentMessage: '',
                friendingReply: null,
                friendingDecision: null,
                error: ''
            }
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser,
                pairingUser: state => state.pairingUser.pairingUser,
                chatMessages: state => state.pairingUser.chatMessages
            })
        },

        methods: {
            endChatting() {
                this.section = END_OF_MESSAGING;
            },
            becomeFriends() {
                if (this.friendingReply == 1) {
                    this.addConnection(this.pairingUser.id);

                    // TODO: send notif to firebase RD
                    // so paired user can know you become friends
                    window.RD.ref('friendingReply').set({
                        friendingFrom: this.currentUser.id,
                        friendingTo: this.pairingUser.id,
                        status: 1
                    });
                } else if (this.friendingReply == null) {
                    const becomeFriendsUri = `${API_BASE_URI}/connections`;

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
                        connected_to: this.pairingUser.id
                    };

                    axios.post(becomeFriendsUri, payload, config)
                        .then(res => {
                            if (res.status == 201) {

                                // TODO: send notif to firebase RD
                                // so paired user can know you become friends
                                window.RD.ref('friendingReply').set({
                                    friendingFrom: this.currentUser.id,
                                    friendingTo: this.pairingUser.id,
                                    status: 1
                                });

                                this.friendingDecision = 1;
                                this.error = '';
                            } else if (res.status == 200) {
                                this.error = 'already connected. Try to refresh page, please';
                            } else {
                                this.error = 'something weird happened. Try one more time, please';
                            }
                        })
                        .catch(err => {
                            this.error = err.message;
                        });
                    }
                
            },
            stayAnonimous() {
                window.RD.ref('friendingReply').set({
                    friendingFrom: this.currentUser.id,
                    friendingTo: this.pairingUser.id,
                    status: 0
                });

                // TODO: set IDLE mode in backend

                this.removePairingUser();
                this.removePairingUserAnswers();
                this.removeChatMessages();
                this.setCurrentUserMode('IDLE');

                this.$emit('friending-rejected');
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
                                window.RD.ref('chatMessage').set({
                                    content: res.data.content,
                                    date: res.data.created_at,
                                    id: res.data.id,
                                    sendedFrom: res.data.user_id,
                                    sendedTo: res.data.addressed_to
                                });

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
                'addNewChatMessage',
                'addConnection',
                'removePairingUser',
                'removePairingUserAnswers',
                'setCurrentUserMode',
                'removeChatMessages'
            ])
        }
    }
</script>