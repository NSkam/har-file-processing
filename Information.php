<?php session_start();
	include("logindb.php");
	$sql = "SELECT count(id) from Usr";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_assoc($result)) {
		$_SESSION['Users'] = $row["count(id)"];
	}
	$sql = "SELECT method, count(method) from request GROUP BY method;";
	$result = mysqli_query($con, $sql);
	
	$string = '';
	while($row = mysqli_fetch_assoc($result)) {
		$string .= $row['method'].":&nbsp;".$row['count(method)']."<br>";
	}
	$_SESSION['Methods'] = $string;
	
	$sql = "SELECT status, count(status) from response GROUP BY status;";
	$result = mysqli_query($con, $sql);
	
	$string = '';
	while($row = mysqli_fetch_assoc($result)) {
		$string .= "Status&nbsp;code->".$row['status'].":&nbsp;&nbsp;&nbsp;".$row['count(status)']."<br>";
	}
	$_SESSION['Status'] = $string;
	
	$sql = "SELECT count(url) from request GROUP BY url;";
	$result = mysqli_query($con, $sql);
	while($row = mysqli_fetch_assoc($result)) {
		$_SESSION['Unique Domains'] = $row['count(url)'];
	}
	$sql = "SELECT connection_provider,count(connection_provider) from entry GROUP BY connection_provider;";
	$result = mysqli_query($con, $sql);
	$string = '';
	while($row = mysqli_fetch_assoc($result)) {
		$string .= $row['connection_provider'].":&nbsp;".$row['count(connection_provider)']."<br>";
	}
	$_SESSION['Unique Providers'] = $string;
	
	$sql = "SELECT response.response->>'$.value' as 'Content type',avg(a.response->>'$.value') as Age 
			from (SELECT * from response where response_id IN (SELECT response_id from response 
                                                   where response->>'$.name' IN ('Age','age')) and response->>'$.name' IN ('Age','age','Content-Type','content-type')) a
                                                   INNER JOIN response ON a.response_id = response.response_id
                                                   where a.response_id = response.response_id 
                                                   and response.response->>'$.name' IN ('Content-Type','content-type')
                                                   and a.response->>'$.name' IN ('Age','age')
                                                   GROUP BY response.response->>'$.value';";
	$result = mysqli_query($con, $sql);
	$isp_string = '';
	while($row = mysqli_fetch_assoc($result)) {
		$isp_string .= $row['Content type']."->&nbsp;&nbsp;&nbsp;".$row['Age']."<br>";
	}
	$_SESSION['Average Age'] = $isp_string;
?>

<html>

<head>
	<title>Information</title>
	<link rel = "stylesheet" type = "text/css" href = "Information.css">   
</head>

<body>
<div class ="box">
<table class = "Info">
  <tr class = "Table_names">
    <th id = "Users">Users</th>
    <th id = "Methods">Methods</th>
    <th id = "Status">Status</th>
    <th id = "Unique-Domains">Unique Domains</th>
    <th id = "Unique-Providers">Unique Providers</th>
    <th id = "Average-Age">Average Age</th>
  </tr>
  <tr class = "Table_Values">
    <td id = "Users-val"><?php echo $_SESSION['Users'] ?></td>
    <td id = "Methods-val"><?php echo $_SESSION['Methods'] ?></td>
    <td id = "Status-val"><?php echo $_SESSION['Status'] ?></td>
    <td id = "Unique-Domains-val"><?php echo $_SESSION['Unique Domains'] ?></td>
    <td id = "Unique-Providers-val"><?php echo $_SESSION['Unique Providers'] ?></td>
	<td id = "Average-Age-val"><?php echo $_SESSION['Average Age'] ?></td> 
  </tr> 
</table>
<button onClick="window.location.href = 'http://localhost/login/Admin.html'" id = "back">back</button>
</div>
</body>
	
</html>