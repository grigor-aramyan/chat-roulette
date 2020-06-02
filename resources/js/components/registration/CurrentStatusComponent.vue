<template>
    <div>
        <h1>Current status</h1>

        <input type="radio" id="one" value="WANT_TO_CONNECT" v-model="picked">
        <label for="one">Want to connect</label>
        <br>
        <input type="radio" id="two" value="A_BIT_LATER" v-model="picked">
        <label for="two">A bit later</label>

        <br />
        <button @click="next">Next</button>
    </div>
</template>

<script>
    const WANT_TO_CONNECT = 'WANT_TO_CONNECT';
    const A_BIT_LATER = 'A_BIT_LATER';

    export default {
        data() {
            return {
                picked: WANT_TO_CONNECT,
                error: ''
            }
        },

        methods: {
            next() {
                if (this.picked) {
                    this.$emit('status-picked', this.picked);
                }
            }
        }
    }
</script>