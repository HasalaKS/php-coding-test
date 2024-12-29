<!DOCTYPE html>
<html>

<head>
    <title>Support Ticket Agent Reply Details</title>
</head>

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; text-align: center; color: #000;">
    <div style="max-width: 600px; margin: 20px auto; padding: 20px;">
        <h1 style="font-size: 20px; color: #333;">Your Support Ticket Reply!</h1>
        <p>Hello {{ $ticket['customer_name'] }},</p>
        <p>Our team has processed your ticket. Below are the current ticket details:</p>
        <ul style="padding: 0; text-align: left; display: inline-block;">
            <li><strong>Reference Number:</strong> {{ $ticket['reference_number'] }}</li>
            <li><strong>Description:</strong> {{ $ticket['problem_description'] }}</li>
            <li><strong>Status:</strong> {{ $ticket['status'] }}</li>
            <li><strong>Reply:</strong> {{ $ticket['ticket_reply']['reply_text']}}</li>
        </ul>
        <p>Contact our team for further assistance.</p>
        <p>Best Regards,<br> Ticket Recorder</p>
    </div>
</body>

</html>