<template>
    <div>
        <h1>Personal info</h1>

        <input v-model="name" placeholder="Full name" />
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
                    this.error = 'Name required'
                } else if (this.age < 10) {
                    this.error = 'Age should be above 10 years'
                } else if (!this.city) {
                    this.error = 'Country, city required'
                } else {
                    const personalInfo = {
                        name: this.name,
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