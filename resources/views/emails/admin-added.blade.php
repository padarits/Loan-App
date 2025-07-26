<!-- resources/views/emails/admin_role_added.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Role Added Notification</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>Congratulations! You have been granted admin privileges on our platform.</p>
    <p>With this role, you now have access to additional features and settings.</p>
    <p>If you have any questions, feel free to reach out to our support team.</p>
    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
</body>
</html>
