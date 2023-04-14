/**
 * Map creation
*/

import { Map, NavigationControl } from 'https://cdn.skypack.dev/maplibre-gl';
import { create } from 'lodash';
import { placeMarker } from './marker';
var MainMap = {};
let GeoapifyApiKey = "f24bade268e94bce825902a4d81db3ef"; // change in .env too

/**
 * Get siblings function (used in makeActive function)
*/
function getSiblings(e) {
	let siblings = [];
	let sibling = e.parentNode.firstChild;

	while (sibling) {
		if (sibling.nodeType === 1 && sibling !== e) {
			siblings.push(sibling);
		}
		sibling = sibling.nextSibling
	}
	return siblings;
};

/**
 * Highlight the selected result
*/
function makeActive(hash) {
    const node = document.querySelector(`#${hash}`);
    if (node) {
        let siblings = getSiblings(node);
        siblings.forEach((sibling) => {
            sibling.classList.remove('active');
        })
        node.classList.add('active');
    }
}

/**
 * Function to create a link to an achor
*/
function createPopupContent(element, myLatitude, myLongitude) {
    // the popup includes 2 links: 1 to an anchor and another to the itinerary results page
    let anchor = `<a id="anchor-link-${element.anchor}" href="#${element.anchor}">${element.name}</a>`;
    anchor += '<br>';
    anchor += `<a href="/results/itinerary?mode=walk&name=${element.name}`;
    anchor += `&latitude=${myLatitude}&longitude=${myLongitude}`;
    anchor += `&destLatitude=${element.latitude}&destLongitude=${element.longitude}`;
    anchor += "\"> Itinéraire </a>";
    return anchor;
}

window.addEventListener('DOMContentLoaded', () => {
    // retrieve the information needed to create the map
    let myMap = document.getElementById('my-map');
    if (myMap) {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString); // retrieve the URL params
        let myLongitude = urlParams.get('longitude');
        let myLatitude = urlParams.get('latitude');
        let mode = urlParams.get('mode') ? urlParams.get('mode') : 'walking';

        // highlight the selected result if there is one
        const hash = window.location.hash.substring(1);
        if (hash) {
            makeActive(hash);
        }

        // for itinerary results: get the right icon according to the selected mode
        let modeIcon = '';
        switch (mode) {
            case 'walk':
                modeIcon = 'walking'
                break;
            case 'bicycle':
                modeIcon = 'biking'
                break;
            case 'drive':
                modeIcon = 'car'
                break;
        default:
            modeIcon = 'walking'
        };

        // create the map
        MainMap = new Map({
            container: 'my-map',
            style: `https://maps.geoapify.com/v1/styles/klokantech-basic/style.json?apiKey=${GeoapifyApiKey}`,
            center: [myLongitude, myLatitude],
            zoom: 13,
            maxZoom: 17,
            minZoom: 10,
        });
        MainMap.addControl(new NavigationControl());

        // icons from Geoapify APIs
        const currentLocationIconUrl = `https://api.geoapify.com/v1/icon/?type=material&color=red&icon=${modeIcon}&iconType=awesome&scaleFactor=2&apiKey=${GeoapifyApiKey}`;
        const storeIconUrl = `https://api.geoapify.com/v1/icon/?type=material&color=%237f2cbf&size=small&icon=store&iconType=awesome&apiKey=${GeoapifyApiKey}`;
        const recyclingCenterIconUrl = `https://api.geoapify.com/v1/icon/?type=material&color=%231b70e1b&size=small&icon=recycle&iconType=awesome&apiKey=${GeoapifyApiKey}`;
        const destinationIconUrl = 'https://api.geoapify.com/v1/icon/?type=material&color=%2329ae21&icon=flag-checkered&iconType=awesome&apiKey=f24bade268e94bce825902a4d81db3ef';

        // place the marker for the current Location
        placeMarker(MainMap, myLongitude, myLatitude, currentLocationIconUrl, 'Position actuelle', true);

        // place markers and popup for the stores
        if (StoreResults) {
            StoreResults.forEach((store) => {
                let popUpContent = createPopupContent(store, myLatitude, myLongitude);
                placeMarker(MainMap, store.longitude, store.latitude, storeIconUrl, popUpContent, false);
            });
        }

        // place markers and popup for the recycling centers
        if (RecyclingCenterResults) {
            RecyclingCenterResults.forEach((center) => {
                let popUpContent = createPopupContent(center, myLatitude, myLongitude);
                placeMarker(MainMap, center.longitude, center.latitude, recyclingCenterIconUrl, popUpContent, false);
            });
        }

        // if itinerary, place the itinerary on the map
        if (Itinerary) {
            MainMap.on('load', () => {
                MainMap.addSource('route', {
                    type: 'geojson',
                    data: Itinerary.response
                });
                  
                MainMap.addLayer({
                    'id': 'route-layer',
                    'type': 'line',
                    'source': 'route',
                    'layout': {
                      'line-cap': "round",
                      'line-join': "round"
                    },
                    'paint': {
                      'line-color': "#6084eb",
                      'line-width': 8
                    },
                    'filter': ['==', '$type', 'LineString']
                });

                placeMarker(
                    MainMap, 
                    Itinerary.destination_longitude, 
                    Itinerary.destination_latitude, 
                    destinationIconUrl, Itinerary.destination_name + '<br>' + Itinerary.destination_address, 
                    true
                );
            })
        }
    }
});

// make the selected result active
window.addEventListener('popstate', () => {
    const hash = window.location.hash.substring(1);
    if (hash) {
        makeActive(hash);
    }
});
