<template>
    <div>
        <h1>Viewing answers of pair user</h1>

        <ul>
            <li v-for="answer in pairingUserAnswers" :key="answer.id">
                {{ answer.msg }}
            </li>
        </ul>

        <p v-if="error" style="color:red;">{{ error }}</p>

        <button @click="reject" class="btn btn-danger">Reject</button>
        <button @click="connect" class="btn btn-info">Connect</button>
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
                error: ''
            }
        },

        computed: {
            ...mapState({
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

                            this.$emit('connect-pairing-user');
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