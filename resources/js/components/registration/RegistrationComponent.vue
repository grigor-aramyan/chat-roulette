<template>
    <div>
        <h1>{{ title }}</h1>
        <div v-if="currentSubPage == 'CONNECTION_PURPOSE'">
            <connection-purpose-component></connection-purpose-component>
        </div>
        <div v-else-if="currentSubPage == 'PERSONAL_INFO'">
            <personal-info-component></personal-info-component>
        </div>
        <div v-else-if="currentSubPage == 'THREE_QS'">
            <three-qs-component></three-qs-component>
        </div>
        <div v-else-if="currentSubPage == 'CURRENT_STATUS'">
            <current-status-component></current-status-component>
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
                title: 'Registration',
                currentSubPage: ''
            }
        }
    }
</script>