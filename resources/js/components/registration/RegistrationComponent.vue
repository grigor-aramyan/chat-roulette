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
            <three-qs-component></three-qs-component>
        </div>
        <div v-else-if="currentSubPage == 'CURRENT_STATUS'">
            <current-status-component @status-picked="addStatus"></current-status-component>
        </div>
    </div>
</template>

<script>
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
                age: 0,
                sex: 'MALE',
                city: '',
                photo: null,
                currentSubPage: PERSONAL_INFO
            }
        },

        methods: {
            addPurpose(purpose) {
                this.purpose = purpose;
                this.currentSubPage = PERSONAL_INFO;
            },
            addStatus(status) {
                this.status = status;
            },
            personalInfoFilled({ name, age, sex, city, photo }) {
                this.name = name;
                this.age = age;
                this.sex = sex;
                this.city = city;
                this.photo = photo;
                this.currentSubPage = THREE_QS;
            }
        }
    }
</script>