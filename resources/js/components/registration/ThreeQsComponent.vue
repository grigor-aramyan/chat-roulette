<template>
    <div>
        <h3>Three Questions</h3>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <div class="form-group row">
                        <input v-model="questionOne" class="form-control" type="text" placeholder="Question 1" />
                    </div>
                    <div class="form-group row">
                        <input v-model="questionTwo" class="form-control" type="text" placeholder="Question 2" />
                    </div>
                    <div class="form-group row">
                        <input v-model="questionThree" class="form-control" type="text" placeholder="Question 3" />
                    </div>
                    <p v-if="error" class="error">{{ error }}</p>
                    <button @click.prevent="next" class="btn btn-primary">Next</button>
                </form>
            </div>
            <div class="col-4"></div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                questionOne: '',
                questionTwo: '',
                questionThree: '',
                error: ''
            }
        },

        methods: {
            next() {
                if (this.questionOne && this.questionTwo && this.questionThree) {
                    const questions = {
                        questionOne: this.questionOne,
                        questionTwo: this.questionTwo,
                        questionThree: this.questionThree
                    };

                    this.$emit('questions-submitted', questions);
                } else {
                    this.error = 'All questions required!';
                }
            }
        }
    }
</script>

<style scoped>
    h3 {
        color: green;
        margin-top: 5vh;
        text-align: center;
    }

    .error {
        color: red;
        text-align: center;
    }
</style>
