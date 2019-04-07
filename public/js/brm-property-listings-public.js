/**
 * Google Map
 */
class GoogleMap {
  /**
   * Initialze the GoogleMap object
   * 
   * @param {number} listingID An optional ID to pull a specified REST API response
   */
  constructor(listingID) {
    // Default container that will hold the rendered map
    this.container = document.getElementById('map')

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
      zoom: window.matchMedia('(min-width: 1024px)').matches ? 8 : 6,
      center: { lat: 29.725792, lng: -95.6951486 },
      mapTypeId: google.maps.MapTypeId.ROADMAP,
    })

    const geocoder = new google.maps.Geocoder()
    const infowindow = new google.maps.InfoWindow()

    if (!Array.isArray(listings)) {
      this.geocodeAddress(listings, geocoder, map, infowindow)
    } else {
      listings.forEach(listing => {
        this.geocodeAddress(listing, geocoder, map, infowindow)
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
   * @return  {object} Return a Google Marker
   */
  geocodeAddress(listing, geocoder, map, infowindow) {
    if (!listing && !geocoder && !map) return

    let title = listing.title.rendered
    let address = listing.address

    geocoder.geocode({ address }, (results, status) => {
      if (status === 'OK') {
        let position = results[0].geometry.location
        let marker = new google.maps.Marker({ map, position })
        let content = `<h4>${title}</h4>
                       <address>${address}</address>`

        marker.setMap(map)

        map.setCenter(results[0].geometry.location)

        marker.addListener('click', () => {
          infowindow.setContent(content)
          infowindow.open(map, marker)
        })
      } else {
        console.log('Geocode was not successful for the following reason: ' + status)
      }
    })
  }
}

// Initialize a new Google Map
if (document.getElementById('map')) {
  if (document.body.classList.contains('single-listings')) {
    new GoogleMap(parseInt(listing.id))
  } else {
    new GoogleMap()
  }
}