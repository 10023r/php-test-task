<?php 
    $db_servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "test_task";
    // Create connection
    
    // Check connection
    try {
        $conn = mysqli_connect($db_servername, 
                            $db_username, 
                            $db_password,
                            $db_name);
    } catch (mysqli_sql_exception) {
        echo "Couldn't connect.";
    }     
    // echo "Connected successfully";
?>