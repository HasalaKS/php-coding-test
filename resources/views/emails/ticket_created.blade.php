<!DOCTYPE html>
<html>

<head>
    <title>Ticket Details</title>
</head>

<body>
    <h1>Thank You for Reaching Out!</h1>
    <p>Hello {{ $ticket->customer_name }},</p>
    <p>We have received your ticket. Below are the details:</p>
    <ul>
        <li><strong>Reference Number:</strong> {{ $ticket->reference_number }}</li>
        <li><strong>Description:</strong> {{ $ticket->problem_description }}</li>
        <li><strong>Status:</strong> {{ $ticket->status }}</li>
    </ul>
    <p>Our team will get back to you shortly.</p>
    <p>Best Regards,<br>{{ config('app.name') }}</p>
</body>

</html>