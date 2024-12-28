<template>
    <div>
        <div class="card">
            <div class="card-header">Support Tickets</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Reference Number</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="supportTicket in supportTickets"
                            :key="supportTicket.id"
                        >
                            <td>{{ supportTicket.reference_number }}</td>
                            <td>{{ supportTicket.customer_name }}</td>
                            <td>{{ supportTicket.customer_email }}</td>
                            <td>{{ supportTicket.customer_phone_number }}</td>
                            <td>{{ supportTicket.status }}</td>
                            <td>
                                {{
                                    new Date(
                                        supportTicket.created_at
                                    ).toLocaleString()
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div
                    class="d-flex justify-content-between align-items-center mt-3"
                >
                    <div>
                        <label for="pageCount" class="form-label me-2"
                            >Items per page:</label
                        >
                        <select
                            id="pageCount"
                            class="form-select"
                            v-model="pageRowCount"
                            @change="getTicketDetails()"
                            style="width: auto; display: inline-block"
                        >
                            <option
                                v-for="option in pageOptions"
                                :key="option.value"
                                :value="option.value"
                            >
                                {{ option.text }}
                            </option>
                        </select>
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li
                                class="page-item"
                                :class="{
                                    disabled:
                                        ticketPagination.current_page === 1,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        getTicketDetails(
                                            ticketPagination.current_page - 1
                                        )
                                    "
                                >
                                    Previous
                                </a>
                            </li>

                            <li
                                v-for="page in ticketPagination.last_page"
                                :key="page"
                                class="page-item"
                                :class="{
                                    active:
                                        page === ticketPagination.current_page,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="getTicketDetails(page)"
                                >
                                    {{ page }}
                                </a>
                            </li>

                            <li
                                class="page-item"
                                :class="{
                                    disabled:
                                        ticketPagination.current_page ===
                                        ticketPagination.last_page,
                                }"
                            >
                                <a
                                    class="page-link"
                                    href="#"
                                    @click.prevent="
                                        getTicketDetails(
                                            ticketPagination.current_page + 1
                                        )
                                    "
                                >
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
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
            supportTickets: [],
            ticketPagination: {
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null,
            },
            pageRowCount: 10,
            pageOptions: [
                { value: 5, text: "5" },
                { value: 10, text: "10" },
                { value: 25, text: "25" },
                { value: 0, text: "All" },
            ],
        };
    },
    methods: {
        async getTicketDetails(currentPage = 1) {
            try {
                const apiUrl = `/api/get-tickets`;

                const params = {
                    page: currentPage,
                    perPage: this.pageRowCount === 0 ? null : this.pageRowCount,
                };

                const response = await axios.get(apiUrl, { params });
                this.supportTickets = response.data.data || [];

                this.ticketPagination = {
                    current_page: response.data.current_page || page,
                    last_page: response.data.last_page || 1,
                    prev_page_url: response.data.prev_page_url || null,
                    next_page_url: response.data.next_page_url || null,
                };
            } catch (error) {
                console.error("Error:", error.message);
            }
        },
    },
    watch: {
        pageRowCount() {
            this.getTicketDetails(1);
        },
    },
    created() {
        this.getTicketDetails();
    },
};
</script>
