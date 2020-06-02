<template>
    <div>
        <h1>Three Questions</h1>

        <input v-model="questionOne" placeholder="Question 1" />
        <br />
        <input v-model="questionTwo" placeholder="Question 2" />
        <br />
        <input v-model="questionThree" placeholder="Question 3" />
        <br />

        <p v-if="error" style="color:red;">{{ error }}</p>

        <button @click="next">Next</button>
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