<?php
require_once("includes/functions.php");
$initialPoint = array("lat"=> $_GET['lat'], "lng"=> $_GET['lng']);

if(isset($_GET['lat'])){
	$arr_data = selectData($initialPoint, 5);
}
?>

<!DOCTYPE HTML>

<html>

<head>
<meta charset="UTF-8">

<!-- Favicon -->
<link rel="icon" href="css/img/favicon.png">

<!-- General CSS -->
<link rel="stylesheet/less" href="css/aragomo.less">

<!-- Vendor JS libraries  -->
<script src="vendor/less/less-1.7.5.min.js"></script>
<script src="vendor/jQuery/jquery-1.11.1.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>

<!-- Vendor CSS libraries -->
<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet"
	href="vendor/bootstrap/css/bootstrap-theme.min.css">

<!-- General JS -->
<script src="js/aragomo.js"></script>
	
<script>
var twitter = <?php echo $arr_data['twitter'] ?>;
var instagram = <?php echo $arr_data['instagram'] ?>;
var flickr = <?php echo $arr_data['flickr'] ?>;
$data_modal = '';
function initialize() {
	var myLatlngCenter = new google.maps.LatLng(<?php echo $initialPoint['lat'].", ".$initialPoint['lng']?>);
	var mapOptions = {
		zoom: 14,
		center: myLatlngCenter
	}
	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	/*FE6256*/
	
	var marker, i;
	for (i = 0; i < twitter.length; i++){
		var myLatlng = new google.maps.LatLng(twitter[i]['lat'],twitter[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_red.png';
		console.log(twitter[i]['arrGroup']);
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			custom: twitter[i]['arrGroup'],
			title: twitter[i]['total'].toString()
		});
		google.maps.event.addListener(marker, 'click', function() {
			$data_modal = this.custom;
			$('#myModal').find('.modal-body-list').empty();
			console.log(this.custom);
			for(var i=0; i<this.custom.length; i++){
				var style_photo = "style='background-image: none;'";
				if(this.custom['thumbnail']!=null && this.custom['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom[i]['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			if(i==0){
				var style_photo = "style='background-image: none;'";
				if(this.custom['thumbnail']!=null && this.custom['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			click_2();
			$('#myModal').modal('show');
		});
	}
	var marker, i;
	for (i = 0; i < instagram.length; i++){
		var myLatlng = new google.maps.LatLng(instagram[i]['lat'],instagram[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_blue.png';
		console.log(instagram[i]['arrGroup']);
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			custom: instagram[i]['arrGroup'],
			title: instagram[i]['total'].toString()
		});
		google.maps.event.addListener(marker, 'click', function() {
			$data_modal = this.custom;
			$('#myModal').find('.modal-body-list').empty();
			console.log(this.custom);
			for(var i=0; i<this.custom.length; i++){
				var style_photo = "style='background-image: none;'";
				if(this.custom[i]['thumbnail']!=null && this.custom[i]['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom[i]['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom[i]['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			if(i==0){
				var style_photo = "style='background-image: none;'";
				if(this.custom['thumbnail']!=null && this.custom['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			click_2();
			$('#myModal').modal('show');
		});
	}
	var marker, i;
	for (i = 0; i < flickr.length; i++){
		var myLatlng = new google.maps.LatLng(flickr[i]['lat'],flickr[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_blue.png';
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			custom: flickr[i]['arrGroup'],
			title: flickr[i]['total'].toString()
		});
		google.maps.event.addListener(marker, 'click', function() {
			$data_modal = this.custom;
			$('#myModal').find('.modal-body-list').empty();
			console.log(this.custom);
			for(var i=0; i<this.custom.length; i++){
				var style_photo = "style='background-image: none;'";
				if(this.custom[i]['thumbnail']!=null && this.custom[i]['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom[i]['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom[i]['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			if(i==0){
				var style_photo = "style='background-image: none;'";
				if(this.custom['thumbnail']!=null && this.custom['thumbnail']!=""){
					style_photo = "style='background-image: url("+this.custom['thumbnail']+")'";
				}
				var li = "<li class='photo' data-id='"+i+"' "+style_photo+">"+this.custom['description']+"</li>";
				$('#myModal').find('.modal-body-list').append(li);
			}
			click_2();
			$('#myModal').modal('show');
		});
	}/*
	var marker, i;
	for (i = 0; i < locs_3.length; i++){
		var myLatlng = new google.maps.LatLng(locs_3[i]['lat'],locs_3[i]['lng']);
		var url='http://www.creatiusgirona.com/proves/marker_amarillo.png';
		/*if(locs[i][2]>1){
			url='http://www.creatiusgirona.com/proves/marker_blue.png';
		}*//*
		marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			icon: url,
			title: locs_3[i]['total'].toString()
		});
	}*/
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>

<title>Aragomo</title>
</head>

<body class="aragomo">
	<?php include 'includes/header.html';?>
	
	<div class="body-content" id="map-canvas">&nbsp;</div>
	
	<?php include 'includes/modal.html';?>
	
	<?php include 'includes/modal2.html';?>
	
	<?php include 'includes/footer.html';?>
</body>

</html>