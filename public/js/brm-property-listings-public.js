window.addEventListener('load', () => {
	const apiRoot = '/wp-json/wp/v2/listings'
	const container = document.getElementById('listings')

	let map, geocoder, marker

	/**
	 * getListings
	 */
	function getListings() {
		fetch(apiRoot)
			.then(res => res.json())
			.then(data => {
				initMap(data)
			})
			.catch(err => console.log(err))
	}

	/**
	 * initMap
	 *
	 * @param {object} data
	 */
	function initMap(data) {
		const zoom = window.matchMedia('(min-width: 1024px)').matches ? 8 : 6

		map = new google.maps.Map(container, {
			zoom,
			center: { lat: 29.725792, lng: -95.6951486 },
			mapTypeId: google.maps.MapTypeId.ROADMAP,
		})

		geocoder = new google.maps.Geocoder()

		const addresses = listingAddresses(data)

		addresses.forEach(address => {
			geocodeAddress(address, geocoder, map)
		})
	}

	/**
	 * listingAddresses
	 *
	 * @param {object} listings
	 */
	function listingAddresses(listings) {
		if (!listings) return

		const addresses = []

		listings.forEach(listing => {
			const { address } = listing

			addresses.push(address)
		})

		return addresses
	}

	/**
	 * geocodeAddress
	 * 
	 * @param {string} address
	 * @param {object} geocoder
	 * @param {object} map
	 */
	function geocodeAddress(address, geocoder, map) {
		geocoder.geocode({ address }, function (results, status) {
			if (status === 'OK') {
				map.setCenter(results[0].geometry.location)

				const icon = {
					path: 'M13.907 40.048C2.177 23.233 0 21.507 0 15.328 0 6.861 6.94 0 15.5 0S31 6.862 31 15.327c0 6.18-2.177 7.906-13.907 24.72-.77 1.1-2.416 1.1-3.186 0z',
					fillColor: '#b7760a',
					fillOpacity: 1,
					scale: 1,
					strokeColor: '#b7760a',
					strokeWeight: 0,
				}

				marker = new google.maps.Marker({
					map,
					position: results[0].geometry.location,
					icon
				})

				google.maps.event.addListener(marker, 'click', function () {
					console.log(marker)
				})
			} else {
				console.log('Geocode was not successful for the following reason: ' + status)
			}
		})
	}

	// Initialize getListings
	if (container) {
		getListings()
	}
})