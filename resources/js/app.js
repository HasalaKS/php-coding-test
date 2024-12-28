import { createApp } from 'vue';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import App from './App.vue';
import router from './router';
import axios from 'axios';

axios.defaults.baseURL = 'http://127.0.0.1:8000'; 
axios.defaults.withCredentials = true; 

// check and configure routes with authorization token in request before send the request
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// check the request response is authorized 
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401 && window.location.pathname !== '/login') {
            console.error('Unauthorized, redirecting to login...');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

const app = createApp(App);
app.config.globalProperties.$axios = axios;
app.use(router);
app.mount('#container');