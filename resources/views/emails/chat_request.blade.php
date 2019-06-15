/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 06/11/2019
 * Time: 2:19 PM
 */


<script src="https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJN1t_tDeuEmsRUsoyG83frY4&fields=name,rating,formatted_phone_number&key=AIzaSyBl-94cl8p6yU-zxAP0HWKbAjF2Lr3AIQo"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('css/view/addevent.css') }}" >
<link rel="stylesheet" type="text/css" href="{{ asset('css/map.css') }}" >
<link href="{{ asset('css/app.css') }}" rel="stylesheet">

<div class="text-center">
    <h2>Hi this is urgent {{ $name}}!</h2>
</div>
<div>We have a family in need of a blood supply here {{$location->location}}, {{$location->address}} to be exact.</div>
<div id="map"></div>
<!-- <div>{{$location->latitude}}</div>
<div>{{$location->longitude}}</div> -->

<div>"{{$location->description}}" - This is what they said about the situation</div>
<div>
    @if ($location->urgent == 0)
        This is not urgent
    @else
        This is urgent
    @endif
</div>
<div>The request is open until {{$location->accessible_until}}</div>


<div>
    <a href="https://www.google.com/maps?saddr=My+Location&daddr={{$location->location}}">Open Location</a>
</div>
<script type="text/javascript">
    function initMap() {
        var js_data = '<?php echo json_encode($location); ?>';
        var js_obj_data = JSON.parse(js_data);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            center: new google.maps.LatLng( js_obj_data.latitude, js_obj_data.longitude),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker;

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(js_obj_data.latitude, js_obj_data.longitude),
            map: map
        });
    }
    setTimeout(() => {
        this.initMap();
    }, 50);
</script>
<script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyBl-94cl8p6yU-zxAP0HWKbAjF2Lr3AIQo&callback=initMap"
        type="text/javascript" async defer></script>