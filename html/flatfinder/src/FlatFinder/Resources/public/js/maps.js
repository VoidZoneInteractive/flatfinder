// AIzaSyBR1SH3srBLIRnTntbm6L4b_i-D64xdOxo

var map;

function initMap() {
    // Create a map object and specify the DOM element for display.
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 52.5051278, lng: 13.297757},
        scrollwheel: false,
        zoom: 11
    });

    var transitLayer = new google.maps.TransitLayer();
    transitLayer.setMap(map);

    getMarkerData();
}

function createMarker(markerInfo) {
    if (markerInfo.latitude == null || markerInfo.longitude == null) {
        return null;
    }

    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: new google.maps.LatLng(markerInfo.latitude, markerInfo.longitude),
        visible: true,
        data: markerInfo
    });

    var boxText = document.createElement("div");
    boxText.style.cssText = "background: #FFF; padding: 5px;";
    boxText.innerHTML = markerInfo.title + '<br />' +
                        '<strong>Price:</strong> ' + markerInfo.net_price + '<br />' +
                        '<a href="' + markerInfo.url + '">View details</a>';

    var infowindow = new google.maps.InfoWindow();

    google.maps.event.addListener(marker, 'mouseover', (function(marker) {
        return function() {
            console.log(marker.data);
            var content = boxText;
            infowindow.setContent(content);
            infowindow.open(map, marker);
        }
    })(marker));

    google.maps.event.addListener(marker, 'mouseout', (function(marker) {
        return function() {
            infowindow.close();
        }
    })(marker));
}

function getMarkerData()
{
    var data = null;

    var xhr = new XMLHttpRequest();
    xhr.withCredentials = true;

    xhr.addEventListener("readystatechange", function () {
        if (this.readyState === this.DONE) {
            var data = JSON.parse(this.responseText);

            for (i in data)
            {
                createMarker(data[i]);
            }
        }
    });

    xhr.open("GET", "/crawler/crawl");
    //xhr.setRequestHeader("cache-control", "no-cache");
    //xhr.setRequestHeader("postman-token", "31a59c2c-3090-68d7-e3be-ec7b7d81747d");

    xhr.send(data);
}