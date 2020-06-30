<template>
    <div>
        <h3>Personal info</h3>

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <form>
                    <div class="form-group row">
                        <input v-model="name" class="form-control" type="text" placeholder="Full name" />
                    </div>
                    <div class="form-group row">
                        <input v-model="password" class="form-control" type="password" placeholder="Password" />
                    </div>
                    <div class="form-group row">
                        <input v-model="confirmPassword" class="form-control" type="password" placeholder="Confirm password" />
                    </div>
                    <div class="form-group row">
                        <input v-model="email" class="form-control" type="text" placeholder="Email" />
                    </div>
                    <div class="form-group row">
                        <input v-model="age" min="10" class="form-control" type="number" placeholder="Age" />
                    </div>
                    <div class="form-group row">
                        <label for="gender">Gender</label>
                        <select v-model="sex" class="custom-select" id="gender">
                            <option value="MALE">Male</option>
                            <option value="FEMALE">Female</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <input v-model="city" class="form-control" type="text" placeholder="Country, city" />
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

<style scoped>
    h3 {
        color: green;
        margin-top: 5vh;
        text-align: center;
    }

    .error {
        color: yellow;
        text-align: center;
    }

    label {
        color: silver;
    }
</style>
