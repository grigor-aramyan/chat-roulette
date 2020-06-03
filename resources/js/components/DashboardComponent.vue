<template>
    <div>
        <h1>Dashboard</h1>

        <p v-if="currentUser">{{ currentUser.name }}</p>

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
    } from '../statics';

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
                    // TODO: display error and navigate somewhere
                    this.error = 'no data fetched';
                });
        },

        data() {
            return {
                error: ''
            }
        },

        methods: {
            ...mapActions([
                'setCurrentUser'
            ])
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser
            })
        }
    }
</script>