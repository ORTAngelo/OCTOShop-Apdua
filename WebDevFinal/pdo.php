<?php
$host="localhost";
    $user="root";
    $password="";
    $dbname="octo_thaiparts";

    // data source name
    $dsn="mysql:host=$host;dbname=$dbname";

    // create a PDO instance - open DB connection
    $conn = new PDO($dsn, $username, $password);
?>