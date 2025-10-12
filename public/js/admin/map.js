$(document).ready(function () {
    mapboxgl.accessToken =
        "pk.eyJ1IjoiaHlzMG1haSIsImEiOiJjbWZuY2x0a2swM3E5Mm5zZ2Q5eGQ5NWFmIn0.rEgiyP7ecaHVHsGLTG0N5w";

    const defaultLng = 121.774;
    const defaultLat = 12.8797;

    const map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/satellite-streets-v11",
        center: [defaultLng, defaultLat],
        zoom: 5,
    });

    map.addControl(new mapboxgl.NavigationControl());

    const marker = new mapboxgl.Marker({ draggable: true })
        .setLngLat([defaultLng, defaultLat])
        .addTo(map);

    const popup = new mapboxgl.Popup({ offset: 25 });

    function updateLatLng(lngLat) {
        $("#latitude").val(lngLat.lat);
        $("#longitude").val(lngLat.lng);

        $.get(
            "https://api.mapbox.com/geocoding/v5/mapbox.places/" +
                lngLat.lng +
                "," +
                lngLat.lat +
                ".json",
            {
                access_token: mapboxgl.accessToken,
                limit: 1,
            }
        ).done(function (data) {
            if (data.features.length > 0) {
                const place = data.features[0].place_name;
                $("#address").val(place);
                popup.setLngLat([lngLat.lng, lngLat.lat]).setHTML(place).addTo(map);
            }
        });
    }

    function goToUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const userLng = position.coords.longitude;
                    const userLat = position.coords.latitude;

                    marker.setLngLat([userLng, userLat]);
                    map.flyTo({ center: [userLng, userLat], zoom: 15 });
                    updateLatLng({ lng: userLng, lat: userLat });
                },
                function (error) {
                    console.warn("Unable to retrieve location:", error.message);
                }
            );
        }
    }

    // Add Mapbox Geocoder (search box on map)
    const geocoder = new MapboxGeocoder({
        accessToken: mapboxgl.accessToken,
        mapboxgl: mapboxgl,
        placeholder: "Search for a location...",
        marker: false, // prevent geocoder from adding its own marker
    });

    map.addControl(geocoder, "top-left");

    // When a result is selected from search
    geocoder.on("result", function (e) {
        const lngLat = e.result.center;
        marker.setLngLat(lngLat);
        map.flyTo({ center: lngLat, zoom: 15 });
        updateLatLng({ lng: lngLat[0], lat: lngLat[1] });
    });

    // Initial load: try to use user location
    goToUserLocation();

    // Drag marker event
    marker.on("dragend", function () {
        updateLatLng(marker.getLngLat());
    });

    // Click on map to move marker
    map.on("click", function (e) {
        marker.setLngLat(e.lngLat);
        updateLatLng(e.lngLat);
    });
});
