<?php 
	include('logindb.php');
	$chartDataDate = [];
	$chartDataTimings = [];
	$getChartDataQuery = "SELECT startedDateTime, timings_wait FROM entry";
	if (strcmp($_POST['check'],"days") == 0){
		$days = [];
		#echo $_POST['check_list'][0];
		$days = $_POST['check_list'];
		#echo $days[0];
		for ($i = 0; $i < sizeof($days); $i++) {
			if(strcmp($days[$i],"Sunday")== 0){ 
			$days[$i] = 1;
			}
			if(strcmp($days[$i],"Monday")== 0){ 
			$days[$i] = 2;
			}
			if(strcmp($days[$i],"Tuesday")== 0){ 
			$days[$i] = 3;
			}
			if(strcmp($days[$i],"Wednesday")== 0){
			$days[$i] = 4;
				}
			if(strcmp($days[$i],"Thursday")== 0){ 
			$days[$i] = 5;
			}
			if(strcmp($days[$i],"Friday")== 0){ 
			$days[$i] = 6;
			}
			if(strcmp($days[$i],"Saturday")== 0){ 
			$days[$i] = 7;
			}
		
		}
		$days_str = implode(",",$days);
		#echo $days_str;
		$query_string = "(" . $days_str . ")";
		#echo $query_string;
		$getChartDataQuery = "SELECT startedDateTime, timings_wait from entry 
				WHERE DAYOFWEEK(startedDateTime) in $query_string";
	}
	
	if (strcmp($_POST['check'],"method") == 0){
		
		$method = [];
		$method = $_POST['check_list'];
		$query_string = "(";
		for ($i=0;$i<sizeof($method);$i++){
			if($i == sizeof($method)-1){
		$query_string.="'".$method[$i]."'";
		}else{
			$query_string.="'".$method[$i]."',";
			}
		}
		$query_string .= ")";
		//echo $query_string;
		$getChartDataQuery = "SELECT startedDateTime, timings_wait from entry 
				INNER JOIN request ON entry.request_id = request.request_id
                               	WHERE request.method IN $query_string";
	}
	
		if (strcmp($_POST['check'],"provider") == 0){
		
		$provider = [];
		$provider = $_POST['check_list'];
		$query_string = "(";
		for ($i=0;$i<sizeof($provider);$i++){
			if($i == sizeof($provider)-1){
		$query_string.="'".$provider[$i]."'";
		}else{
			$query_string.="'".$provider[$i]."',";
			}
		}
		$query_string .= ")";
		//echo $query_string;
		$getChartDataQuery = "SELECT startedDateTime, timings_wait from entry 
				INNER JOIN request ON entry.request_id = request.request_id
                               	WHERE connection_provider IN $query_string";
	}
	$result = mysqli_query($con, $getChartDataQuery);
	
	while($row = mysqli_fetch_assoc($result)){
		$chartData = array(
			"startDate" => $row['startedDateTime'],
			"timing"         => $row['timings_wait'],
		);
	
	$chartDataAll[] = $chartData;
	}
	
	echo json_encode($chartDataAll);
?>