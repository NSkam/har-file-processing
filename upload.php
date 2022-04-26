<?php
	session_start();
    include('logindb.php');
	date_default_timezone_set("Europe/Athens");

	
	$user_ID = $_SESSION['userID'];
	$result = '';
	$file_id_query = "SELECT file_id FROM har order by file_id DESC";
	
	$result = mysqli_query($con, $file_id_query);
	$file_id = 0;
	$count = 0;
	while($row = mysqli_fetch_assoc($result)) {
		$file_id = $row["file_id"] + 1;
		break;
	}
	if ($file_id == 0) {
		$file_id = 1;
	}
	
	$upload_counter_query = "SELECT Upload_counter FROM profile where ID = ".$user_ID.";";
	$result = mysqli_query($con, $upload_counter_query);
	while($row = mysqli_fetch_assoc($result)) {
		$count = $row["Upload_counter"] + 1;
		break;
	}
	
	$curr_date = date("d/m/y");
	$curr_time = date("h:i:sa"); 
	
	$_SESSION["last_upload"] = $curr_date."&nbsp;".$curr_time;
	$_SESSION["count"] = $count;
	
	$file = $_POST['har'];
	$filename = $_POST['name'];
	$username = $_SESSION['username'];
	
	$json = json_decode($file);
	$sql_query = "INSERT INTO har VALUES (".$file_id.",".$user_ID.",'".$username."','".$filename."');";
	$sql_profile = "UPDATE profile SET Last_upload = '".$curr_date."&nbsp;".$curr_time."', Upload_counter = ".$count." WHERE ID = ".$user_ID.";";
	 
	mysqli_query($con,$sql_query);
	mysqli_query($con,$sql_profile);
	
	$arr_request = array();
	$arr_response = array();
	$request_header_id = 0;
    $response_header_id = 0;
	
	foreach ($json->log->entries as $row) {
		if ($row == "startedDateTime");{
			$sql_start = "INSERT INTO entry VALUES (".$user_ID.",".$file_id.",".$request_header_id.",".$response_header_id .",'".$row->startedDateTime."',".$row->timings->wait.",'".$row->serverIPAddress."','".$_SESSION['isp']."');";  // Make Multiple Insert Query 
			mysqli_query($con,$sql_start);
		}
		foreach ($row->request->headers as $request) {
			//if ($request->name == "Content-Type" || $request->name == "Cache-Control" || $request->name == "Pragma" || $request->name == "Expires" || $request->name == "Age"  || $request->name == "Last-Modified" || $request->name = "Host"){
				$sql1 = "INSERT INTO Request VALUES (".$user_ID.",".$file_id.",".$request_header_id.",'".json_encode($request)."','".$row->request->method."','".$row->request->url."');";  // Make Multiple Insert Query 
                mysqli_query($con,$sql1);
			//}
			
		}
		foreach ($row->response->headers as $response) {
			//if ($response->name == "Content-Type" || $response->name == "Cache-Control" || $response->name == "Pragma" || $response->name == "Expires" || $response->name == "Age"  || $response->name == "Last-Modified" || $response->name = "Host"){
				$sql2 = "INSERT INTO Response VALUES (".$user_ID.",".$file_id.",".$response_header_id.",'".json_encode($response)."','".$row->response->status."','".$row->response->statusText."');";  // Make Multiple Insert Query   // Make Multiple Insert Query 
				mysqli_query($con,$sql2);
			//}
			
		}
		$request_header_id = $request_header_id + 1;
		$response_header_id = $response_header_id + 1;
	}
	$json_request = json_encode($arr_request);
	$json_response = json_encode($arr_response);
	//echo $json_request;
	
    
	/*if ($con->query($sql_query) === TRUE) {
	echo "New record created successfully";
	echo "username is ".$username;
	echo "file id is ".$file_id;
	echo "filename is ".$filename;
	echo "user id is ".$user_ID;
	} else {
	echo "Error: " . $sql . "<br>" . $con->error;
	}*/
?>