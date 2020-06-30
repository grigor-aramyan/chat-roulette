<template>
    <div>
        <h3>Connection purpose</h3>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="one" value="JUST_CHATTING" v-model="picked">
                        <label class="form-check-label" for="one">Just Chatting</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="two" value="RELATIONSHIP" v-model="picked">
                        <label class="form-check-label" for="two">Relationship</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="three" value="SEX" v-model="picked">
                        <label class="form-check-label" for="three">Sex</label>
                    </div>

                    <button class="btn btn-primary" @click.prevent="next">Next</button>
                </form>
            </div>
            <div class="col-4"></div>
        </div>
        
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

<style scoped>
    h3 {
        color: green;
        margin-top: 20vh;
        text-align: center;
    }

    label {
        color: silver;
        font-size: 120%;
    }
</style>