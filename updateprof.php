<?php  
	
	session_start();
	include('logindb.php');
	
	$new_username = $_POST['username'];
	$new_password = $_POST['password'];
	
	$user_ID = $_SESSION['userID'];
	$_SESSION['username'] = $new_username;
	$_SESSION['password'] = $new_password;
 
	$sql = "UPDATE Usr SET username = '$new_username', password = '$new_password'  WHERE ID=$user_ID";
	$sql_profile = "UPDATE profile SET username = '$new_username', password = '$new_password' WHERE ID = $user_ID;";
	 
	 if ($_SESSION['con']->query($sql) === TRUE) {
		echo "Record updated successfully";
	 } else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	 }
	 if ($_SESSION['con']->query($sql_profile) === TRUE) {
		echo "Record updated successfully";
	 } else {
		echo "Error: " . $sql_profile . "<br>" . $conn->error;
	 }
	 ?>