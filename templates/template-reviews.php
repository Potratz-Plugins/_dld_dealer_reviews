<?php 
// GET GOOGLE REVIEWS
?>

<div id="showAllData"></div>


<script>
function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 32.3669454, lng: -86.2096623},
          zoom: 17,
        });

        var infowindow = new google.maps.InfoWindow();
        var service = new google.maps.places.PlacesService(map);

        service.getDetails({
          placeId: 'ChIJd3QeN3YpjIgR9-EZmWtzRIQ'
        }, function(place, status) {
          if (status === google.maps.places.PlacesServiceStatus.OK) {

            var marker = new google.maps.Marker({
              map: map,
              position: place.geometry.location
            });

          	var rev = place.reviews;
          	var rate_avg = place.rating;
          	var place = place.name;

          }

          // DISPLAY
             var rating = document.getElementById('rating');
              var showAllData = document.getElementById('showAllData');


            // STARS DISPLAY
          	var veh_stars = '';
				for (i = 1; i <= 5; i++) {
					if (i <= rate_avg) {
						veh_stars += '<i class="star fa fa-star"></i>';
					} else {
						if ((i - 1) < rate_avg) {
							veh_stars += '<i class="star fa fa-star-half-o"></i>';
						} else {
							veh_stars += '<i class="star fa fa-star-o"></i>';
						}
					}
                }
                // DISPLAY Average rating for dealership 
				showAllData.innerHTML +=  "AVERAGE RATING FOR " + place + ": " + rate_avg + " " + veh_stars + "</br>";
                

                console.log(rev);
				for (i = 0; i < 5; i++) {
					if(rev[i]['rating'] >= 3) {
                        showAllData.innerHTML += 
                        "NAME : " + rev[i]['author_name'] + "</br>" +
                        "IMAGE : <img src='" + rev[i]['profile_photo_url'] + "' style='width:304px;height:228px;'></br>" +
                        "RATING : " + rev[i]['rating'] + "</br>" +
                        "TEXT : " + rev[i]['text'] + "</br>";
					}
				}
        });
    }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>

 <div id="map" style="display:none"></div>


