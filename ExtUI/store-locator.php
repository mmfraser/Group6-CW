<html>
<head>
<title>Maps</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>


<script type="text/javascript">
	 
	var StoreFinder = (new function(){
	 
	// config
	var showAllLocationsOnStart = true;
	 
	// @PRIVATE variables
	var userAddress, markers = [],
	image = 'http://cdn1.iconfinder.com/data/icons/fatcow/32x32_0440/flag_red.png',
	stores = [
	{lat:55.94626811443313, lng:-3.0794334411621094, name:'Edinburgh Store'},
	{lat:55.870880722982676, lng:-4.308958053588867, name:'Glasgow Store'},
	{lat:51.48842223816589, lng:-0.10409586131572723, name:'London Store'},
	{lat:56.483633905539676, lng:-2.9819297790527344, name:'Dundee Store'}
	];
	 
	 
	/* Initialize GMaps ***********************************************/
	this.the_map;
	this.initialize = function(){
	var usCenter = new google.maps.LatLng(54.67383096593114, -2.6806640625),
	myOptions = {zoom:4,center: usCenter,mapTypeId:google.maps.MapTypeId.ROADMAP};
	 
	StoreFinder.the_map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	 
	var storeCount = stores.length;
	for(i=0; i < storeCount; i++){
	var marker = new google.maps.Marker({position: new google.maps.LatLng(stores[i].lat,stores[i].lng),title:stores[i].name,icon: image})
	markers.push( marker )
	if(showAllLocationsOnStart){ marker.setMap(StoreFinder.the_map); }
	}
	}
	/* End Initialize *************************************************/
	 
	 
	// @PRIVATE
	function haversineDistance(p1, p2) {
	function rad(x) {return x*Math.PI/180;}
	var R = 3958.75587;
	var dLat = rad( (p2.lat-p1.lat) );
	var dLon = rad( (p2.lng-p1.lng) );
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
	Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) *
	Math.sin(dLon/2) * Math.sin(dLon/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;
	return d;
	}
	 
	// @PRIVATE get distance between two markers (GMARKER OBJECTS)
	function getDist(marker1,marker2){
	var p1 = { lat:marker1.position.za, lng:marker1.position.Ba },
	p2 = { lat:marker2.position.za, lng:marker2.position.Ba };
	return haversineDistance(p1, p2);
	}
	 
	// @PUBLIC clear all markers, then display all store locations
	this.showAllLocations = function(){
	var storeCount = markers.length;
	for(i=0; i < storeCount; i++){
	markers[i].setMap(null);
	markers[i].setMap(StoreFinder.the_map);
	}
	var usCenter = new google.maps.LatLng(38.4391222, -98.9465077);
	StoreFinder.the_map.setCenter(usCenter);
	StoreFinder.the_map.setZoom(4);
	}
	 
	// @PUBLIC - geocode person's address (from form inputs), calculate distance to stores,
	// then display those within X miles
	this.geoCode = function(userLocation,miles){
	var geocoder = new google.maps.Geocoder();
	var _stores = markers; //@IMPORTANT: markers is the array of instantiated Gmarker objects (don't use the STORES variable)
	geocoder.geocode({'address':userLocation},function(results,status){
	if(userAddress === null || userAddress === undefined){
	userAddress = new google.maps.Marker({
	map:StoreFinder.the_map,
	position:results[0].geometry.location
	})
	}else{
	userAddress.setMap(null);
	userAddress = new google.maps.Marker({
	map:StoreFinder.the_map,
	position:results[0].geometry.location
	})
	}
	 
	StoreFinder.the_map.setCenter( new google.maps.LatLng(userAddress.position.za, userAddress.position.Ba) );
	StoreFinder.the_map.setZoom(5);
	 
	var storeCount = _stores.length,
	results = 0;
	for(i=0; i < storeCount; i++){
	_stores[i].setMap(null);
	if( getDist(_stores[i],userAddress) < miles ){
	_stores[i].setMap(StoreFinder.the_map);
	results++;
	}
	}
	 
	var str = results+' store(s) found within '+miles+' miles of your location'
	$('#results').empty().append( str );
	})
	}
	 
	})
	 
	$(document).ready(function(){
	$('#send').click(function(){
	var location = $('#sl-city').val(),
	miles = $('#sl-miles').val();
	if(location.length > 5){ StoreFinder.geoCode(location,miles); }else{ StoreFinder.showAllLocations(); }
	})
	})
 
 
 
</script>

<style type="text/css">
	html { height: 100% }
	body { height: 100%; margin: 0px; padding: 0px }
	#map_canvas { height: 100% }
	 
	 
	#store_locator_sorting {float:left;width:200px;padding:5px;margin-right:10px;}
	#store_locator_sorting label {display:block;margin-bottom:8px;}
	#store_locator_sorting label span {display:block;}
</style>

</head>


<body onload="StoreFinder.initialize()" style="padding:10px;">
 
<div id="store_locator_sorting">
<label for="sl-state"><span>State</span>
<select id="sl-state" name="sl-state">
<option value=""></option>
<option value="Maryland">Maryland</option>
</select>
</label>
 
<label for="sl-city"><span>City</span>
<input id="sl-city" name="sl-city" />
</label>
 
<label for="sl-zip"><span>Zip</span>
<input id="sl-zip" name="sl-zip" style="width:60px;" />
</label>
 
<label for="sl-miles"><span>Within</span>
<select id="sl-miles" name="sl-miles">
<option value="25">25 Miles</option>
<option value="50">50 Miles</option>
<option value="100">100 Miles</option>
<option value="200">200 Miles</option>
<option value="500">500 Miles</option>
</select>
</label>
 
<button id="send" type="button" >Find</button> <span style="font-size:11px;text-transform:uppercase;cursor:pointer;">( <a onclick="StoreFinder.showAllLocations();">Reset Map</a> )</span>
</div>
 
<div id="map_canvas" style="float:left;width:750px;height:530px;border:5px solid #ddd;"></div>
 
<div id="results" style="float:left;"></div>
 
</body>

