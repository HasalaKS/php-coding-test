<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link class="nav-link" to="/">
                    <a class="navbar-brand">PHP Test</a>
                </router-link>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/">
                                Create Ticket
                            </router-link>
                        </li>
                        <li v-if="isUserAuthenticated" class="nav-item">
                            <router-link class="nav-link" to="/tickets">
                                Manage Tickets
                            </router-link>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li v-if="!isUserAuthenticated" class="nav-item">
                            <router-link to="/login">
                                <a class="btn btn-outline-success">Login</a>
                            </router-link>
                        </li>
                        <li v-if="isUserAuthenticated" class="nav-item">
                                <a @click.prevent="logout"  class="btn btn-outline-success">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-4">
            <router-view @authenticationChanged="checkAuthenticationStatus"></router-view>
        </div>
    </div>
</template>

<script>
export default { 
    name: "App" ,

    data() {
        return {
            isUserAuthenticated: false
        };
    },
    mounted() {
        this.checkAuthenticationStatus();
    },
    methods: {
        async checkAuthenticationStatus() {
            const token = localStorage.getItem("token");
            this.isUserAuthenticated = token ? true : false; 
        },
        logout() {
            localStorage.removeItem("token");
            this.$router.push("/login");
            this.isUserAuthenticated = false;
        }
    }
};
</script>
