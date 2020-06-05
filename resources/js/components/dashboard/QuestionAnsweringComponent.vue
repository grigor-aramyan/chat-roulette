<template>
    <div>
        <h1>Answering questions</h1>
        <div v-if="currentQuestion == 'DEFAULT_SCREEN'">
            <button @click="next('QUESTION_ONE')">Start answering</button>
        </div>
        <div v-else-if="currentQuestion == 'QUESTION_ONE' && pairingUser && pairingUser.question_one">
            <p>{{ pairingUser.question_one }}</p>
            <input v-model="answerOne" placeholder="Be as truthy, as possible, please" />
            <br />

            <button @click="next('QUESTION_TWO')">Next</button>
        </div>
        <div v-else-if="currentQuestion == 'QUESTION_TWO' && pairingUser && pairingUser.question_two">
            <p>{{ pairingUser.question_two }}</p>
            <input v-model="answerTwo" placeholder="Be as truthy, as possible, please" />
            <br />

            <button @click="next('QUESTION_THREE')">Next</button>
        </div>
        <div v-else-if="currentQuestion == 'QUESTION_THREE' && pairingUser && pairingUser.question_three">
            <p>{{ pairingUser.question_three }}</p>
            <input v-model="answerThree" placeholder="Be as truthy, as possible, please" />
            <br />

            <button @click="pair">Pair with user</button>
        </div>
        <div v-else-if="currentQuestion == 'END_SCREEN'">
            <p style="color:purple;">Wait a bit, please! Your pair still answering to questions)</p>
        </div>

        <p v-if="error" style="color:red;">{{ error }}</p>
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
                timeToAnswer: 5,
                error: ''
            }
        },

        computed: {
            ...mapState({
                pairingUser: state => state.pairingUser.pairingUser
            })
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
                            this.currentQuestion = END_SCREEN;

                            this.startTime = 0;
                            clearInterval(this.intervalId);
                            this.currentQuestion = END_SCREEN;
                            this.intervalId = null;
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
                    console.log('pairing');
                }
            }
        }
    }
</script>