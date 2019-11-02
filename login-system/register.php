<?php 
/* Registration process, inserts user info into the database
   and sends account confirmation email message
*/

// Set session variables to be used on profile.php page
$_SESSION['email']      = $_POST['email'];
$_SESSION['first_name'] = $_POST['firstname'];
$_SESSION['last_name']  = $_POST['lastname'];
$_SESSION['user_name']  = $_POST['username'];

// Escape all $_POST variables to protect SQL injections
$first_name = $mysqli->escape_string($_POST['firstname']);
$last_name  = $mysqli->escape_string($_POST['lastname']);
$user_name  = $mysqli->escape_string($_POST['username']);
$email      = $mysqli->escape_string($_POST['email']);
$password   = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
$hash       = $mysqli->escape_string(md5( rand(0,1000)));

// Check if user with that email already exists 
$result = $mysqli->query("SELECT * FROM users WHERE email='$email'") or die($mysqli->error());

// We know user email exists if the rows returned are more than 0
if ($result->num_rows > 0) {

    $_SESSION['message'] = 'User with this email already exists!';
    header("location: error.php");
} 
else { // Email dosen't exist already in database, proceed...
    // active is 0 by DEFAULT (no need to include it here)
    $sql = "INSERT INTO users (first_name, last_name, user_name, email, password, hash)"
            . "VALUES ('$first_name', '$last_name', '$user_name', $email', '$password', '$hash')";

    // Add user to the database
    if ($mysqli->query($sql)) {
       
        $_SESSION['active']    = 0; // 0 until user activates their account with verify.php
        $_SESSION['logged_in'] = true; // So we know the user has logged in
        $_SESSION['message']   = 

                "Confirmation link has been sent to $email, please verify your
                account by clicking on the link in the message!";

        // Send registration confirmation link (verify.php)
        $to           = $email;
        $subject      = 'Accont Verification ( educrisis.in )';
        $message_body = '
        
        Hello '.$first_name.',

        Thank you for signing up!

        Please click this link to verify your account:

        http://localhost/educrisis/login-system/verify.php?email='.$email.'$hash='.$hash;

        mail($to, $subject, $message_body);

        header('location: profile.php');

    }

    else {
        $_SESSION['message'] = 'Registration failed!' ;
        header("location: error.php");
    }
}