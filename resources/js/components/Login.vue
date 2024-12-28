<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <form @submit.prevent="login">
                    <div class="mb-3">
                        <label for="email" class="form-label"
                            >Email address</label
                        >
                        <input
                            type="text"
                            v-model="email"
                            :class="errorMessage.email ? 'form-control is-invalid' : 'form-control'"
                            id="email"
                        />
                        <div v-if="errorMessage.email" class="invalid-feedback">
                            {{ errorMessage.email[0] }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"
                            >Password</label
                        >
                        <input
                            type="password"
                            v-model="password"
                            :class="errorMessage.password ? 'form-control is-invalid' : 'form-control'"                            id="password"
                        />
                        <div v-if="errorMessage.password" class="invalid-feedback">
                            {{ errorMessage.password[0] }}
                        </div>
                    </div>
                    <div v-if="commonErrorMessage" class="alert alert-danger" role="alert">
                        {{ commonErrorMessage }}
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            email: "",
            password: "",
            errorMessage: [],
            commonErrorMessage: "",
        };
    },
    methods: {
        async login() {
            let self = this;
            self.errorMessage = [];
            self.commonErrorMessage = "";

            try {
                await axios
                    .post("/api/login", {
                        email: this.email,
                        password: this.password,
                    })
                    .then(function (response) {
                        const token = response.data.access_token;
                        localStorage.setItem("token", token);
                        self.$emit('authenticationChanged');
                        self.$router.push({ path: "/tickets" });
                    });
            } catch (error) {
                if(error.response && error.response.status === 422) {
                    self.errorMessage = error.response.data.errors;
                } else if(error.response && error.response.status === 401) {
                    self.commonErrorMessage = error.response.data.message;  
                } else {
                    console.log('An Unexpected Error: ', error); 
                }
            }
        },
    },
};
</script>
