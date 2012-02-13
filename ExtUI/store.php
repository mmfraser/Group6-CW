<?php

	// Page PHP Backend Code Begin
		include_once("page.php");
		$page = new Page();
		$page->title = "Quicksilver Music";
		$page->getHeader();
		$errorMsg = null;
		$infoMsg = null;
		
		$id = $_REQUEST['storeId'];
		
		////////////////////////////////////////////////////////
		
		include ("AppClasses/db_conn.php");
		dbConnect("0", "00");
		dbSelect("sales");
		$store = mysql_fetch_array(mysql_query("SELECT * FROM `store` WHERE `storeId` = '".$id."'"));
		
		$storeName = $store['storeName'];
		
?>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script type="text/javascript">
    //<![CDATA[
    var map;
    var markers = [];
    var infoWindow;
    var locationSelect;

    function load() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(55.378051, -3.435973),
        zoom: 4,
        mapTypeId: 'roadmap',
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
      });
      infoWindow = new google.maps.InfoWindow();

      locationSelect = document.getElementById("locationSelect");
      locationSelect.onchange = function() {
        var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        if (markerNum != "none"){
          google.maps.event.trigger(markers[markerNum], 'click');
        }
      };
   }

   function searchLocations() {
     var address = document.getElementById("addressInput").value;
     var geocoder = new google.maps.Geocoder();
     geocoder.geocode({address: address}, function(results, status) {
       if (status == google.maps.GeocoderStatus.OK) {
        searchLocationsNear(results[0].geometry.location);
       } else {
         alert(address + ' not found');
       }
     });
   }

   function clearLocations() {
     infoWindow.close();
     for (var i = 0; i < markers.length; i++) {
       markers[i].setMap(null);
     }
     markers.length = 0;

     locationSelect.innerHTML = "";
     var option = document.createElement("option");
     option.value = "none";
     option.innerHTML = "See all results:";
     locationSelect.appendChild(option);
   }

   function searchLocationsNear(center) {
     clearLocations(); 

     var radius = document.getElementById('radiusSelect').value;
     var searchUrl = 'phpsqlsearch_genxml.php?lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker");
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
         var name = markerNodes[i].getAttribute("name");
         var address = markerNodes[i].getAttribute("address");
		 var mon = markerNodes[i].getAttribute("mon");
		 var tue = markerNodes[i].getAttribute("tue");
		 var wed = markerNodes[i].getAttribute("wed");
		 var thu = markerNodes[i].getAttribute("thu");
		 var fri = markerNodes[i].getAttribute("fri");
		 var sat = markerNodes[i].getAttribute("sat");
		 var sun = markerNodes[i].getAttribute("sun");
         var distance = parseFloat(markerNodes[i].getAttribute("distance"));
         var latlng = new google.maps.LatLng(
              parseFloat(markerNodes[i].getAttribute("lat")),
              parseFloat(markerNodes[i].getAttribute("lng")));

         createOption(name, distance, i);
         createMarker(latlng, name, address, mon, tue, wed, thu, fri, sat, sun);
         bounds.extend(latlng);
       }
       map.fitBounds(bounds);
       locationSelect.style.visibility = "visible";
       locationSelect.onchange = function() {
         var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
         google.maps.event.trigger(markers[markerNum], 'click');
       };
      });
    }

    function createMarker(latlng, name, address, mon, tue, wed, thu, fri, sat, sun) {
		  var html = "<b>Quicksilver Music<br />" + name + " Store</b> <br/>" + address + "<br /><br />Store Hours:<br /><br />Mon: " + mon + "<br />Tue: " + tue + "<br />Wed: " + wed + "<br />Thu: " + thu + "<br />Fri: " + fri + "<br />Sat: " + sat + "<br />Sun: " + sun;
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
		
		changeText("selectedStore", "The " + name + " Store");
		changeText("storeInfo", html);
		

      });
      markers.push(marker);
    }

	function changeText(divId, newText) {
	  var fieldNameElement = document.getElementById(divId);
	 // $('divId').update("The " + name + " Store");
	  fieldNameElement.innerHTML = newText;

	}
	
	function setFocus(){
		document.getElementById['addressInput'].focus();
	}

    function createOption(name, distance, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name + "(" + distance.toFixed(1) + ")";
      locationSelect.appendChild(option);
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request.responseText, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>
  </script>
<div class="container-615">
	<h2 class="header-615-blue item-heading">Find your nearest store</h2>
    <div class="content-615-blue">
        <div>
               <label for="addressInput">Please enter your town or postcode <input type="text" id="addressInput" name="addressInput" size="10"/></label>
               <label for="radiusSelect">Within <select name="radiusSelect" id="radiusSelect">
                  <option value="5" selected>5 Miles</option>
                  <option value="10">10 Miles</option>
                  <option value="50">50 Miles</option>
                </select></label>
            
                <input type="button" onclick="searchLocations()" value="Search" />
                </div>
                <div><select id="locationSelect" style="width:50%;visibility:hidden"></select></div>
    	<div id="map-container"><div id="map"></div></div>
    </div>
</div>



<span class="spacer">
	&nbsp;
</span>

<div class="container-300">
	<h2 class="header-300-red item-heading" id="selectedStore">Find Your Store</h2>
    <div class="content-300-red">
		<div id="storeInfo" class="storeInfo">
		Enter your address, town or postcode into the <a href="#" onclick="setFocus()">form on the left</a> to find your nearest store.
		</div>
    </div>
</div>

<?php	
	$page->getFooter();
?>