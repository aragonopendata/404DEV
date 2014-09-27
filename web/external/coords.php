<?php
require_once("functions.php");
define("br", "<br><br>");

include_once("arr.php");
/*
$postdata = http_build_query(
    array(
        'lat' => '42.034553',
        'lng' => '0.125476',
        'distance' => '500'
    )
);

$opts = array('http' =>
    array(
        'method'  => 'GET',
        'content' => $postdata
    )
);

$context  = stream_context_create($opts);

$result = file_get_contents('http://10.6.9.125:3000/search/?lat=42.034553&lng=0.125476&distance=500');*/
/*
$url = "http://10.6.9.125:3000/search/?lat=42.034553&lng=0.125476&distance=500";

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain')); 

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

var_dump($result);
die;*/

$arrData = json_decode($arrString);

$initialPoint = array("lat"=>42.035861, "lng"=>0.123653);
//var_dump($arrData->twitter[0]->data);
/*print_r(mean_points($arrData->twitter[0]->data));*//*
$twitter = mean_points($arrData->twitter, $initialPoint);
$instagram = mean_points($arrData->instagram, $initialPoint);
$places = mean_points($arrData->places, $initialPoint);*/
$twitter = $arrData->twitter;
$instagram = $arrData->instagram;
$places = $arrData->places;
var_dump($arrData->places);
/*
$twitter_arr = array();
foreach($twitter as $points){
	$point = $points['coord'];
	$twitter_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);
}
$instagram_arr = array();
foreach($instagram as $points){
	$point = $points['coord'];
	$instagram_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);
}*/
$instagram_arr = array();
foreach($instagram as $points){
	//$point = $points['coord'];
	/*$places_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);*/
	$instagram_arr[]= array("lat"=>$points->lat, "lng"=>$points->lng, "total"=>1);
}
$twitter_arr = array();
foreach($twitter as $points){
	//$point = $points['coord'];
	/*$places_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);*/
	$twitter_arr[]= array("lat"=>$points->lat, "lng"=>$points->lng, "total"=>1);
}
$places_arr = array();
foreach($places as $points){
	//$point = $points['coord'];
	/*$places_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);*/
	$places_arr[]= array("lat"=>$points->lat, "lng"=>$points->lng, "total"=>1);
}

/*
$mean_points_aux = array(new stdClass());
$i = 0;
foreach($mean_points as $points){
	$point = $points['coord'];
	$mean_points_aux[$i] = new stdClass();
	$mean_points_aux[$i]->lat = $point['lat'];
	$mean_points_aux[$i]->lng = $point['lng'];
	$i++;
}
$arr_aux_merge = array_merge($mean_points_aux, $arrData->places[0]->data);
$mean_points_2 = mean_points($arr_aux_merge);*/
//var_dump($mean_points_2);
//$mean_points_2 = mean_points($arr_aux_merge);
//var_dump($mean_points_2);
/*
$arr_javascript = "[";
$arr_javascript_num = "[";
foreach($mean_points as $points){
	$point = $points['coord'];
	$arr_javascript .= "[".$point['lat'].", ".$point['lng'].", ".$points['total']."],";
	$arr_javascript_num .= $points['total'].",";
}
$arr_javascript .= "]";
$arr_javascript_num .= "]";*/
/*
$arr_javascript = array();
foreach($mean_points as $points){
	$point = $points['coord'];
	$arr_javascript[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);
}
$arr_javascript = json_encode($arr_javascript);

$arr_javascript_2 = array();
foreach($mean_points_2 as $points){
	//var_dump($points);
	$point = $points['coord'];
	//var_dump($point);
	$arr_javascript_2[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);
}
$arr_javascript_2 = json_encode($arr_javascript);

$arr_javascript_places = array();
foreach($arrData->places[0]->data as $points){
	$arr_javascript_places[]= array("lat"=>$points->lat, "lng"=>$points->lng, "total"=>1);
}

$arr_places = json_encode($arr_javascript_places);*/
//echo $arr_places;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script>
function initialize() {
	var locs = <?php echo json_encode($twitter_arr);?>;
	var locs_2 = <?php echo json_encode($instagram_arr);?>;
	var locs_3 = <?php echo json_encode($places_arr);?>;
	
  var myLatlng = new google.maps.LatLng(locs_3[0]['lat'],locs_3[0]['lng']);
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	/*FE6256*/
	
	var marker, i;
	console.log( locs.length);
	for (i = 0; i < locs.length; i++){
		var myLatlng = new google.maps.LatLng(locs[i]['lat'],locs[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_red.png';
		/*if(locs[i][2]>1){
			url='http://www.creatiusgirona.com/proves/marker_blue.png';
		}*/
		console.log(locs[i]['lat']+","+locs[i]['lng']);
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			title: locs[i]['lat']+","+locs[i]['lng']
		});
	}
	var marker, i;
	for (i = 0; i < locs_2.length; i++){
		var myLatlng = new google.maps.LatLng(locs_2[i]['lat'],locs_2[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_blue.png';
		/*if(locs[i][2]>1){
			url='http://www.creatiusgirona.com/proves/marker_blue.png';
		}*/
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			title: locs_2[i]['lat']+","+locs_2[i]['lng']
		});
	}
	var marker, i;
	for (i = 0; i < locs_3.length; i++){
		var myLatlng = new google.maps.LatLng(locs_3[i]['lat'],locs_3[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_amarillo.png';
		/*if(locs[i][2]>1){
			url='http://www.creatiusgirona.com/proves/marker_blue.png';
		}*/
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			title: locs_3[i]['lat']+","+locs_3[i]['lng']
		});
	}
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
  </head>
  <body>
    <div id="map-canvas"></div>
  </body>
</html>
