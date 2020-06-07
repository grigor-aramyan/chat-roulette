<template>
    <div>
        <h1>Dashboard</h1>

        <p v-if="currentUser">Current email: {{ currentUser.email }}</p>

        <p v-if="pairingUser">Pairing email: {{ pairingUser.email }}</p>

        <div v-if="currentSubPage == 'DEFAULT_DASHBOARD'">
            <h1 style="color:yellow;">Default dashboard</h1>
        </div>
        <div v-else-if="currentSubPage == 'QUESTIONS_SUBPAGE'">
            <question-answering-component></question-answering-component>
        </div>
        <div v-else-if="currentSubPage == 'ANSWERS_SUBPAGE'">
            <answers-viewing-component
                @reject-pairing-user="rejectPairingUser"
                @connect-pairing-user="connectPairingUser">
            </answers-viewing-component>
        </div>
        <div v-else-if="currentSubPage == 'CHAT_SUBPAGE'">
            <messaging-component></messaging-component>
        </div>

        <p v-if="error" style="color:red;">{{ error }}</p>
    </div>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex';
    import axios from 'axios';
    import {
        API_BASE_URI,
        CR_USER_TOKEN
    } from '../../statics';

    // constants
    const DEFAULT_DASHBOARD = 'DEFAULT_DASHBOARD';
    const QUESTIONS_SUBPAGE = 'QUESTIONS_SUBPAGE';
    const ANSWERS_SUBPAGE = 'ANSWERS_SUBPAGE';
    const CHAT_SUBPAGE = 'CHAT_SUBPAGE';

    export default {
        mounted() {
            const fetchMyDataUri = `${API_BASE_URI}/auth/me`;

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

            axios.post(fetchMyDataUri, {}, config)
                .then(res => {
                    if (res.status == 200) {
                        this.setCurrentUser(res.data);
                    } else {
                        // TODO: display error and navigate somewhere
                        this.error = 'no data fetched';
                    }
                })
                .catch(err => {
                    if (err.response.status == 401) {
                        localStorage.removeItem(CR_USER_TOKEN);
                        this.$router.replace('/login');
                    } else {
                        // TODO: display error and navigate somewhere
                        this.error = 'no data fetched';
                    }
                });
        },

        data() {
            return {
                currentSubPage: ANSWERS_SUBPAGE,
                error: ''
            }
        },

        methods: {
            rejectPairingUser() {
                this.currentSubPage = DEFAULT_DASHBOARD;
            },
            connectPairingUser() {
                this.currentSubPage = CHAT_SUBPAGE;
            },
            ...mapActions([
                'setCurrentUser',
                'setPairingUser'
            ])
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser,
                pairingUser: state => state.pairingUser.pairingUser
            })
        },

        watch: {
            currentUser: function(newUser, oldUser) {
                if (newUser && (newUser.status == 'WANT_TO_CONNECT')) {
                    const fetchPairedUserUri = `${API_BASE_URI}/pair/find`;

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

                    axios.get(fetchPairedUserUri, config)
                        .then(res => {
                            if (res.status == 200) {
                                if ('msg' in res.data) {
                                    // TODO: case when no one to pair found
                                } else if ('id' in res.data) {
                                    // TODO: got pairing user
                                    // dispatch event to firebase RD, so pairing user
                                    // can be notified about pairing and start answering
                                    // this user questions as well
                                    // set mode to 'pending' from pairing user side
                                    // lister to RD events from app.js (?)

                                    this.setPairingUser(res.data);
                                }
                            } else {
                                // TODO: display error and navigate somewhere
                                this.error = 'no data fetched';
                            }
                        })
                        .catch(err => {
                            // TODO: display error and navigate somewhere
                            this.error = 'no data fetched';
                        });
                }
            }
        }
    }
</script>