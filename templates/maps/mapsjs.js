function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 48.8566, lng: 2.3522 },
        zoom: 7
    });

    var directionsService = new google.maps.DirectionsService();
    var directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);

    document.getElementById('submit').addEventListener('click', function () {
        calculateAndDisplayRoute(directionsService, directionsRenderer);
    });

    const campuses = [
        { name: "Campus Eiffel", lat: 48.846092, lng: 2.351833 },
        { name: "Campus Lyon", lat: 45.754042, lng: 4.831659 },
        { name: "Campus Bordeaux", lat: 44.847124, lng: -0.579180 },
        { name: "Campus Rennes", lat: 48.117266, lng: -1.677793 }
    ];

    map.addListener('click', function (event) {
        const clickedLocation = event.latLng;
        let clickedCampus = null;
        campuses.forEach(campus => {
            const distance = google.maps.geometry.spherical.computeDistanceBetween(
                clickedLocation,
                new google.maps.LatLng(campus.lat, campus.lng)
            );
            if (distance < 1000) {  // Radius of 1km to consider a click on a campus
                clickedCampus = campus;
            }
        });
        if (clickedCampus) {
            alert('Vous avez cliqué sur le ' + clickedCampus.name);
        } else {
            alert('Vous n\'avez cliqué sur aucun campus');
        }
    });
}

function calculateAndDisplayRoute(directionsService, directionsRenderer) {
    var start = document.getElementById('start').value;
    var end = document.getElementById('end').value;

    directionsService.route({
        origin: start,
        destination: end,
        travelMode: 'DRIVING'
    }, function (response, status) {
        if (status === 'OK') {
            directionsRenderer.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

window.onload = initMap;