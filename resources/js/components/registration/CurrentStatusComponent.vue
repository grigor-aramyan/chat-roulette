<template>
    <div>
        <h3>Current status</h3>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="one" value="WANT_TO_CONNECT" v-model="picked">
                        <label class="form-check-label" for="one">Want to connect</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="two" value="A_BIT_LATER" v-model="picked">
                        <label class="form-check-label" for="two">A bit later</label>
                    </div>
                    <button @click.prevent="next" class="btn btn-primary">Next</button>
                </form>
            </div>
            <div class="col-4"></div>
        </div>

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

<style scoped>
    h3 {
        color: green;
        margin-top: 10vh;
        text-align: center;
    }

    label {
        color: silver;
        font-size: 120%;
    }
</style>
