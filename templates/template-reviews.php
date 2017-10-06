<?php
/**
 * Template Name: Dealer Reviews
 *
 * @package WordPress
 * @subpackage Alpha
 * @since Alpha 1.0
 */

global $wpdb;

require_once( ABSPATH . '/wp-content/plugins/_dld_dealer_reviews/include/functions.php' );
require_once( ABSPATH . '/wp-content/plugins/_dld_dealer_reviews/include/DealerReviews.class.php' );

function dld_reviews() {
  wp_register_style( 'dld-reviews', plugin_dir_url( __DIR__ ) . 'styles/reviews.css', '', '1.0.0'  );
  wp_enqueue_style( 'dld-reviews' );
}
add_action( 'wp_enqueue_scripts', 'dld_reviews' );

get_header();

$googleLink = 'https://www.google.com/search?q=Reinhardt+Toyota&oq=Reinhardt+Toyota&gs_l=psy-ab.3..0i67k1j0j0i67k1j0.9704.12319.0.12551.16.16.0.0.0.0.100.1280.15j1.16.0....0...1.1.64.psy-ab..0.16.1275...0i131k1.iR1mNz5TnxA#lrd=0x888c2976371e7477:0x8444736b9919e1f7,1,';
$facebookLink = 'https://www.facebook.com/pg/ReinhardtToyotaAlabama/reviews/';
$lat = '32.3669454';
$long = '-86.2096623';
$placesID = 'ChIJd3QeN3YpjIgR9-EZmWtzRIQ';

?>

<div class="page-content clearfix">

<section class="reviewHeader">
  <div class="MaxWidth">
    <h1>Latest Reviews: </h1>
    <div class="col-sm-6 col-xs-12">
      <div class="google">
        <img class="googleImg" src="/wp-content/plugins/_dld_dealer_reviews/src/images/googlelogo.png" />
        <div id="googleRating"></div>
        <p><a class="googleLink" href="<?php echo $googleLink; ?>" target="_blank">Read All Google Reviews</a></p>
      </div>
    </div>
    <div class="col-sm-6 col-xs-12">
      <div class="facebook">
        <img class="facebookImg" src="/wp-content/plugins/_dld_dealer_reviews/src/images/facebook-logo.png" />
        <div id="facebookRating"></div>
        <p><a class="facebookLink" href="<?php echo $facebookLink; ?>" target="_blank">Read All Facebook Reviews</a></p>
      </div>
    </div><div class="clear"></div>
  </div>
</section>

<section>
  <div id="reviews">

<?php
      dld_dealer_reviews_show_all_on_page_template();
?>

  </div>
</section>

<section>
  <div id="map"></div>
</section>

</div>

<script>
function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: <?php echo $lat; ?>, lng: <?php echo $long; ?>},
          zoom: 17,
          scrollwheel: false,
        });
        var infowindow = new google.maps.InfoWindow();
        var service = new google.maps.places.PlacesService(map);
        service.getDetails({
          placeId: '<?php echo $placesID; ?>'
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
            var rating = document.getElementById('googleRating');
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
        rating.innerHTML = rate_avg + " " + veh_stars + " Rating";
        });
      }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>

<?php get_footer(); ?>
