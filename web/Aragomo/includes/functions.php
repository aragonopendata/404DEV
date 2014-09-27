<?php



/**************************************** CLUSTER ****************************************/
define('OFFSET', 268435456);
define('RADIUS', 85445659.4471);

function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}

function haversineDistance($lat1, $lng1, $lat2, $lng2) {
    $latd = deg2rad($lat2 - $lat1);
    $lngd = deg2rad($lng2 - $lng1);
    $a = sin($latd / 2) * sin($latd / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lngd / 2) * sin($lngd / 2);
         $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return 6371.0 * $c;
}
    
function lngToX($lng) {
    return round(OFFSET + RADIUS * $lng * pi() / 180);        
}

function latToY($lat) {
    return round(OFFSET - RADIUS * 
                log((1 + sin($lat * pi() / 180)) / 
                (1 - sin($lat * pi() / 180))) / 2);
}

function pixelDistance($lat1, $lng1, $lat2, $lng2, $zoom) {
    $x1 = lngToX($lng1);
    $y1 = latToY($lat1);

    $x2 = lngToX($lng2);
    $y2 = latToY($lat2);
        
    return sqrt(pow(($x1-$x2),2) + pow(($y1-$y2),2)) >> (21 - $zoom);
}

function cluster($markers, $distance, $zoom, $initialPoint) {
    $clustered = array();
    while (count($markers)) {
        $marker  = array_pop($markers);
        $cluster = array();
        foreach ($markers as $key => $target) {
			//if(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $target->lat, $target->lng) <= 5){
		
            $pixels = pixelDistance($marker->lat, $marker->lng,
                                    $target->lat, $target->lng,
                                    $zoom);
            if ($distance > $pixels) {
                unset($markers[$key]);
                $cluster[] = $target;
            }
			/*}else{
                unset($markers[$key]);
			
			}*/
        }

        if (count($cluster) > 0) {
            $cluster[] = $marker;
            $clustered[] = $cluster;
        } else {
            $clustered[] = $marker;
        }
    }
    return $clustered;
}
/**************************************** END CLUSTER ****************************************/



/**************************************** MEAN POINTS ****************************************/
function mean_points($arr_points, $initialPoint, $dist=10, $zoom=11){
	$clustered = cluster($arr_points, $dist, $zoom, $initialPoint);
	$interest_points = array();
	$hastags = array();
	$sum_hastags = array();
	foreach($clustered as $arrGroup){
		$sum = array( "lat"=>0, "lng"=>0 );
		$count = 0;
		if(is_array($arrGroup)){
			foreach($arrGroup as $point){
				$sum = array( "lat"=>($sum['lat']+$point->lat), "lng"=>($sum['lng']+$point->lng) );
				$count++;
				foreach($point->hashtags as $hash){
					if($key = array_search($hash, $hastags)){
						$sum_hastags[$key]++;
					}else{
						$hastags[] = $hash;
						$sum_hastags[] = 1;
					}
				}
			}
		}else{
			$count = 1;
			$sum = array( "lat"=>$arrGroup->lat, "lng"=>$arrGroup->lng );
			
			foreach($arrGroup->hashtags	as $hash){
				if($key = array_search($hash, $hastags)){
					$sum_hastags[$key]++;
				}else{
					$hastags[] = $hash;
					$sum_hastags[] = 1;
				}
			}
		}
		array_multisort($sum_hastags,SORT_DESC, $hastags);//I get repeat ones
		$hashtag_important = isset($hastags[0])?$hastags[0]:"";
		if(is_array($arrGroup)){
			$arrGroup_aux = $arrGroup;
		}else{
			$arrGroup_aux = array($arrGroup);
		}
		$arr_values = array();
		foreach($arrGroup_aux as $post){
			$value = 0;
			if(in_array($hashtag_important, $post->hashtags)){
				$value = 100/sizeOf($post->hashtags);
			}
			$post->value = $value;
			
			$arr_values[] = $value;
		}
		array_multisort($arr_values,SORT_DESC, $arrGroup_aux);
		
		$mean_sum = array("coord"=>array("lat"=>$sum['lat']/$count, "lng"=>$sum['lng']/$count), "total"=>$count, "arrGroup"=>$arrGroup_aux);
		$interest_points[] = $mean_sum;
	}
	return $interest_points;
}
/**************************************** END MEAN POINTS ****************************************/


/**************************************** POINTS ****************************************/
function points_proximity($arr_points){
	$clustered = cluster($arr_points, 1, 1);
	$interest_points = array();
	foreach($clustered as $arrGroup){
		$sum = array();
		$count = 0;
		if(is_array($arrGroup)){
			foreach($arrGroup as $point){
				$sum[] = array( "lat"=>$point->lat, "lng"=>$point->lng );
				$count++;
			}
		}else{
			$count = 1;
			$sum = array( "lat"=>$arrGroup->lat, "lng"=>$arrGroup->lng );
		}
		$mean_sum = array("coord"=>$sum, "total"=>$count);
		$interest_points[] = $mean_sum;
	}
	return $interest_points;
}
/**************************************** END POINTS ****************************************/

function selectData($initialPoint, $dist){
	$arr_data = array();
	
	$url = "http://155.210.71.103:2000/search/?lat=".$initialPoint['lat']."&lng=".$initialPoint['lng']."&distance=".($dist*1000)."&nPages=5";

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain')); 

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);
	
	//$result = file_get_contents("ta.json");
	$arrData = json_decode($result);
	
	//var_dump($arrData->flickr);
	
	$arr_data['twitter'] = isset($arrData->twitter)?mean_points($arrData->twitter, $initialPoint):array();
	$arr_data['instagram'] = isset($arrData->instagram)?mean_points($arrData->instagram, $initialPoint):array();
	$arr_data['flickr'] = isset($arrData->flickr)?mean_points($arrData->flickr, $initialPoint):array();
	$arr_data['places'] = isset($arrData->places)?$arrData->places:array();
	
	$twitter_arr = array();
	foreach($arr_data['twitter'] as $points){
		$point = $points['coord'];
		//var_dump($points['arrGroup']);
		//if(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $point['lat'], $point['lng']) <= 5){
			$twitter_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>$points['total'], "arrGroup"=>$points['arrGroup']);
		//}
	}
	$instagram_arr = array();
	foreach($arr_data['instagram'] as $points){
		$point = $points['coord'];
		//if(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $point['lat'], $point['lng']) <= 5){
			$instagram_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>$points['total'], "arrGroup"=>$points['arrGroup']);
		//}
	}
	$flickr_arr = array();
	foreach($arr_data['flickr'] as $points){
		$point = $points['coord'];
		//if(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $point['lat'], $point['lng']) <= 5){
			$flickr_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>$points['total'], "arrGroup"=>$points['arrGroup']);
		//}
	}
	$places_arr = array();
	if(isset($arr_data['place'])){
	foreach($arr_data['place'] as $points){
		//$point = $points['coord'];
		//$places_arr[]= array("lat"=>$point['lat'], "lng"=>$point['lng'], "total"=>1);
		$places_arr[]= array("lat"=>$points->lat, "lng"=>$points->lng, "total"=>1);
	}
	}
	
	return array("twitter"=>json_encode($twitter_arr), "instagram"=>json_encode($instagram_arr), "flickr"=>json_encode($flickr_arr), "places"=>json_encode($places_arr));
}

function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}