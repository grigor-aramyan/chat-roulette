<template>
    <div>
        <h1>Viewing answers of pair user</h1>

        <ul>
            <li v-for="answer in pairingUserAnswers" :key="answer.id">
                {{ answer.msg }}
            </li>
        </ul>

        <button @click="reject" class="btn btn-danger">Reject</button>
        <button @click="connect" class="btn btn-info">Connect</button>
    </div>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex';

    export default {
        data() {
            return {
                
            }
        },

        computed: {
            ...mapState({
                pairingUserAnswers: state => state.pairingUser.pairingUserAnswers
            })
        },

        methods: {
            reject() {
                // TODO axios call to set user status to IDLE
                // ###

                // TODO firebase RD call to notify pairing user about rejection

                this.removePairingUser();
                this.removePairingUserAnswers();
                this.$emit('reject-pairing-user');
            },
            connect() {
                // TODO axios call to set user status to CONNECTED
                // ###

                // TODO firebase RD call to notify pairing user about connection

                this.$emit('connect-pairing-user');
            },
            ...mapActions([
                'removePairingUser',
                'removePairingUserAnswers'
            ])
        }
    }
</script>