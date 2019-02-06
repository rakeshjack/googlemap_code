    <div id="map" style="width:82%; height: 88%;position: absolute !important;"></div>
    <script>
      var icon;
      var map;
        function initMap() {
        // var myOptions = {
        //     zoom: 15,
        //     },
            // map = new google.maps.Map(document.getElementById('map'), myOptions);
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 17,
                center: new google.maps.LatLng(13.009257, 80.2575274),
                mapTypeId: 'roadmap'
            });
        icon = {
            path: "M27.648-41.399q0-3.816-2.7-6.516t-6.516-2.7-6.516 2.7-2.7 6.516 2.7 6.516 6.516 2.7 6.516-2.7 2.7-6.516zm9.216 0q0 3.924-1.188 6.444l-13.104 27.864q-.576 1.188-1.71 1.872t-2.43.684-2.43-.684-1.674-1.872l-13.14-27.864q-1.188-2.52-1.188-6.444 0-7.632 5.4-13.032t13.032-5.4 13.032 5.4 5.4 13.032z",
            fillColor: '#E32831',
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 0.65
        };
        marker = new google.maps.Marker({
            map: map,
            icon: icon,
            animation: google.maps.Animation.DROP,
        });

        map.setCenter(new google.maps.LatLng(<?php echo $riderdetails->rider_latitude; ?>,<?php echo $riderdetails->rider_longitude; ?>));
        marker.setPosition(map.getCenter());
        map.addListener('click', function(e) {
            animatedMove(marker, 10, marker.position, e.latLng);
        });
        }
google.maps.event.addDomListener(window, 'load', initMap);
google.maps.event.trigger(marker, "click", {});

function animatedMove(marker, n, current, moveto) {
  var lat = <?php echo $riderdetails->rider_latitude; ?>;
  var lng = <?php echo $riderdetails->rider_longitude; ?>;
  
  var deltalat = (moveto.lat() - current.lat()) / 100;
  var deltalng = (moveto.lng() - current.lng()) / 100;

  setInterval(function() {
        $.ajax({
            type:'POST',
            url:"<?php echo base_url();?>Dashboard/get_rider_latlong",
            dataType:'JSON',
            data : { 'rider_id' : <?php echo $rider_id; ?>,'latitude' : lat , 'longitude': lng },
            success: function(response) {
                lat=response.result.rider_latitude;
                lng=response.result.rider_longitude;
                console.log(lat+"++"+lng);
                if(response.action=='1') {
                deltalat = (response.result.rider_latitude - current.lat()) / 100;
                deltalng = (response.result.rider_longitude - current.lng()) / 100;
                for (var i = 0; i < 100; i++) {
                    (function(ind) {
                    setTimeout(
                        function() {
                        var lat = marker.position.lat();
                        var lng = marker.position.lng();

                        lat += deltalat;
                        lng += deltalng;
                        latlng = new google.maps.LatLng(lat, lng);
                        marker.setPosition(latlng);
                        }, 10 * ind
                    );
                    })(i)
                }
            } else {
                console.log('I am good');
            }
            }
        });
    }, 5000);
  for (var i = 0; i < 100; i++) {
    (function(ind) {
      setTimeout(
        function() {
          var lat = marker.position.lat();
          var lng = marker.position.lng();

          lat += deltalat;
          lng += deltalng;
          latlng = new google.maps.LatLng(lat, lng);
          marker.setPosition(latlng);
        }, 10 * ind
      );
    })(i)
  }
}

    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCddUuW05eK7ZMG08LsCO7Qt3YN4IFv4n0&callback=initMap"></script>