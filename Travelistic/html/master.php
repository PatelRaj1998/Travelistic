
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
        width: 50%;
        float: left;
        margin-left: 2%;
      }
      .placesList{
      	margin: auto;
      	text-align: center;
      }
      .innerPlacesList{
      	text-align: left;
      	padding: 15px;
      	width: 40%; 
      	height: 50%; 
      	margin: auto;
      	overflow: scroll;
		-webkit-box-shadow: 0 10px 6px -6px #777;
		-moz-box-shadow: 0 10px 6px -6px #777;
		box-shadow: 0 10px 6px -6px #777;
		border: solid grey 2px;
      }
  </style>
 </head>
 <body>
 <?php echo '<p>Master.php page</p>';
 ?>

<div id="places" class="placesList"> List of Places to Explore </div>
<div id="map"></div>
<div id="locationSelect" class="innerPlacesList"></div>
<script type="text/javascript">

var map;
var service;
var infoWindow;
var markers = [];

function initialize() {
	var latitude = '<?=$_POST['latitude']?>';
	var longitude = '<?=$_POST['longitude']?>';
	var pyrmont = new google.maps.LatLng(latitude, longitude);

  map = new google.maps.Map(document.getElementById('map'), {
      center: pyrmont,
      zoom: 12
    });
  infoWindow = new google.maps.InfoWindow();

  var request = {
    location: pyrmont,
    radius: '500',
    query: 'Places to visit'
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
  }
}
function createMarkersFactory (markerNodes, num) {
     var id = markerNodes.id;
     var name = markerNodes.name;
     var address = markerNodes.formatted_address;
     var latlng = new google.maps.LatLng(
          parseFloat(markerNodes.geometry.location.lat()),
          parseFloat(markerNodes.geometry.location.lng()));

     createOption(name, num);
     createMarker(latlng, name, address);
}

   function createOption(name, num) {
      var option = document.createElement("option");
      option.value = num;
      option.innerHTML = name;
      locationSelect.appendChild(option);
   }

 function createMarker(latlng, name, address) {
      var html = "<b>" + name + "</b> <br/>" + address;
      var marker = new google.maps.Marker({
        map: map,
        position: latlng
      });
      google.maps.event.addListener(marker, 'mouseover', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
      markers.push(marker);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRUn-gFXh88tScx4OUByKiNfSdzDBbOqU&libraries=places&callback=initialize"
     async defer>
</script>
 </body>
</html>