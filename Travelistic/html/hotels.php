
<html>
 <head>
  <title>Virtual Travel Agent</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/index.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<script src='https://kit.fontawesome.com/a076d05399.js'></script>
  <style>
    #map {
      height: 50%;
      width: 46.25%;
      float: left;
      margin-left: 2.5%;
      margin-right: 1.25%;
      border: solid grey 2px;
    }
    .placesList{
    	margin: auto;
    	text-align: center;
      margin: 2%;
    }
    .innerPlacesList{
    	width: 46.25%; 
    	height: 50%; 
    	margin-left: 1.25%;
      margin-right: 2.5%;
      overflow: scroll;
  		border: solid grey 2px;
      position: relative;
    }
    .scrollImages{
      overflow: auto;
      white-space: nowrap;
      margin: 2%;
      background-color: #333;
      top: 0;
    }
    .scrollImages > div {
      display: inline-block;
      color: white;
      float: none;
      padding: 10px;
      margin: 10px;
      width: 170px;
      vertical-align: top;
    }
     .scrollImages > div:hover{
      background-color: black;
    }
    .scrollImages > div > img{
      width: 150px;
      height:150px;
    }
    .scrollImages > div > option{
      font-size: 12px;
      text-align: center;
      white-space: normal;
    }
    .placeImage{
      width: 100%; 
      height: 100%;
      position: relative;
    }
    .placeDetails{
      position: absolute;
      background-color: rgba(0, 0, 0, 0.7);
      color: white; 
      bottom: 0;
      height: 34%;
      width: 100%;
      overflow: hidden;
    }
    .placeName{
      float: left;
      clear: right;
    }
    .placeAddress{
      float: right;
    }
  </style>
  <?php 
    if(isset($_POST['latitude']) )
      {
        $lat=$_POST['latitude'];
        $lng=$_POST['longitude'];
      } 
  ?>
 </head>
 <body>
 <!-- Navbar (sit on top) -->
<div class="w3-top">
  <div class="w3-bar w3-white w3-card" id="myNavbar">
    <a href="index.html" class="w3-bar-item w3-button w3-wide">Travelistic</a>
    
    <!-- Right-sided navbar links -->
    <div class="w3-right w3-hide-small">
      <a href="#" class="w3-bar-item w3-button"><i class="fa fa-user"></i> Login</a>
    </div>
    <div class="w3-right w3-hide-small">
      <a href="#" class="w3-bar-item w3-button"><i class='fas fa-hotel'></i> Hotels</a>
    </div>
    <div class="w3-right w3-hide-small">
      <a onclick="post('food.php');" class="w3-bar-item w3-button"><i class="fas fa-pizza-slice"></i> Food</a>
    </div>
      <div class="w3-right w3-hide-small">
      <a onclick="post('places.php');" class="w3-bar-item w3-button"><i class="fas fa-hotel"></i> Places</a>
    </div>
    <!-- Hide right-floated links on small screens and replace them with a menu icon -->

    <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="w3_open()">
      <i class="fa fa-bars"></i>
    </a>
  </div>
</div>
<!-- Sidebar on small screens when clicking the menu icon -->
<nav class="w3-sidebar w3-bar-block w3-black w3-card w3-animate-left w3-hide-medium w3-hide-large" style="display:none" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button w3-large w3-padding-16" style="float: right;">X</a>
  <a href="#" onclick="w3_close();" class="w3-bar-item w3-button">Login</a>
  <a onclick="w3_close(); post('places.php');" class="w3-bar-item w3-button">Places</a>
  <a onclick="w3_close(); post('food.php');" class="w3-bar-item w3-button">Food</a>
  <a href="#" onclick="w3_close();" class="w3-bar-item w3-button">Hotels</a>
</nav>

<br><br><br>
<div class="w3-display-topright w3-text-white" style="padding:60px;">
      
    <input id="pac-input" class="controls" type="text" placeholder="Edit your destination" style="font-size: 15px;">
    
      <form action="hotels.php" method="post" id="placeBound">
        <input id="latitude" type="hidden" name="latitude" value="0">
        <input id="longitude" type="hidden" name="longitude" value="0">
      </form>
      
  </div>
<div id="places" class="placesList"> List of Hotels to Stay </div>

<div id="map"></div>
<div id="locationSelect" class="innerPlacesList">
  
  <img id="placeImage" class="placeImage" src="">
  <div id="placeDetails" class="placeDetails">
    <div id="placeName" class="placeName"></div><br>
    <div id="placeAddress" style="placeAddress"></div>
  </div>
  
</div>

<div class="scrollImages" id="imagesForPlaces">
</div>


<script type="text/javascript">

var map;
var service;
var infoWindow;
var infoWindow1;
var markers = [];
var latitude;
var longitude;

function initialize() {

  initAutocomplete();

  var pyrmont = new google.maps.LatLng(latitude, longitude);

  map = new google.maps.Map(document.getElementById('map'), {
      center: pyrmont,
      zoom: 12
    });
  infoWindow = new google.maps.InfoWindow();
  infoWindow1 = new google.maps.InfoWindow();

  var request = {
    location: pyrmont,
    radius: '500',
    query: 'Places to stay'
  };

  service = new google.maps.places.PlacesService(map);
  service.textSearch(request, callback);
}

