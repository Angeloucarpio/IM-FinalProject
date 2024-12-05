<?php
session_start();
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $surname = $_POST['surname'];
    $email_or_phone = $_POST['email_or_phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if user already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email_or_phone = ?");
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error_message'] = "Email or phone number already registered.";
        header("Location: ../html/register.html");
    } else {
        $stmt = $conn->prepare("INSERT INTO users (first_name, surname, email_or_phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $first_name, $surname, $email_or_phone, $password);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Account created successfully. Please log in.";
            header("Location: ../html/login.html");
        } else {
            $_SESSION['error_message'] = "An error occurred. Please try again.";
            header("Location: ../html/register.html");
        }
    }

    $stmt->close();
    $conn->close();
}
?>
