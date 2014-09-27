<?php
require_once("functions.php");
define("br", "<br><br>");

$arrString = <<<EOF
{
   "twitter" : [
      {
         "data" : [
			{
				"lat": 59.441193,
				"lng": 24.729494
			},
			{
				"lat": 59.432365,
				"lng": 24.742992
			},
			{
				"lat": 59.431602,
				"lng": 24.757563
			},
			{
				"lat": 59.437843,
				"lng": 24.765759
			},
			{
				"lat": 59.439644,
				"lng": 24.779041
			},
			{
				"lat": 59.434776,
				"lng": 24.756681
			}
         ]
      }
   ],
   "places" : [
			   {
				  "lat" : 42.035986,
				  "name" : "Catedral de Barbastro",
				  "lng" : 0.122348
			   },
			   {
				  "lat" : 42.035861,
				  "name" : "Hotel San RamÃ³n del Somontano",
				  "lng" : 0.123653
			   },
			   {
				  "lat" : 42.037663,
				  "name" : "Mi Casa",
				  "lng" : 0.130003
			   },
			   {
				  "lat" : 42.036487,
				  "name" : "Gran Hotel Ciudad De Barbastro",
				  "lng" : 0.125267
			   },
			   {
				  "lat" : 42.036272,
				  "name" : "Hotel Clemente",
				  "lng" : 0.127821
			   },
			   {
				  "lat" : 42.0366,
				  "name" : "Restaurante Flor",
				  "lng" : 0.130492
			   },
			   {
				  "lat" : 42.036182,
				  "name" : "Hostal Palafox",
				  "lng" : 0.127828
			   },
			   {
				  "lat" : 42.035995,
				  "name" : "MesÃ³n Muro",
				  "lng" : 0.128251
			   },
			   {
				  "lat" : 42.036195,
				  "name" : "Hostal Restaurante Pirineos",
				  "lng" : 0.126703
			   },
			   {
				  "lat" : 42.035388,
				  "name" : "PastelerÃ­a IRIS",
				  "lng" : 0.122326
			   },
			   {
				  "lat" : 42.03652,
				  "name" : "UNED Barbastro - FundaciÃ³n RamÃ³n J. Sender.",
				  "lng" : 0.124376
			   },
			   {
				  "lat" : 42.034038,
				  "name" : "Ruta del Vino Somontano",
				  "lng" : 0.120211
			   },
			   {
				  "lat" : 42.035317,
				  "name" : "Vinobar",
				  "lng" : 0.121912
			   },
			   {
				  "lat" : 42.034889,
				  "name" : "Sabeco",
				  "lng" : 0.127342
			   },
			   {
				  "lat" : 42.036185,
				  "name" : "Trasiego vinos y tapas - restaurante",
				  "lng" : 0.124239
			   },
			   {
				  "lat" : 42.038112,
				  "name" : "RincÃ³n del Somontano",
				  "lng" : 0.126524
			   },
			   {
				  "lat" : 42.033761,
				  "name" : "Centro Privado de EnseÃ±anza San Vicente de PaÃºl",
				  "lng" : 0.119889
			   },
			   {
				  "lat" : 42.036844,
				  "name" : "El Placer",
				  "lng" : 0.130551
			   },
			   {
				  "lat" : 42.036324,
				  "name" : "Bbva",
				  "lng" : 0.126666
			   },
			   {
				  "lat" : 42.03594,
				  "name" : "Restaurante Bodega del Vero",
				  "lng" : 0.12519
			   }
   ]
}
EOF;

$postdata = http_build_query(
    array(
        'lat' => '42.034553',
        'lng' => '0.125476',
        'distance' => '500'
    )
);

$arrData = json_decode($arrString);

//var_dump($arrData->twitter[0]->data);
				   
/*print_r(mean_points($arrData->twitter[0]->data));*/
$mean_points = mean_points($arrData->twitter[0]->data);
//var_dump($mean_points);
$arr_javascript = "[";
$arr_javascript_num = "[";
foreach($mean_points as $points){
	$point = $points['coord'];
	$arr_javascript .= "[".$point['lat'].", ".$point['lng'].", ".$points['total']."],";
	$arr_javascript_num .= $points['total'].",";
}
$arr_javascript .= "]";
$arr_javascript_num .= "]";

$arr_places = json_encode((array)$arrData->places);
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
	var locs = <?php echo $arr_places;?>;
  var myLatlng = new google.maps.LatLng(locs[0]['lat'],locs[0]['lng']);
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	/*FE6256*/
	
	var marker, i;
	for (i = 0; i < locs.length; i++){
		var myLatlng = new google.maps.LatLng(locs[i]['lat'],locs[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_red.png';
		/*if(locs[i][2]>1){
			url='http://www.creatiusgirona.com/proves/marker_blue.png';
		}*/
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			title: locs[i]['name']
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
