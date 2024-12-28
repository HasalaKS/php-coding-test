import { createRouter, createWebHistory } from 'vue-router';
import CreateTicket from '../components/CreateTicket.vue';
import Login from '../components/Login.vue';
import Tickets from '../components/Tickets.vue';
import SearchTicket from '../components/SearchTicket.vue';

const routes = [
    {
        path: '/',
        name: 'CreateTicket',
        component: CreateTicket,
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
    {
        path: '/search-ticket',
        name: 'SearchTicket',
        component: SearchTicket,
     },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;