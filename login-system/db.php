<?php
/* Database connection settings */
$host = 'localhost';
$user = 'root';
$pass = 'forevercookies';
$db = 'accounts';
$mysqli = new mysqli($host, $user, $pass, $db) or die ($mysqli->error);
