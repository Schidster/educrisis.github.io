<?php
/* Reset your account password form, sends reset.php password link */
require 'db.php';
session_start();

// Check if form submitted with method="post"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $mysqli->escape_string($_POST['email']);
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email");

    if ($result->num_rows == 0) // User dosen't exist {

        $_SESSION['message'] = "User with that email dosen't exist!";
        header("location: error.php");
    }
    else { // User exists 
    
        $user = $result->fetch_assoc(); // $user becomes array with user data

        $email = $user['email'];
        $hash  = $user['hash'];
        $first_name = $user['first_name'];

        // Session message to display on success.php
        $_SESSION['message'] = "<p>Please check your email <span>$email</span>"
        . "for a confirmation link to complete your password reset!";

        // Send registration confirmation link (reset.php)
        $to           = $email;
        $subject      = 'Accont Verification ( educrisis.in )';
        $message_body = '
        
        Hello '.$first_name.',

        You have requested password reset!

        Please click this link to reset your account:

        http://localhost/educrisis/login-system/reset.php?email='.$email.'$hash='.$hash;

        mail($to, $subject, $message_body);

        header('location: success.php');



    }
}
?>