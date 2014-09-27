<?php



/**************************************** CLUSTER ****************************************/
define('OFFSET', 268435456);
define('RADIUS', 85445659.4471);

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
			/*echo br."lat: ";var_dump( $target->lat);echo br."lng: ";var_dump( $target->lng);echo br."dist: ";
			var_dump(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $target->lat, $target->lng));echo br."bool: ";
			var_dump(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $target->lat, $target->lng) <= 1);*/
			if(haversineDistance($initialPoint['lat'], $initialPoint['lng'], $target->lat, $target->lng) <= 20){
		
            $pixels = pixelDistance($marker->lat, $marker->lng,
                                    $target->lat, $target->lng,
                                    $zoom);
            if ($distance > $pixels) {
                unset($markers[$key]);
                $cluster[] = $target;
            }
			}else{
                unset($markers[$key]);
			
			}
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
function mean_points($arr_points, $initialPoint){
	$clustered = cluster($arr_points, 20, 11, $initialPoint);
	$interest_points = array();
	foreach($clustered as $arrGroup){
		$sum = array( "lat"=>0, "lng"=>0 );
		$count = 0;
		if(is_array($arrGroup)){
			foreach($arrGroup as $point){
				$sum = array( "lat"=>($sum['lat']+$point->lat), "lng"=>($sum['lng']+$point->lng) );
				$count++;
			}
		}else{
			$count = 1;
			$sum = array( "lat"=>$arrGroup->lat, "lng"=>$arrGroup->lng );
		}
		$mean_sum = array("coord"=>array("lat"=>$sum['lat']/$count, "lng"=>$sum['lng']/$count), "total"=>$count);
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