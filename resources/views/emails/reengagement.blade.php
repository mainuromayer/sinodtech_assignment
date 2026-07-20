<!DOCTYPE html>
<html>

<head>
    <title>We Miss You!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #10b981;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .content {
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Hello {{ $customer->name }},</h2>
        </div>
        <div class="content">
            <p>{{ $promoMessage }}</p>
        </div>
        <div class="footer">
            If you have any questions, feel free to reply to this email.
        </div>
    </div>
</body>

</html>