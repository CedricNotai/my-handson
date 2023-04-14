/**
 * Markers for the map
*/

import { Marker, Popup } from 'https://cdn.skypack.dev/maplibre-gl';

function placeMarker(map, longitude, latitude, iconUrl, popUpContent, isBig) {
    var markerIcon = document.createElement('div');
    if (isBig) {
        markerIcon.style.width = '38px';
        markerIcon.style.height = '55px';
    } else {
        markerIcon.style.width = '25px';
        markerIcon.style.height = '36px';

    }
    markerIcon.style.backgroundSize = "contain";
    markerIcon.style.backgroundImage = `url(${iconUrl})`;
    markerIcon.style.cursor = "pointer";

    let markerPopup = new Popup({
        anchor: 'bottom',
        closeOnMove: true,
        offset: [0, -30]
    }).setHTML(popUpContent);

    new Marker(markerIcon, {anchor: 'bottom', offset: [0, 6]})
        .setLngLat([longitude, latitude])
        .setPopup(markerPopup)
        .addTo(map);
}

export { placeMarker };