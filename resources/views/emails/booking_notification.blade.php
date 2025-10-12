<!DOCTYPE html>
<html>
<head>
    <title>Booking Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
        }
        p {
            color: #555555;
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #dddddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmation</h1>
        <p>Dear Visitor,</p>
        <p>We are pleased to inform you that a new booking has been successfully created with the following details:</p>
        
        <div class="details">
            <p><strong>Transaction Number:</strong> {{ $bookingDetails['transaction_number'] }}</p>
            <p><strong>PDL ID:</strong> {{ $bookingDetails['pdl_id'] }}</p>
            <p><strong>PDL Name:</strong> {{ $bookingDetails['pdl_name'] }}</p>
            <p><strong>Date of Visit:</strong> {{ $bookingDetails['date'] }}</p>
            <p><strong>Time of Visit:</strong> {{ $bookingDetails['time'] }}</p>
            <p><strong>Type of Visit:</strong> {{ $bookingDetails['type'] }}</p>
        </div>

        <p>We look forward to your visit. Please ensure that you bring a valid ID and arrive at least 15 minutes before the scheduled time.</p>
        
        <p>If you have any questions or need to make changes to your booking, please contact our support team at support@SmartSched.com.</p>
        
        <p>Best regards,</p>
        <p>The Visitation Team</p>
    </div>
</body>
</html>
