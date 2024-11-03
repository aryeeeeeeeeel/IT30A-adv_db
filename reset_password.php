<?php
include "connection.php";
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if token is valid and not expired
    $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token' AND token_expiration > NOW()");
    if (mysqli_num_rows($result) === 1) {
        if (isset($_POST['reset_password'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            // Update password in the database
            mysqli_query($conn, "UPDATE users SET password='$new_password', reset_token=NULL, token_expiration=NULL WHERE reset_token='$token'");
            
            echo "Password has been reset successfully. You can now <a href='login.php'>log in</a> with your new password.";
            exit();
        }
    } else {
        echo "This reset link is invalid or expired.";
        exit();
    }
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
    <form action="" method="POST">
        <label for="password">Enter new password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</body>
</html>
