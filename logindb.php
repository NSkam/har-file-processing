<?php      
	session_start();

    $host = "localhost";  
    $user = "root";  
    $password = '';  
    $db_name = "project_web_2021";  
      
    $con = mysqli_connect($host, $user, $password, $db_name);  
	
	$_SESSION['con'] = $con;
	
    if(mysqli_connect_errno()) {  
        die("Failed to connect with MySQL: ". mysqli_connect_error());  
    }  
?>  