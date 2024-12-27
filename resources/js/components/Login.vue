<template>
    <div class="container mt-5">
        <h1 class="text-center">Login</h1>
        <form @submit.prevent="login">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" v-model="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" v-model="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p class="text-danger mt-3" v-if="errorMessage">{{ errorMessage }}</p>
        </form>

        <button class="btn btn-secondary" @click="testFunction">Test</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            errorMessage: '',
        };
    },
    methods: {
        async login() {
            let self = this;
            try {
                await axios.post('/api/login', {
                    email: this.email,
                    password: this.password,
                }).then(function(response) {
                    const token = response.data.access_token;
                    localStorage.setItem('token', token);
                    self.$router.push({ path: '/tickets'});
                });
            } catch (error) {
                self.errorMessage = error;
            }
        },
        // This is test function for auth route
        async testFunction() {
            try {
                await axios.post('/api/test').then(function(response) {});
            } catch (error) {
                this.errorMessage = error;
            }
        },
    },
};
</script>



