
    $(document).ready(function () {
        var currgeocoder;
        var user_lng;
        var user_lat;
        var url;
        var a = document.getElementById("plus_proche");

        //Set geo location lat and long

        navigator.geolocation.getCurrentPosition(function (position, html5Error) {

            geo_loc = processGeolocationResult(position);
            currLatLong = geo_loc.split(",");
            initializeCurrent(currLatLong[0], currLatLong[1]);

        });

        //Get geo location result

        function processGeolocationResult(position) {
            html5Lat = position.coords.latitude; //Get latitude
            html5Lon = position.coords.longitude; //Get longitude
            html5TimeStamp = position.timestamp; //Get timestamp
            html5Accuracy = position.coords.accuracy; //Get accuracy in meters
            return (html5Lat).toFixed(8) + ", " + (html5Lon).toFixed(8);
        }

        //Check value is present or not & call google api function

        function initializeCurrent(latcurr, longcurr) {
            currgeocoder = new google.maps.Geocoder();
            console.log(latcurr + "-- ######## --" + longcurr);

            if (latcurr != '' && longcurr != '') {
                var myLatlng = new google.maps.LatLng(latcurr, longcurr);
                return getCurrentAddress(myLatlng);
            }
        }

        //Get current address

        function getCurrentAddress(location) {
            currgeocoder.geocode({
                'location': location

            }, function (results, status) {

                if (status == google.maps.GeocoderStatus.OK) {
                    console.log(results[0]);
                   // $("#address").html(results[0].formatted_address);
                    user_lng = results[0].geometry.location.lng();
                    user_lat = results[0].geometry.location.lat();
                    
                    setCookie("user_lat", user_lat, 365);
                    setCookie("user_lng", user_lng, 365);

                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
        var lat = getCookie("user_lat");
        var lng = getCookie("user_lng");



        url = Routing.generate('near_me', {lat: lat, lng: lng});
        a.setAttribute("href", url);


    });
