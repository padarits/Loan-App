<!-- resources/views/emails/admin_role_removed.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Removed Notification</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>We wanted to inform you that your admin role has been removed.</p>
    <p>If this change was unexpected, please contact support for further assistance.</p>
    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
</body>
</html>
