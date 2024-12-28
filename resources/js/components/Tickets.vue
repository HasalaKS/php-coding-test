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
                            <th>Actions</th>
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
                            <td>
                                <button
                                    v-if="!supportTicket.ticket_reply"
                                    class="btn btn-primary btn-sm me-2"
                                    @click="openModal(supportTicket)"
                                >
                                    Reply
                                </button>
                                <button
                                    v-else
                                    class="btn btn-secondary btn-sm"
                                    @click="openModal(supportTicket)"
                                >
                                    Edit
                                </button>
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

        <div
            class="modal fade"
            id="actionModal"
            tabindex="-1"
            role="dialog"
            aria-labelledby="actionModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="actionModalLabel">
                            {{ replyText ? "Edit" : "Create" }} Reply
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label
                                for="problem-description"
                                class="form-label fw-bold"
                                >Ticket Reply</label
                            >
                            <textarea
                                v-model="replyText"
                                id="problem-description"
                                :class="
                                    errorMessage.replyText
                                        ? 'form-control is-invalid'
                                        : 'form-control'
                                "
                                rows="5"
                                placeholder="Enter your reply here..."
                            ></textarea>

                            <div
                                v-if="errorMessage.replyText"
                                class="invalid-feedback"
                            >
                                {{ errorMessage.replyText[0] }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            @click="replyTextSubmit('reviewing')"
                        >
                            Save as Draft
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            @click="replyTextSubmit('closed')"
                        >
                            Close & Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import * as bootstrap from "bootstrap";

export default {
    data() {
        return {
            supportTickets: [],
            ticketPagination: {
                current_page: 1,
                last_page: 1,
            },
            pageRowCount: 10,
            pageOptions: [
                { value: 5, text: "5" },
                { value: 10, text: "10" },
                { value: 25, text: "25" },
                { value: 0, text: "All" },
            ],
            replyText: "",
            selectedTicket: null,
            errorMessage: [],
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
                this.ticketPagination = response.data;
            } catch (error) {
                console.error("Error:", error.message);
            }
        },
        openModal(ticket) {
            this.selectedTicket = ticket;
            this.replyText = ticket.ticket_reply
                ? ticket.ticket_reply.reply_text
                : "";
            const modal = new bootstrap.Modal(
                document.getElementById("actionModal")
            );
            modal.show();
        },
        async replyTextSubmit(status) {
            let self = this;
            try {
                await axios
                    .post("/api/create-reply-to-ticket", {
                        status: status,
                        replyText: this.replyText,
                        ticketId: this.selectedTicket.id,
                    })
                    .then(function (response) {
                        if (response.status === 201) {
                            const modalElement =
                                document.getElementById("actionModal");
                            const modalInstance =
                                bootstrap.Modal.getInstance(modalElement);
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                            self.getTicketDetails();
                        }
                    });
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    self.errorMessage = error.response.data.errors;
                } else {
                    console.log("An Unexpected Error: ", error);
                }
            }
        },
    },
    created() {
        this.getTicketDetails();
    },
};
</script>
