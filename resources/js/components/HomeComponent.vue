<template>
    <div class="container">
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>
        <a href='#' @click.prevent="logout">Log out</a>
        <router-link to="/dashboard">Dashboard</router-link>

        <p v-if="error" style="color:red;">{{ error }}</p>

        <router-view></router-view>

        <div v-if="$router.currentRoute.path == '/'" id='landing'>
            <div id='our-mission'>
                <h2>Our mission</h2>
                <div class="row">
                    <div class="col">
                        <p style="font-style:italic;">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                    </div>
                    <div class="col">
                        <img style='width:20rem;heigth:20rem;' src='images/our_mission.png' />
                    </div>
                </div>
            </div>

            <div id='team'>
                <h2>Team</h2>
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <img class="card-img-top" src="images/team.jpg" />
                            <div class="card-body">
                                <h5 class="card-title">Member 1</h5>
                                <p class="card-text">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img class="card-img-top" src="images/team.jpg" />
                            <div class="card-body">
                                <h5 class="card-title">Member 2</h5>
                                <p class="card-text">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <img class="card-img-top" src="images/team.jpg" />
                            <div class="card-body">
                                <h5 class="card-title">Member 3</h5>
                                <p class="card-text">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end team -->

            <div id="rules">
                <h2>Rules</h2>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rule
                                <span class="badge badge-primary badge-pill">1</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rule
                                <span class="badge badge-primary badge-pill">2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rule
                                <span class="badge badge-primary badge-pill">3</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rule
                                <span class="badge badge-primary badge-pill">4</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rule
                                <span class="badge badge-primary badge-pill">5</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-3"></div>
                </div>
            </div> <!-- end rules -->
        </div> <!-- end landing -->

    </div> <!-- end container -->
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

<style scoped>
    h2 {
        text-align: center;
    }
</style>