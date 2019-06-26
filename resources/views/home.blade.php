@extends('layouts.app')

@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('css/map.css') }}" >
<link rel="stylesheet" type="text/css" href="{{ asset('css/graph.css') }}" >
<link href="{{ asset('css/stock.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">PMI Location</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    
                    <div class="d-flex">
                      <div class="w-50">
                          <div class="text-left stock-title">
                              <div>Our Current <br> Stock Donor</div>
                              <div></div>
                          </div>
                          <dl>
                              @foreach($stocks as $stock)
                                  <dd class="d-flex percentage percentage-{{ $stock->percent}}">
                                      <div class="text-center w-20 bg-white">
                                      {{ $stock->name}} : {{ $stock->percent}}%
                                      </div>
                                      <div class="w-80 bar">

                                      </div>
                                  </dd>
                              @endforeach
                          </dl>
                      </div>
                      <div class="w-50 text-center">
                        <div style="font-size:1.2em">PMI Location</div>
                        <div id="map"></div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript">
var currentlatitude;
var currentlongitude;

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
function showPosition(position) {
  currentlatitude = position.coords.latitude;
  currentlongitude = position.coords.longitude;
  console.log(currentlatitude + ', ' +currentlongitude);
}

function initMap() {
  var js_data = '<?php echo json_encode($locations); ?>';
  var js_obj_data = JSON.parse(js_data);

  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: new google.maps.LatLng( currentlatitude, currentlongitude),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var infowindow = new google.maps.InfoWindow();

  var marker, i;

  for (i = 0; i < js_obj_data.length; i++) {  
    marker = new google.maps.Marker({
      position: new google.maps.LatLng(js_obj_data[i].latitude, js_obj_data[i].longitude),
      map: map
    });
    console.log( js_obj_data[i].latitude + '   ' + js_obj_data[i].longitude );

    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      var gmaps = "https://www.google.com/maps?saddr=My+Location&daddr=" + js_obj_data[i].name;
      var gmaps = "https://maps.google.com?q=" + js_obj_data[i].name;
      return function() {
        
        // infowindow.setContent('<a href="/location/' + js_obj_data[i].id + '">' + js_obj_data[i].name + ', ' + js_obj_data[i].address + '</a>');
        infowindow.setContent('<a href="' + gmaps +'">' + js_obj_data[i].name + ', ' + js_obj_data[i].address + '</a>');
        infowindow.open(map, marker);
      }
    })(marker, i));
  }   
}
getLocation();
</script>

<script src="http://maps.google.com/maps/api/js?key=AIzaSyBl-94cl8p6yU-zxAP0HWKbAjF2Lr3AIQo&callback=initMap" 
        type="application/javascript" async defer></script>
@endsection
