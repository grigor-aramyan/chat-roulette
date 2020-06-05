<template>
    <div>
        <h1>Viewing answers of pair user</h1>

        <ul>
            <li v-for="message in messages" :key="message.id">
                {{ message.msg }}
            </li>
        </ul>
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
                messages: [
                    { id: 1, msg: 'message1' },
                    { id: 2, msg: 'message2' },
                    { id: 3, msg: 'message3' }
                ]
            }
        }
    }
</script>