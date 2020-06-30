<template>
    <div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <h3>Messaging</h3>

                    <div v-if="section == 'MESSAGING'">

                        <div id="messaging-list-div">
                            <ul class="list-group list-group-flush list-group-height">
                                <li :class="{
                                        'right-handed-message-style': msg.user_id == currentUser.id,
                                        'list-group-item': true
                                    }" v-for="msg in chatMessages" :key="msg.id">
                                    <div>
                                        <span class="date-style">{{ msg.created_at.split('T')[1].split('.')[0] }}</span>
                                        {{ msg.content }}
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <input v-model="currentMessage" class="form-control mb-2" type="text" placeholder="Type a message to send..." />
                        <button @click.prevent="send" class="btn btn-success">Send</button>

                        <button @click.prevent="endChatting" class="btn btn-dark">Close chat</button>
                    
                    </div>

                    <div v-if="section == 'END_OF_MESSAGING'">
                        <div>
                            <button @click.prevent="becomeFriends" class="btn btn-success">Become friends</button>
                            <button @click.prevent="stayAnonimous" class="btn btn-danger">Stay anonymous</button>
                        </div>
                    </div>

                    <p v-if="error" class="error mt-2">{{ error }}</p>
                </form>
            </div>
            <div class="col-4"></div>
        </div>
        
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

                                        const clearConnectionUri = `${API_BASE_URI}/connections/${this.pairingUser.id}`;

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

                                        axios.delete(clearConnectionUri, config)
                                            .then(res => {
                                                if (res.status == 200) {
                                                    const updateMyModeUri = `${API_BASE_URI}/mode/update`;

                                                    const payload = {
                                                        mode: 'IDLE'
                                                    };

                                                    axios.post(updateMyModeUri, payload, config)
                                                        .then(res1 => {
                                                            if (res1.status == 200) {
                                                                this.removePairingUser();
                                                                this.removePairingUserAnswers();
                                                                this.removeChatMessages();
                                                                this.setCurrentUserMode(res1.data.mode);

                                                                this.$emit('friending-rejected');
                                                            } else {
                                                                this.error = 'failed updating mode in backend';
                                                                // TODO: implement better error handling scheme
                                                            }
                                                        })
                                                        .catch(err1 => {
                                                            this.error = err1.message;
                                                        });
                                                } else {
                                                    this.error = 'something weird with clearing connection';
                                                }
                                            })
                                            .catch(err => {
                                                this.error = err.message;
                                            });
                                    } else if (this.friendingDecision == null) {
                                        const updateMyModeUri = `${API_BASE_URI}/mode/update`;

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
                                            mode: 'IDLE'
                                        };

                                        axios.post(updateMyModeUri, payload, config)
                                            .then(res1 => {
                                                if (res1.status == 200) {
                                                    this.removePairingUser();
                                                    this.removePairingUserAnswers();
                                                    this.removeChatMessages();
                                                    this.setCurrentUserMode(res1.data.mode);

                                                    this.$emit('friending-rejected');
                                                } else {
                                                    this.error = 'failed updating mode in backend';
                                                    // TODO: implement better error handling scheme
                                                }
                                            })
                                            .catch(err1 => {
                                                this.error = err1.message;
                                            });
                                    }

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
                    this.section = MESSAGING;

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
                                this.section = MESSAGING;
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

                const updateMyModeUri = `${API_BASE_URI}/mode/update`;

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
                    mode: 'IDLE'
                };

                axios.post(updateMyModeUri, payload, config)
                    .then(res1 => {
                        if (res1.status == 200) {
                            this.removePairingUser();
                            this.removePairingUserAnswers();
                            this.removeChatMessages();
                            this.setCurrentUserMode(res1.data.mode);

                            this.$emit('friending-rejected');
                        } else {
                            this.error = 'failed updating mode in backend';
                            // TODO: implement better error handling scheme
                        }
                    })
                    .catch(err1 => {
                        this.error = err1.message;
                    });
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

<style scoped>
    h3 {
        color: green;
        text-align: center;
    }

    .error {
        color: red;
        text-align: center;
    }

    .right-handed-message-style {
        text-align: right;
        width: 100%;
    }

    .date-style {
        color: silver;
        font-size: 80%;
        display: block;
        font-style: italic;
    }

    .list-group-height {
        min-height: 40vh;
        max-height: 40vh;
        overflow-y: scroll;
    }

    #messaging-list-div {
        min-height: 40vh;
        background-color: white;
    }
</style>