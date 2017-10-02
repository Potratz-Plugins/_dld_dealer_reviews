
<?php
/**
 * Template Name: New Testimonials
 *
 */


get_header(); 

?>
<style>
#map {
	height: 100%;
	min-height:400px;
}

#rating {
	font-size:21px;font-weight:bold;
	margin:20px 0;
	color:#fa8300;
}
#reviews{
	margin:10px 0;
	font-size:1.4rem;
}
.rev {padding:15px;}
.rev:nth-child(even) {
	background-color:#eee;
}
.rev-content {

}
h2 {margin:20px 0 !important;
	font-size:2rem;
}
</style>

<div class="page-content clearfix">
	<div class="pad-5 primary-bg"></div>
	<section class="headerBG light-gray-bg">
		<div class="MaxWidth">
			<div class="col-xs-12">
				<div class="ContactTitle white-txt">Testimonials</div>
			</div><div class="clear"></div>
		</div>
	</section><div class="clear"></div>

	<section style="padding:15px 0;background-color:#eee;">
		<div class="MaxWidth">
			<div id="rating"></div>
			<h2>Latest <img src="/wp-content/themes/alpha-dark/css/images/googlelogo.png"> Reviews:</h2>
			<p><a style="margin:-30px 0 0;color:#888;font-size:1.1rem;display:block" href="https://www.google.com/search?q=Reinhardt+Toyota&oq=Reinhardt+Toyota&gs_l=psy-ab.3..0i67k1j0j0i67k1j0.9704.12319.0.12551.16.16.0.0.0.0.100.1280.15j1.16.0....0...1.1.64.psy-ab..0.16.1275...0i131k1.iR1mNz5TnxA#lrd=0x888c2976371e7477:0x8444736b9919e1f7,1," target="_blank">Read All Google Reviews</a></p>
		</div>
	</section>

	<section style="padding:15px 0;background-color:#fff;">
		<div id="reviews"></div>
	</section>
</div>
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

          	console.log(rate_avg);
          	console.log(rev);

          }

          	var rating = document.getElementById('rating');
          	var reviews = document.getElementById('reviews');

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
				rating.innerHTML = rate_avg + " " + veh_stars + " Rating <span style='color:#000'> at " + place + "</span>";

				var google_review = '';
				for (i = 0; i < 5; i++) {
					if(rev[i]['rating'] >= 3) {
					google_review += "<section class='rev'><div class='MaxWidth rev-content'><i class='fa fa-quote-left' style='font-size:2rem;padding:10px 10px 0 10px;color:#888'></i>" + rev[i]['text'] + "<i class='fa fa-quote-right' style='font-size:2rem;padding:0 10px;color:#888'></i><p style='margin-top:15px;'> - " + rev[i]['author_name'] + "</p></div></section>";
					}
				}

				reviews.innerHTML = google_review;
        });
      }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>

 <div id="map"></div>



<?php get_footer(); ?>