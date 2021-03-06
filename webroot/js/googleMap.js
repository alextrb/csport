var map, i;

function initMap() {
    var paris = {lat: 48.86, lng: 2.33};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 8,
        center: paris
    });

    var contentString = '<div> Test contenu </div>';
    var infowindow = new google.maps.InfoWindow({content : contentString});
    
    json_locations.forEach(function(coords) {
        var json_coords = {lat: coords.lat, lng: coords.lng};
        var marker = new google.maps.Marker({
            position: json_coords,
            map: map,
            title: 'Votre entrainement',
            draggable : true
        });
        marker.addListener('click', function(){
            infowindow.open(map,marker);
        });
    });
}