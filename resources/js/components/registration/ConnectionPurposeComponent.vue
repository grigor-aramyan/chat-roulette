<template>
    <div>
        <h1>Connection purpose</h1>
        
        <input type="radio" id="one" value="JUST_CHATTING" v-model="picked">
        <label for="one">Just Chatting</label>
        <br>
        <input type="radio" id="two" value="RELATIONSHIP" v-model="picked">
        <label for="two">Relationship</label>
        <br>
        <input type="radio" id="three" value="SEX" v-model="picked">
        <label for="three">Sex</label>

        <br />
        <button @click="next">Next</button>
    </div>
</template>

<script>
    const JUST_CHATTING = 'JUST_CHATTING';
    const RELATIONSHIP = 'RELATIONSHIP';
    const SEX = 'SEX';

    export default {

        data() {
            return {
                picked: JUST_CHATTING,
                error: ''
            }
        },

        methods: {
            next() {
                if (this.picked) {
                    this.$emit('pick-purpose', this.picked);
                }
            }
        }
    }
</script>