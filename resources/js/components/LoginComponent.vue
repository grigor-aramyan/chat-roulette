<template>
    <div>

        <h1>Login</h1>
        
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <div class="form-group row">
                        <input v-model="email" class="form-control" type="text" placeholder="Email" />
                    </div>
                    <div class="form-group row">
                        <input v-model="password" class="form-control" type="password" placeholder="Password" />
                    </div>
                    <button @click.prevent="doLogin" class="btn btn-primary">Login</button>
                </form>
            </div>
            <div class="col-4"></div>
        </div>
        
        <p v-if="error" class="error">{{ error }}</p>

    </div>
</template>

<script>
    import axios from 'axios';
    import {
        API_BASE_URI,
        CR_USER_TOKEN
    } from '../statics';

    export default {
        data() {
            return {
                email: '',
                password: '',
                error: ''
            }
        },

        methods: {
            doLogin() {
                if (!(this.email && this.password)) {
                    this.error = 'Both fields required';
                } else if (this.password.length < 8) {
                    this.error = 'Password should contain at least 8 characters';
                } else if (!(/[A-Z]+/.test(this.password) && /[a-z]+/.test(this.password) && /[0-9]+/.test(this.password))) {
                    this.error = 'Password should contain lower-case, upper-case letters and numbers';
                } else {
                    const loginUri = `${API_BASE_URI}/auth/login`;

                    const payload = {
                        email: this.email,
                        password: this.password
                    };

                    const config = {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    };

                    axios.post(loginUri, payload, config)
                        .then(res => {
                            if (res.status == 200) {
                                const token = `Bearer ${res.data.access_token}`;
                                localStorage.setItem(CR_USER_TOKEN, token);

                                this.error = '';

                                this.$router.push('/dashboard');
                            } else {
                                this.error = 'Something wrong happened. Try again or contact with us, please!';
                            }
                        })
                        .catch(err => {
                            if (err.response.status == 401) {
                                this.error = 'Unauthorized';
                            } else {
                                this.error = err.message;
                            }
                        });
                }
            }
        }
    }
</script>

<style scoped>
    h1 {
        color: green;
        text-align: center;
        margin-top: 20vh;
    }

    .error {
        color: yellow;
        text-align: center;
    }
</style>