<template>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">Search Ticket Details</div>
            <div class="card-body">
                <form @submit.prevent="searchTicket" class="mb-4">
                    <div class="input-group">
                        <input
                            type="text"
                            v-model="referenceNumber"
                            placeholder="Enter Reference Number"
                            class="form-control"
                            required/>
                        <button class="btn btn-primary me-1" type="submit">Search</button>
                        <button class="btn btn-primary" type="button" @click="resetSearch">reset</button>
                    </div>
                </form>
                <div v-if="ticket" class="card mb-4">
                    <div class="card-header">
                        <h5>Ticket Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Reference Number:</strong> {{ ticket.reference_number }}</p>
                        <p><strong>Customer Name:</strong> {{ ticket.customer_name }}</p>
                        <p><strong>Email:</strong> {{ ticket.customer_email }}</p>
                        <p><strong>Phone:</strong> {{ ticket.customer_phone_number }}</p>
                        <p><strong>Status:</strong> <span :class="statusBadgeClass">{{ ticket.status }}</span> </p>
                        <p><strong>Problem Description:</strong> {{ ticket.problem_description }}</p>
                    </div>
                </div>
                <div v-if="ticket && ticket.ticket_reply" class="card">
                    <div class="card-header">
                        <h5>Replies</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <p><strong>Agent:</strong> {{ ticket.ticket_reply.replied_agent.name }}</p>
                            <p><strong>Reply:</strong> {{ ticket.ticket_reply.reply_text }}</p>
                            <p><small class="text-muted">Replied on {{ formatDate(ticket.ticket_reply.created_at) }}</small></p>
                        </li>
                    </ul>
                </div>
                <div v-if="ticket === null && !loading" class="alert alert-warning mt-4">
                    No ticket found with the provided reference number.
                </div>
                <div v-if="loading" class="text-center mt-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div> 
    </div>
 </template>

 <script>
 import axios from "axios";
 
 export default {
    data() {
        return {
            referenceNumber: "",
            ticket: null,
            loading: false,
        };
    },
    computed: {
        statusBadgeClass() {
            switch (this.ticket.status) {
                case "open":
                    return "badge bg-success";
                case "reviewing":
                    return "badge bg-info";
                case "closed":
                    return "badge bg-primary";
                default:
                    return "badge bg-danger";
            }
        },
    },
    methods: {
        // get ticket details according to the reference number
        async searchTicket() {
            this.ticket = null;
            this.replies = [];
            this.loading = true;

            try {
                const response = await axios.get(`/api/tickets/${this.referenceNumber}`);
                this.ticket = response.data.ticket;
            } catch (error) {
                if (error.response?.status === 404) {
                    this.ticket = null;
                } else {
                    console.error("An error occurred:", error.message);
                }
            } finally {
                this.loading = false;
            }
        },
        // format date to display in a readable format
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString();
        },
        // reset search form and ticket details
        resetSearch() {
            this.referenceNumber = "";
            this.ticket = null;
            this.loading = false;
        }
    },
 };
 </script>
 
 
 