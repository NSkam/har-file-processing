<?php	

	header('Access-Control-Allow-Origin: *');
	include('logindb.php');

	//Pairnoume ta username kai password apo ta fields tou arxeiou html
	$password = $_POST['password_text'];  
	$username = $_POST['username_text'];  
	$email = $_POST['email_text'];
	$id = 0;
	$query = "SELECT ID FROM Usr ORDER BY ID DESC";
	$result = mysqli_query($con, $query);
	while($row = mysqli_fetch_assoc($result)) {
		$id = $row["ID"] + 1;
		break;
	}
	if ($id == 0) {
		$id = 1;
	}
	
	$sql = "INSERT INTO Usr (ID,username, password, email, status)
	VALUES (".$id.",'$username', '$password', '$email', 'User')";
	$sql_profile = "INSERT INTO profile VALUES (".$id.",'$username','$password','No&nbsp;Upload',0);";
	$result = mysqli_query($con, $sql);
	/*if ($con->query($sql) === TRUE) {
	echo "New record created successfully";
	} else {
	echo "Error: " . $sql_profile . "<br>" . $conn->error;
	}*/
	if ($con->query($sql_profile) === TRUE) {
	echo "Profile created successfully";
	header("Location:login.html");
	} else {
	echo "Error: " . $sql_profile . "<br>" . $conn->error;
	}
	
?>