<?php
include "connection.php";
session_start();

if (isset($_POST['reset_request'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if email exists
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($result) > 0) {
        $token = bin2hex(random_bytes(50)); // Generate unique token
        $expTime = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expiration time
        
        // Store the token and expiration time in the database
        mysqli_query($conn, "UPDATE users SET reset_token='$token', token_expiration='$expTime' WHERE email='$email'");
        
        // Send reset link to email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n$resetLink\nThis link will expire in one hour.";
        $headers = "From: no-reply@yourwebsite.com";
        
        mail($email, $subject, $message, $headers);
        
        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <form action="forgot.php" method="POST">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" required>
        <button type="submit" name="reset_request">Reset Password</button>
    </form>
</body>
</html>
