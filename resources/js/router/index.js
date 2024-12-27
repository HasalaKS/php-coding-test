import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import About from '../components/About.vue';
import Login from '../components/Login.vue';
import Tickets from '../components/Tickets.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/about',
        name: 'About',
        component: About,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/tickets',
        name: 'Tickets',
        component: Tickets,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;