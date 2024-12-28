<template>
    <div>
        <div class="card">
            <div class="card-header">Ticket Details</div>
            <div class="card-body">
                <div v-if="successfullyCreated" class="alert alert-success alert-dismissible fade show" role="alert">
                    Successfully created. The Ticket reference is <strong>{{ createdTicketReferenceNumber }}</strong>
                    <small> (The email will be send to your email.)</small>
                </div>
                <form>
                    <div class="form-group mb-4">
                        <label for="customer-name">Customer Name</label>
                        <input
                            v-model="customerName"
                            type="text"
                            id="customer-name"
                            placeholder="Enter your name"
                            :class="errorMessage.customerName ? 'form-control is-invalid' : 'form-control'"
                        />
                        <div v-if="errorMessage.customerName" class="invalid-feedback">
                            {{ errorMessage.customerName[0] }}
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="problem-description">Problem Description</label>
                        <textarea
                            v-model="problemDescription"
                            :class="errorMessage.problemDescription ? 'form-control is-invalid' : 'form-control'"
                            id="problem-description"
                            rows="3"
                        ></textarea>
                        <div v-if="errorMessage.problemDescription" class="invalid-feedback">
                            {{ errorMessage.problemDescription[0] }}
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="email">Email</label>
                        <input
                            v-model="customerEmail"
                            type="email"
                            :class="errorMessage.customerEmail ? 'form-control is-invalid' : 'form-control'"
                            id="email"
                            placeholder="Enter your email address"
                        />
                        <div v-if="errorMessage.customerEmail" class="invalid-feedback">
                            {{ errorMessage.customerEmail[0] }}
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="phone-number">Phone Number</label>
                        <input
                            v-model="customerPhoneNumber"     
                            type="tel"
                            :class="errorMessage.customerPhoneNumber ? 'form-control is-invalid' : 'form-control'"
                            id="phone-number"
                            placeholder="Enter your phone number"
                        />
                        <div v-if="errorMessage.customerPhoneNumber" class="invalid-feedback">
                            {{ errorMessage.customerPhoneNumber[0] }}
                        </div>
                    </div>
                </form>
                <div class="text-end mt-3">
                    <button type="button" @click="submitTicketDetails" class="btn btn-primary me-2">Submit</button>
                    <button type="button" @click="resetTicketDetails" class="btn btn-secondary">Reset</button>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "CreateTicket",

    data() {
        return {
            customerName: '',
            problemDescription: '',
            customerEmail: '',
            customerPhoneNumber: '',
            errorMessage: [],
            successfullyCreated: false,
            createdTicketReferenceNumber:'',
        };
    },
    methods: {
        async submitTicketDetails() {
            let self = this;
            try {
                await axios.post('/api/create-ticket', {
                    customerName: this.customerName,
                    problemDescription: this.problemDescription,
                    customerEmail: this.customerEmail,
                    customerPhoneNumber: this.customerPhoneNumber,
                }).then(function(response) {
                    if(response.status === 201) {
                        self.createdTicketReferenceNumber = response.data.data.reference_number;
                        self.successfullyCreated = true;
                        self.resetTicketDetails();
                    }
                }).finally(function() {
                    setTimeout(() => {
                        self.successfullyCreated = false;
                    }, 3000);
                });
            } catch (error) {
                if(error.response && error.response.status === 422) {
                    self.errorMessage = error.response.data.errors;
                } else {
                    console.log('An Unexpected Error: ', error);
                }
            }
        },
        resetTicketDetails() {
            this.customerName = '';
            this.problemDescription = '';
            this.customerEmail = '';
            this.customerPhoneNumber = '';
            this.errorMessage = [];
        }
    },
};
</script>

<style scoped></style>
