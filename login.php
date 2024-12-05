<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_or_phone = $_POST['email_or_phone'];
    $password = $_POST['password'];

    // Check if email or phone exists
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email_or_phone = ?");
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header("Location: ../html/home.html");
        } else {
            $_SESSION['error_message'] = "Invalid password.";
            header("Location: ../html/login.html");
        }
    } else {
        $_SESSION['error_message'] = "Email or phone number not found.";
        header("Location: ../html/login.html");
    }

    $stmt->close();
    $conn->close();
}
?>
