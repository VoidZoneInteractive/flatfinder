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

    for(i in markerData) {
        createMarker(markerData[i]);
    }
}

function createMarker(markerInfo) {
    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        position: new google.maps.LatLng(markerInfo.latitude, markerInfo.longitude),
        visible: true
    });

    var boxText = document.createElement("div");
    boxText.style.cssText = "border: 1px solid black; margin-top: 8px; background: #FFF; padding: 5px;";
    boxText.innerHTML = "City Hall, Sechelt<br>British Columbia<br>Canada";

    var myOptions = {
        content: boxText
        ,disableAutoPan: false
        ,maxWidth: 0
        ,pixelOffset: new google.maps.Size(-140, 0)
        ,zIndex: null
        ,boxStyle: {
            background: "url('tipbox.gif') no-repeat"
            ,opacity: 0.75
            ,width: "280px"
        }
        ,closeBoxMargin: "10px 2px 2px 2px"
        ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
        ,infoBoxClearance: new google.maps.Size(1, 1)
        ,isHidden: false
        ,pane: "floatPane"
        ,enableEventPropagation: false
    };

    var ib = new InfoBox(myOptions);
    ib.open(theMap, marker);
}