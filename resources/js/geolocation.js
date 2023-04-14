/**
 * Geolocation
*/
function geoFindMe() {
    const status = document.querySelector('#status');

    function success(position) {
        const latitude  = position.coords.latitude;
        const longitude = position.coords.longitude;
        // if the geolocation was successfull, redirect to results page
        window.location.replace(`/results?latitude=${latitude}&longitude=${longitude}`);
    }

    function error() {
        status.textContent = 'Impossible de vous géolocaliser. Avez-vous autorisé la géolocalisation ?';
    }

    if (!navigator.geolocation) {
        status.textContent = 'La géolocalisation n\'est pas compatible avec votre navigateur.';
    } else {
        status.textContent = 'En cours de géolocalisation, merci de patienter...';
        navigator.geolocation.getCurrentPosition(success, error);
    }  
}

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

window.addEventListener('DOMContentLoaded', () => {
    // geolocation
    const findMeButton = document.getElementById('find-me');
    if (findMeButton) {
        findMeButton.addEventListener('click', geoFindMe);
    }
});