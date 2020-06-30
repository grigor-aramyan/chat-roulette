<template>
    <div>
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <h3>Answering questions</h3>
                <div v-if="currentQuestion == 'DEFAULT_SCREEN'">
                    <button class="btn btn-primary" @click="next('QUESTION_ONE')">Start answering</button>
                </div>
                <div v-else-if="currentQuestion == 'QUESTION_ONE' && pairingUser && pairingUser.question_one">
                    <div class="form-group row">
                        <p class="question-style">{{ pairingUser.question_one }}</p>
                        <input v-model="answerOne" class="form-control" type="text" placeholder="Be as truthy, as possible, please" />
                        <button class="btn btn-info mt-2" @click="next('QUESTION_TWO')">Next</button>
                    </div>
                </div>
                <div v-else-if="currentQuestion == 'QUESTION_TWO' && pairingUser && pairingUser.question_two">
                    <div class="form-group row">
                        <p class="question-style">{{ pairingUser.question_two }}</p>
                        <input v-model="answerTwo" class="form-control" type="text" placeholder="Be as truthy, as possible, please" />
                        <button class="btn btn-info mt-2" @click="next('QUESTION_THREE')">Next</button>
                    </div>
                </div>
                <div v-else-if="currentQuestion == 'QUESTION_THREE' && pairingUser && pairingUser.question_three">
                    <div class="form-group row">
                        <p class="question-style">{{ pairingUser.question_three }}</p>
                        <input v-model="answerThree" class="form-control" type="text" placeholder="Be as truthy, as possible, please" />
                        <button class="btn btn-info mt-2" @click="pair">Pair with user</button>
                    </div>
                </div>
                <div v-else-if="currentQuestion == 'END_SCREEN'">
                    <p class="end-screen-style">Wait a bit, please! Your pair still answering to questions)</p>
                </div>

                <p v-if="error" class="error">{{ error }}</p>
            </div>
            <div class="col-4"></div>
        </div>
        
    </div>
</template>

<script>
    import {
        mapState,
        mapActions
    } from 'vuex';

    const DEFAULT_SCREEN = 'DEFAULT_SCREEN';
    const QUESTION_ONE = 'QUESTION_ONE';
    const QUESTION_TWO = 'QUESTION_TWO';
    const QUESTION_THREE = 'QUESTION_THREE';
    const END_SCREEN = 'END_SCREEN';

    export default {
        data() {
            return {
                answerOne: '',
                answerTwo: '',
                answerThree: '',
                currentQuestion: DEFAULT_SCREEN,
                startTime: 0,
                intervalId: null,
                timeToAnswer: 10,
                error: ''
            }
        },

        computed: {
            ...mapState({
                currentUser: state => state.currentUser.currentUser,
                pairingUser: state => state.pairingUser.pairingUser,
                pairingUserAnswers: state => state.pairingUser.pairingUserAnswers
            })
        },

        watch: {
            pairingUserAnswers: function (newAnswers, oldAnswers) {
                if (newAnswers && (this.currentQuestion == END_SCREEN)) {
                    this.$emit('switch-to-viewing-answers');
                }
            }
        },

        methods: {
            setTimer(currentQ) {
                if (this.intervalId) {
                    clearInterval(this.intervalId);
                }

                this.startTime = new Date();
                this.intervalId = setInterval(() => {
                    const secondsPassed = Math.round((new Date() - this.startTime) / 1000);
                    console.log('doing... ' + secondsPassed);
                    if (secondsPassed >= this.timeToAnswer) {
                        if (currentQ == 1) {
                            this.answerOne = 'null';
                        } else if (currentQ == 2) {
                            this.answerTwo = 'null';
                        } else if (currentQ == 3) {
                            this.answerThree = 'null';
                        }
                        
                        this.startTime = 0;
                        clearInterval(this.intervalId);
                        this.intervalId = null;

                        if (currentQ == 1) {
                            this.currentQuestion = QUESTION_TWO;
                            this.setTimer(2);
                        } else if (currentQ == 2) {
                            this.currentQuestion = QUESTION_THREE;
                            this.setTimer(3);
                        } else if (currentQ == 3) {
                            
                            this.startTime = 0;
                            clearInterval(this.intervalId);
                            this.currentQuestion = END_SCREEN;
                            this.intervalId = null;

                            window.RD.ref('answers').set({
                                pairedFrom: this.currentUser.id,
                                pairedTo: this.pairingUser.id,
                                answerOne: this.answerOne,
                                answerTwo: this.answerTwo,
                                answerThree: this.answerThree
                            });

                            if (this.pairingUserAnswers) {
                                this.$emit('switch-to-viewing-answers');
                            }
                        }
                    }
                }, 1000);
            },
            next(nextQuestion) {
                if (nextQuestion == QUESTION_ONE) {
                    this.setTimer(1);

                    this.currentQuestion = nextQuestion;
                    this.error = '';
                } else if (nextQuestion == QUESTION_TWO) {
                    if (!this.answerOne) {
                        this.error = 'Answer required!';
                    } else {

                        this.setTimer(2);

                        this.currentQuestion = nextQuestion;
                        this.error = '';
                    }
                } else if (nextQuestion == QUESTION_THREE) {
                    if (!this.answerTwo) {
                        this.error = 'Answer required!';
                    } else {
                        
                        this.setTimer(3);

                        this.currentQuestion = nextQuestion;
                        this.error = '';
                    }
                }
            },
            pair() {
                if (!this.answerThree) {
                    this.error = 'Answer required!';
                } else {
                    this.startTime = 0;
                    clearInterval(this.intervalId);
                    this.currentQuestion = END_SCREEN;
                    this.intervalId = null;

                    this.error = '';

                    // TODO: send answers over the firebase RD
                    // wait for pairing user answers
                    // so you can evaluate each other
                    // and decide whether want to pair or not

                    window.RD.ref('answers').set({
                        pairedFrom: this.currentUser.id,
                        pairedTo: this.pairingUser.id,
                        answerOne: this.answerOne,
                        answerTwo: this.answerTwo,
                        answerThree: this.answerThree
                    });

                    if (this.pairingUserAnswers) {
                        this.$emit('switch-to-viewing-answers');
                    }

                    console.log('pairing');
                }
            }
        }
    }
</script>

<style scoped>
    h3 {
        color: green;
        text-align: center;
    }

    .error {
        color: yellow;
        text-align: center;
    }

    .question-style {
        color: white;
        text-align: center;
    }

    .end-screen-style {
        color: yellow;
        font-style: italic;
        text-align: center;
    }
</style>