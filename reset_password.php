<?php
// Include database connection
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_or_phone = $_POST['email_or_phone'];

    // Check if the email/phone exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email_or_phone = ?");
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Password reset logic here (e.g., sending email with a reset link)
        echo "Password reset link has been sent to your email or phone number.";
    } else {
        echo "No account found with that email or phone number.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="reset-password-container">
        <h2>Reset Password</h2>
        <form action="reset_password.php" method="POST">
            <label for="email_or_phone">Email or Phone:</label>
            <input type="text" id="email_or_phone" name="email_or_phone" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
