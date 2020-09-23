var mymap = L.map('mapid').setView([-34.6158037,-58.5033381], 12);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoiamF2aWVydmVyb244MSIsImEiOiJja2YwY3BubXkwa2I3MnJsYzd6eTc3aHNkIn0.Nc5gamXKTPseDihEk1LkIg'
}).addTo(mymap);

var marker_controls = new L.Control.SimpleMarkers();
mymap.addControl(marker_controls);

var marker = L.marker([-34.6158037,-58.5033381]).addTo(mymap);
marker.bindPopup("<b>Hola Mundo!</b><br>Esto es una prueba.");