function callback(results, status) {
  if (status == google.maps.places.PlacesServiceStatus.OK) {
    for (var i = 0; i < results.length; i++) {
      var place = results[i];
      createMarkersFactory(results[i], i);
    }
    populateSomeThings(results[0], 0);
  }
}
function populateSomeThings(markerNodes, num)
{
  var id = markerNodes.id;
  var name = markerNodes.name;
  var photo = markerNodes.photos;
  var address = markerNodes.formatted_address;
  var latlng = new google.maps.LatLng(
      parseFloat(markerNodes.geometry.location.lat()),
      parseFloat(markerNodes.geometry.location.lng())
  );
  var html = "<b>" + name;
  var marker = new google.maps.Marker({
  map: map,
  position: latlng
  });
  infoWindow1.setContent(html);
  infoWindow1.open(map, marker);
  if(!(typeof photo === 'undefined' || photo === null))
  {
    document.getElementById('placeImage').src = photo[0].getUrl();
  }
  document.getElementById('placeName').innerHTML = "Place: " + name;
  document.getElementById('placeAddress').innerHTML = "Address: " + address;
}
function createMarkersFactory (markerNodes, num) {
     var id = markerNodes.id;
     var name = markerNodes.name;
     var photo = markerNodes.photos;
     var address = markerNodes.formatted_address;
     var latlng = new google.maps.LatLng(
          parseFloat(markerNodes.geometry.location.lat()),
          parseFloat(markerNodes.geometry.location.lng())
     );
     console.log(markerNodes);
     createPreviewForEachPlace(latlng, name, address, photo);
     createMarker(latlng, name, address, photo);
}
 function createPreviewForEachPlace(latlng, name, address, photo) {
  var html = "<b>" + name;
  var marker = new google.maps.Marker({
    map: map,
    position: latlng
  });
  var placePreview = document.createElement('div');
  var placeImage = document.createElement('img');
  var placeName = document.createElement('option');
  if(!(typeof photo === 'undefined' || photo === null))
  {
    placeImage.src = photo[0].getUrl();
  }
  placeName.innerHTML = name;
  placePreview.appendChild(placeImage);      
  placePreview.appendChild(placeName);
  placePreview.addEventListener('click',function(e){
    infoWindow1.setContent(html);
    infoWindow1.open(map, marker);
    if(!(typeof photo === 'undefined' || photo === null))
    {
      document.getElementById('placeImage').src = photo[0].getUrl();
    }
    document.getElementById('placeName').innerHTML = "Place: " + name;
    document.getElementById('placeAddress').innerHTML = "Address: " + address;
  });
  imagesForPlaces.appendChild(placePreview);
 }
 function createMarker(latlng, name, address, photo) {
      var html = "<b>" + name;
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'mouseover', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      google.maps.event.addListener(marker, 'mouseout', function() {
        infoWindow.close();
      });
      google.maps.event.addListener(marker, "click", function() {
        infoWindow1.setContent(html);
        infoWindow1.open(map, marker);
      console.log("Photo: ");
      console.log(photo);
      if(!(typeof photo === 'undefined' || photo === null))
      {
        document.getElementById('placeImage').src = photo[0].getUrl();
      }
      document.getElementById('placeName').innerHTML = "Place: " + name;
      document.getElementById('placeAddress').innerHTML = "Address: " + address;
      });
      markers.push(marker);
    }

  function initAutocomplete() {
      var input = document.getElementById('pac-input');
      var autocomplete = new google.maps.places.Autocomplete(input, {types: ['geocode']});
      autocomplete.addListener('place_changed', function(){
        var place = this.getPlace();
        var pos = place.geometry.location;
        var latitude = place.geometry.location.lat();
        var longitude = place.geometry.location.lng();
        document.getElementById("latitude").value = latitude;
        document.getElementById("longitude").value = longitude;
        document.getElementById("placeBound").submit();
    });       

  }

function variableDeclared()
{
	if(latitude == null)
		setTimeout(variableDeclared, 200);
	else
		initialize();
}
function getLocation() {
  if(<?php echo !empty($_POST) ? 'true' : 'false'; ?>){
    console.log("We have the post variables");
    latitude = '<?php echo $lat; ?>';
    longitude = '<?php echo $lng; ?>';
  }
  else{
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
  		  latitude = position.coords.latitude;
  		  longitude = position.coords.longitude;
      	});
    } else { 
      console.log("Error");
      latitude = 0;
      longitude = 0;
    }
  }
  //gotta wait for above function to determine the location
  variableDeclared();
}
function post(path, method='POST') {
  const form = document.createElement('form');
  form.method = method;
  form.action = path;

  const hiddenField = document.createElement('input');
  hiddenField.type = 'hidden';
  hiddenField.name = "latitude";
  hiddenField.value = latitude;
  const hiddenField1 = document.createElement('input');
  hiddenField1.type = 'hidden';
  hiddenField1.name = "longitude";
  hiddenField1.value = longitude;

  form.appendChild(hiddenField);
  form.appendChild(hiddenField1);

  document.body.appendChild(form);
  form.submit();
}

</script>

<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=getLocation"
     async defer>
</script>
 </body>
</html>
