<?php
	session_start();
	include('logindb.php');

	//Pairnoume ta username kai password apo ta fields tou arxeiou html
	$password = $_POST['password_text'];  
	$username = $_POST['username_text'];
	
	//Afairoume ta \ gia na mporesoume na tre3oume sql queries
	$username = stripcslashes($username);  
	$password = stripcslashes($password); 

	//Afairoume eidikous xarakthres gia na mporesoume na tre3oume sql queries
	$username = mysqli_real_escape_string($con, $username); 
	$password = mysqli_real_escape_string($con, $password); 
    
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	
	//to sql query mas
	//$sql = "select * from Usr where username = '$username' and password = '$password'";  
	$sql = "select ID,status from Usr where username = '$username' and password = '$password'";  
	
	//to tresoume sthn database mas
    $result = mysqli_query($con, $sql);
	
	//Metatrepoume to apotelesma se array
    //$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	while($row = mysqli_fetch_assoc($result)) {
		$_SESSION['userID'] = $row['ID'];
		break;
	}

	$sql_prof_query = "select Last_upload,Upload_counter from profile where ID = ".$_SESSION['userID'].";";
	$result_prof = mysqli_query($con, $sql_prof_query);
	while($row_prof = mysqli_fetch_assoc($result_prof)) {
		$_SESSION['last_upload'] = $row_prof['Last_upload'];
		$_SESSION['count'] = $row_prof['Upload_counter'];
	}
	
	
	//metrame ta rows gia na doume an einai adeia
    $count = mysqli_num_rows($result); 

	if($count == 1){
		if ($row['status'] == 'User'){
			header("Location:main_menu.html");
		}
		else {
			header("Location:Admin.html");
		}
    }  
    else{  
        echo "<h1> Login failed. Invalid username or password.</h1>";  
    } 
	
?> 
