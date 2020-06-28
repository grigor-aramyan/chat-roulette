<template>
    <div>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <h3>Viewing answers of pair user</h3>

                    <ul class="list-group">
                        <li class="list-group-item" v-for="answer in pairingUserAnswers" :key="answer.id">
                            {{ answer.msg }}
                        </li>
                    </ul>

                    <p v-if="error" class="error">{{ error }}</p>

                    <button @click.prevent="reject" class="btn btn-danger">Reject</button>
                    <button @click.prevent="connect" class="btn btn-info">Connect</button>
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

    export default {
        mounted() {
            window.RD.ref('pairingReply').on('value', (snapshot) => {
                const snapData = snapshot.val();

                if (this.currentUser && this.pairingUser) {
                    if (snapData.pairedTo == this.currentUser.id &&
                        snapData.pairedFrom == this.pairingUser.id) {
                            if (this.pairingDecision && snapData.status) {
                                // TODO: pairing complete
                                // switch to chat page
                                this.$emit('connect-pairing-user');
                            } else if (this.pairingDecision && !snapData.status) { 
                                // TODO: pairing rejected from the other side
                                // print some msg for user to know what happened

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
                                    .then(res => {
                                        if (res.status == 200) {
                                            const updatedMode = res.data.mode;

                                            this.setCurrentUserMode(updatedMode);

                                            this.removePairingUser();
                                            this.removePairingUserAnswers();

                                            this.error = 'Pairing rejected from the other side((';

                                            setTimeout(() => {
                                                this.$emit('reject-pairing-user');
                                            }, 10000);

                                        } else {
                                            this.error = 'something weird happened';
                                        }
                                    })
                                    .catch(err => {
                                        this.error = err.message;
                                    });
                            } else if (this.pairingDecision == null) {
                                if (snapData.status) {
                                    this.pairingReply = snapData.status;

                                    // TODO: print some msg for user to know his pair
                                    // is interested to connect

                                    this.error = 'Pair is interested to connect ))';
                                } else {
                                    // TODO: print some msg for user to know his pair
                                    // rejects pairing
                                    // clear data and return to dashboard

                                    this.error = 'Pairing rejected from the other side (( Sorry. Stay connected and we\'ll find another pair for you';

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
                                                
                                            } else {
                                                this.error = 'failed updating mode in backend';
                                                
                                            }
                                        })
                                        .catch(err1 => {
                                            this.error = err1.message;
                                        });
                                    
                                    setTimeout(() => {
                                        
                                        this.removePairingUser();
                                        this.removePairingUserAnswers();
                                        this.setCurrentUserMode('IDLE');
                                        this.$emit('reject-pairing-user');

                                    }, 10000);
                                }
                            }
                        }
                }
            });
        },

        data() {
            return {
                pairingReply: null,
                pairingDecision: null,
                error: ''
            }
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser,
                pairingUser: state => state.pairingUser.pairingUser,
                pairingUserAnswers: state => state.pairingUser.pairingUserAnswers
            })
        },

        methods: {
            reject() {
                
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
                    .then(res => {
                        if (res.status == 200) {
                            const updatedMode = res.data.mode;

                            this.setCurrentUserMode(updatedMode);

                            // TODO firebase RD call to notify pairing user about rejection
                            // status: 0 (rejected), 1 (paired)
                            window.RD.ref('pairingReply').set({
                                pairedFrom: this.currentUser.id,
                                pairedTo: this.pairingUser.id,
                                status: 0
                            });

                            this.removePairingUser();
                            this.removePairingUserAnswers();
                            this.$emit('reject-pairing-user');
                        } else {
                            this.error = 'something weird happened';
                        }
                    })
                    .catch(err => {
                        this.error = err.message;
                    });

            },
            connect() {
                
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
                    mode: 'CONNECTED'
                };

                axios.post(updateMyModeUri, payload, config)
                    .then(res => {
                        if (res.status == 200) {
                            const updatedMode = res.data.mode;

                            this.setCurrentUserMode(updatedMode);

                            // TODO firebase RD call to notify pairing user about connection
                            window.RD.ref('pairingReply').set({
                                pairedFrom: this.currentUser.id,
                                pairedTo: this.pairingUser.id,
                                status: 1
                            });

                            if (this.pairingReply) {
                                this.$emit('connect-pairing-user');
                            } else {
                                this.error = 'your pair is notified about your decision. wait for his reply, please';
                                this.pairingDecision = 1;
                            }
                        } else {
                            this.error = 'something weird happened';
                        }
                    })
                    .catch(err => {
                        this.error = err.message;
                    });

            },
            ...mapActions([
                'removePairingUser',
                'removePairingUserAnswers',
                'setCurrentUserMode'
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
</style>