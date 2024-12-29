<!DOCTYPE html>
<html>

<head>
    <title>Support Ticket Details</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; text-align: center; color: #000;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px;">
        <h1 style="font-size: 20px; color: #333;">Thank You for Reaching Out!</h1>
        <p>Hello {{ $ticket->customer_name }},</p>
        <p>We have received your ticket. Below are the details:</p>
        <ul style="padding: 0; text-align: left; display: inline-block;">
            <li><strong>Reference Number:</strong> {{ $ticket->reference_number }}</li>
            <li><strong>Description:</strong> {{ $ticket->problem_description }}</li>
            <li><strong>Status:</strong> {{ $ticket->status }}</li>
        </ul>
        <p>Our team will respond shortly.</p>
        <p>Best Regards,<br>Ticket Recorder</p>
    </div>
</body>


</html>