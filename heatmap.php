<?php 
	session_start();
	include('logindb.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	//$ip = "141.237.150.208";
	
	$url_ip_list = [];
	$get_number_of_headers_query = "SELECT MAX(request_id) FROM request";
	$result = mysqli_query($con, $get_number_of_headers_query);
	$row = mysqli_fetch_assoc($result);
	$no_of_requests = $row["MAX(request_id)"];
	//echo strval($no_of_requests);
	
	for ($i = 1; $i <= $no_of_requests; $i++) {
	$get_url_query = "SELECT url FROM request WHERE request_id ='$i'";
	$result = mysqli_query($con, $get_url_query);
	$row = mysqli_fetch_assoc($result);
	$url = $row["url"];
	//echo $url;
	
	$url_ip = gethostbyname($url);
	array_push($url_ip_list, $url_ip);
	}
	
	/*foreach($url_ip_list as $url){
		echo $url . "\n";
	}*/
?>

<html>
<head>
	<title>Heatmap</title>
	<link rel = "stylesheet" type = "text/css" href = "heatmap_style.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
	integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
	crossorigin=""/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
	integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
	crossorigin=""></script>
	<script src="heatmap.js"></script>
	<script src="leaflet-heatmap.js"></script>
</head>

<body>
<div class = "box">
<div id="mapid"></div>
<button onClick="window.location.href = 'http://localhost/login/main_menu.html'" id = "back">back</button>
</div>
<script>
	var ip = "<?php echo $ip; ?>" ;
	console.log(ip);
	var heatmap_ip_list = <?php echo json_encode($url_ip_list); ?> ;
	//console.log(heatmap_ip_list);
	var endpoint = "http://ip-api.com/json/"+ip+"?fields=58071";
	var lat_list = [];
	var lon_list =[];
	var count_list=[];

	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var response = JSON.parse(this.responseText);
			if(response.status !== 'success') {
				console.log('query failed: ' + response.message);
				return
			}
		}
		console.log(response.lat);
		console.log(response.lon);
		
			var mymap = L.map('mapid').setView([response.lat, response.lon], 15);

	var baseLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1Ijoic292ZXJlaWduOTQ5Mjk1IiwiYSI6ImNrdGVsOXA5ZTA1MTMycG53MWxmNWpoaWQifQ.lOCf-ayXgw0LPV02FI6E_Q'
}).addTo(mymap);
	
	var xhr_heatmap =[];

for (let i = 0; i< 10; i++){
	var heatmap_endpoint= "http://ip-api.com/json/"+heatmap_ip_list[i]+"?fields=24768"
	//console.log(heatmap_endpoint);
	xhr_heatmap[i] = new XMLHttpRequest();
	xhr_heatmap[i].open('GET', heatmap_endpoint, true);
	xhr_heatmap[i].onreadystatechange = function() {
		if (xhr_heatmap[i].readyState == 4 && xhr_heatmap[i].status == 200) {
			var response = JSON.parse(xhr_heatmap[i].responseText);
			if(response.status !== 'success') {
				console.log('query failed: ' + response.message);
				return
			}
			
				//console.log(response.lat);
				//console.log(response.lon);
			if (!lat_list.includes(response.lat) || !lon_list.includes(response.lon)){
				
				 lat_list.push(response.lat);
				 lon_list.push(response.lon);
				 count_list.push(1);
				
			}else{
				
				var index = lat_list.findIndex(function(lat, lon) { if(lat === response.lat){ return lon === response.lon} else{return -1}});
				count_list[index] = count_list[index] + 1;
				
			}
		}
		if(i===9){
		//console.log(lat_list);
		//console.log(lon_list);
		//console.log(count_list);
		makeHeatMap(lat_list, lon_list,count_list);
		}
	};
		xhr_heatmap[i].send();
	}

function makeHeatMap(lat,lon,count){
	var heatmap_data =[];
	for (let i =0; i<lat.length; i++){
		heatmap_entry = {};
		heatmap_entry["lat"] = lat[i];
		heatmap_entry["lng"] = lon[i];
		heatmap_entry["count"] = count[i];
		heatmap_data.push(heatmap_entry);
	}
		//console.log(heatmap_data);
		data = {
		max: 15,
		data: heatmap_data
		};
	var cfg = {

	"radius": 2,
	"maxOpacity": .8,
	"scaleRadius": true,
	"useLocalExtrema": true,
	latField: 'lat',
	lngField: 'lng',
	valueField: 'count'
	};
	var heatmapLayer = new HeatmapOverlay(cfg);
	
	var baseL = {
		
		"Base": baseLayer
		
	}
	
	var overlayMaps = {
    "Heatmap": heatmapLayer
	};
	L.control.layers(null,overlayMaps).addTo(mymap);
	heatmapLayer.setData(data);
	
}
	
	
	};
	
xhr.open('GET', endpoint, true);
xhr.send();




</script>

</body>
</html>