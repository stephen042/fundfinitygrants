<?php
session_start();
include '../db_connection.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['errorMsg'] = 'Please fill in all fields.';
        header("Location: Login.php");
        exit;
    }

    $sql = "SELECT * FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['id'];
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['errorMsg'] = 'Invalid password. Please try again.';
            header("Location: Login.php");
            exit;
        }
    } else {
        $_SESSION['errorMsg'] = 'No account found with that email.';
        header("Location: Login.php");
        exit;
    }

    $stmt->close();
}

?>
