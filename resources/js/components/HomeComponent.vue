<template>
    <div class="container">
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>
        <a href='#' @click.prevent="logout">Log out</a>
        <router-link to="/dashboard">Dashboard</router-link>

        <p v-if="error" style="color:red;">{{ error }}</p>

        <router-view></router-view>
    </div>
</template>

<script>
    import {
        mapActions,
        mapState
    } from 'vuex';

    import axios from 'axios';
    import {
        API_BASE_URI,
        CR_USER_TOKEN
    } from '../statics';

    export default {
        data() {
            return {
                title: 'home component',
                error: ''
            }
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser
            })
        },

        methods: {
            logout() {
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
                    mode: 'OFFLINE'
                };

                axios.post(updateMyModeUri, payload, config)
                    .then(res => {
                        if (res.status == 200) {
                            this.removePairingUser();
                            this.removePairingUserAnswers();
                            this.removeChatMessages();
                            this.setCurrentUserMode(res.data.mode);
                            localStorage.removeItem(CR_USER_TOKEN);

                            this.$router.replace('/');
                        } else {
                            this.error = 'something weird happened. check your connection, please';
                        }
                    })
                    .catch(err => {
                        this.error = err.message;
                    });
            },
            ...mapActions([
                'removePairingUser',
                'removePairingUserAnswers',
                'setCurrentUserMode',
                'removeChatMessages'
            ])
        }
    }
</script>