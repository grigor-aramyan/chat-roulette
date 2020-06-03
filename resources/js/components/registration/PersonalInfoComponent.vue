<template>
    <div>
        <h1>Personal info</h1>

        <input v-model="name" placeholder="Full name" />
        <br />
        <input v-model="password" type="password" placeholder="Password" />
        <br />
        <input v-model="confirmPassword" type="password" placeholder="Confirm password" />
        <br />
        <input v-model="email" placeholder="Email" />
        <br />
        <input v-model="age" min="10" type="number" placeholder="Age" />
        <br />

        <label for="gender">Gender</label>
        <select v-model="sex" id="gender">
            <option value="MALE">Male</option>
            <option value="FEMALE">Female</option>
        </select>
        <br />

        <input v-model="city" placeholder="Country, city" />

        <p v-if="error" style="color:red;">{{ error }}</p>
        <br />
        <button @click="next">Next</button>
    </div>
</template>

<script>
    const MALE = 'MALE';
    const FEMALE = 'FEMALE';

    export default {

        data() {
            return {
                name: '',
                password: '',
                email: '',
                confirmPassword: '',
                age: 0,
                sex: MALE,
                city: '',
                photo: null,
                error: ''
            }
        },

        methods: {
            next() {
                if (!this.name) {
                    this.error = 'Name required';
                } else if (!this.password) {
                    this.error = 'Password required';
                } else if (!this.confirmPassword) {
                    this.error = 'Confirm password required';
                } else if (this.password.length < 8) {
                    this.error = 'Password should be at least 8 chars long';
                } else if (!(/[A-Z]+/.test(this.password) && /[a-z]+/.test(this.password) && /[0-9]+/.test(this.password))) {
                    this.error = 'Password should contain lower-case, upper-case letters and numbers only';
                } else if (this.password != this.confirmPassword) {
                    this.error = 'Password and Confirm password don\'t match';
                } else if (!this.email) {
                    this.error = 'Email required';
                } else if (this.age < 10) {
                    this.error = 'Age should be above 10 years';
                } else if (!this.city) {
                    this.error = 'Country, city required';
                } else {
                    const personalInfo = {
                        name: this.name,
                        password: this.password,
                        email: this.email,
                        age: this.age,
                        sex: this.sex,
                        city: this.city,
                        photo: this.photo
                    };

                    this.$emit('personal-info-filled', personalInfo);
                }
            }
        }
    }
</script>