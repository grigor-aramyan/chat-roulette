<template>
    <div>
        <h1>Registration</h1>
        <div v-if="currentSubPage == 'CONNECTION_PURPOSE'">
            <connection-purpose-component @pick-purpose="addPurpose"></connection-purpose-component>
        </div>
        <div v-else-if="currentSubPage == 'PERSONAL_INFO'">
            <personal-info-component @personal-info-filled="personalInfoFilled"></personal-info-component>
        </div>
        <div v-else-if="currentSubPage == 'THREE_QS'">
            <three-qs-component @questions-submitted="questionsSubmitted"></three-qs-component>
        </div>
        <div v-else-if="currentSubPage == 'CURRENT_STATUS'">
            <current-status-component @status-picked="addStatus"></current-status-component>
        </div>

        <p v-if="error" style="color:red;">{{ error }}</p>
    </div>
</template>

<script>
    import axios from 'axios';
    import {
        API_BASE_URI,
        CR_USER_TOKEN
    } from '../../statics';

    const CONNECTION_PURPOSE = 'CONNECTION_PURPOSE';
    const PERSONAL_INFO = 'PERSONAL_INFO';
    const THREE_QS = 'THREE_QS';
    const CURRENT_STATUS = 'CURRENT_STATUS';

    export default {

        data() {
            return {
                purpose: '',
                status: '',
                name: '',
                password: '',
                email: '',
                age: 0,
                sex: 'MALE',
                city: '',
                photo: null,
                questionOne: '',
                questionTwo: '',
                questionThree: '',
                currentSubPage: CONNECTION_PURPOSE,
                error: ''
            }
        },

        methods: {
            addPurpose(purpose) {
                this.purpose = purpose;
                this.currentSubPage = PERSONAL_INFO;
            },
            addStatus(status) {
                this.status = status;

                const registration_uri = API_BASE_URI + '/auth/register';

                const payload = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    purpose: this.purpose,
                    status: this.status,
                    age: this.age,
                    sex: this.sex,
                    city: this.city,
                    photo: this.photo,
                    question_one: this.questionOne,
                    question_two: this.questionTwo,
                    question_three: this.questionThree
                };

                const config = {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                };

                axios.post(registration_uri, payload, config)
                    .then(res => {
                        if (res.status == 200) {
                            const token = `Bearer ${res.data.access_token}`;
                            localStorage.setItem(CR_USER_TOKEN, token);

                            this.$router.push('/dashboard');
                        } else {
                            this.error = 'Something wrong happened. Try again or contact with us, please!';
                            this.currentSubPage = CONNECTION_PURPOSE;
                        }
                    })
                    .catch(err => {
                        this.error = err.response.data.msg;
                        this.currentSubPage = CONNECTION_PURPOSE;
                    });
            },
            personalInfoFilled({ name, password, email, age, sex, city, photo }) {
                this.name = name;
                this.password = password;
                this.email = email;
                this.age = age;
                this.sex = sex;
                this.city = city;
                this.photo = photo;
                this.currentSubPage = THREE_QS;
            },
            questionsSubmitted({ questionOne, questionTwo, questionThree }) {
                this.questionOne = questionOne;
                this.questionTwo = questionTwo;
                this.questionThree = questionThree;
                this.currentSubPage = CURRENT_STATUS;
            }
        }
    }
</script>