<template>
    <div class="container">
        <router-link to="/login">Login</router-link>
        <router-link to="/register">Register</router-link>

        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                title: 'home component'
            }
        }
    }
</script>