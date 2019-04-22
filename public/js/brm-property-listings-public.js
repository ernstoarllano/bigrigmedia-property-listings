/**
 * Google Map
 */
class GoogleMap {
  /**
   * Initialze the GoogleMap object
   * 
   * @param {number} listingID An optional ID to pull a specified REST API response
   * @param {number} zoom An optional map zoom level
   */
  constructor(listingID, zoom = 8) {
    // Default container that will hold the rendered map
    this.container = document.getElementById('map')

    // Set map zoom level
    this.zoom = zoom

    // Initialize the fetch REST API method
    this.fetchEndpoint(listingID)
  }

  /**
   * Determine which REST API endpoint to use
   * 
   * @param   {number} listingID 
   * @return  {string} The REST API endpoint
   */
  whichEndpoint(listingID) {
    return typeof listingID !== 'undefined' ? `/wp-json/wp/v2/listings/${listingID}` : `/wp-json/wp/v2/listings/`
  }

  /**
   * Fetch REST API data
   * 
   * @param   {number} listingID 
   * @return  {object} The REST API response
   */
  fetchEndpoint(listingID) {
    fetch(this.whichEndpoint(listingID))
      .then(res => res.json())
      .then(data => this.initMap(data))
      .catch(err => console.log(err))
  }

  /**
   * Initialize a Google Map
   * 
   * @param   {object} listings 
   * @return  {object} Return a new Google Map
   */
  initMap(listings) {
    if (!listings) return

    const map = new google.maps.Map(this.container, {
      zoom: window.matchMedia('(min-width: 1024px)').matches ? this.zoom : 6,
      center: { lat: 29.725792, lng: -95.6951486 },
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      fullscreenControl: false,
      mapTypeControl: false,
      zoomControlOptions: {
        position: google.maps.ControlPosition.LEFT_TOP
      }
    })

    const geocoder = new google.maps.Geocoder()
    const infowindow = new google.maps.InfoWindow()
    const bounds = new google.maps.LatLngBounds();

    if (!Array.isArray(listings)) {
      this.geocodeAddress(listings, geocoder, map, infowindow, bounds)
    } else {
      listings.forEach(listing => {
        this.geocodeAddress(listing, geocoder, map, infowindow, bounds)
      })
    }
  }

  /**
   * Reverse Geocode an address
   * 
   * @param   {object} listing 
   * @param   {object} geocoder 
   * @param   {object} map 
   * @param   {object} infowindow
   * @param   {object} bounds
   * @return  {object} Return a Google Marker
   */
  geocodeAddress(listing, geocoder, map, infowindow, bounds) {
    if (!listing && !geocoder && !map && !infowindow && !bounds) return

    let title = listing.title.rendered
    let address = listing.address
    let link = listing.link

    geocoder.geocode({ address }, (results, status) => {
      if (status === 'OK') {
        let position = results[0].geometry.location
        let marker = new google.maps.Marker({ map, position })
        let markerLatLng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng())
        let content = `<div class="map-content">
                        <h4>${title}</h4>
                        <address>${address}</address>
                        <a class="btn btn--secondary" href="${link}">View Property</a>
                        <a class="btn btn--primary" href="#">Book Now</a>
                       </div>`

        marker.setMap(map)

        map.setCenter(results[0].geometry.location)

        marker.addListener('click', () => {
          infowindow.setContent(content)
          infowindow.open(map, marker)
        })

        if (!document.body.classList.contains('single-listings')) {
          bounds.extend(markerLatLng)
          map.fitBounds(bounds)
        }
      } else {
        console.log('Geocode was not successful for the following reason: ' + status)
      }
    })
  }
}

// Initialize a new Google Map
if (document.getElementById('map')) {
  if (document.body.classList.contains('single-listings')) {
    new GoogleMap(parseInt(listing.id), 15)
  } else {
    new GoogleMap()
  }
}

/*
$('.filter').submit(function (e) {
  e.preventDefault()

  $.ajax({
    url: $(this).attr('action'),
    data: $(this).serialize(),
    type: $(this).attr('method'),
    success: function (data) {
      console.log(data)
    },
    error: function (err) {
      console.log(err)
    }
  })
})
*/