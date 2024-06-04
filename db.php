<?php
$servername = "sql312.infinityfree.com";
$username = "if0_36466624";
$password = "RcwRszYuDeyyQm";
$dbname = "if0_36466624_A";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
    }
    ?>
