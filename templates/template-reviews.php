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


if(get_option('FacebookAverageReviewOptionValue') === false){
  update_option('FacebookAverageReviewOptionValue', '0', false);
}
if(get_option('GoogleAverageReviewOptionValue') === false){
  update_option('GoogleAverageReviewOptionValue', '0', false);
}


$s_googleAverageReview = get_option('GoogleAverageReviewOptionValue');    
$f_facebookAverageReview = floatval(get_option('FacebookAverageReviewOptionValue'));  
$s_showFacebookAverageReview = get_option('ShowFacebookAverageReviewOptionValue');
$s_showGoogleAverageReview = get_option('ShowGoogleAverageReviewOptionValue');
$s_facebook_average_review_span = '';

// SET FACEBOOK AVERAGE RATING html
for ($i = 1; $i <= 5; $i++) {
  if ($i <= $f_facebookAverageReview) {
    $s_facebook_average_review_span = $s_facebook_average_review_span.'<i class="star fa fa-star" style="color:blue"></i>';
  } else {
    if (($i - 1) < $f_facebookAverageReview) {
      $s_facebook_average_review_span = $s_facebook_average_review_span.'<i class="star fa fa-star-half-o" style="color:blue"></i>';
    } else {
      $s_facebook_average_review_span = $s_facebook_average_review_span.'<i class="star fa fa-star-o" style="color:blue"></i>';
    }
  }
}


 // DO WE SHOW GOOGLE / FACEBOOK REVIEWS
 $b_showFacebook = true;
 $b_showGoogle = true;
 if(get_option('ShowFacebookReviewsOptionValue') === false){
     update_option('ShowFacebookReviewsOptionValue', 'true', false);
 }
 if(get_option('ShowGoogleReviewsOptionValue') === false){
     update_option('ShowGoogleReviewsOptionValue', 'true', false);
 }
 $s_showFacebookReviews = get_option('ShowFacebookReviewsOptionValue');
 $s_showGoogleReviews = get_option('ShowGoogleReviewsOptionValue');
 if($s_showFacebookReviews == 'false'){
     $b_showFacebook = false;
     echo '
     <style>
     .facebook{
       display:none; 
     }
     </style>
      ';
 }
 if($s_showGoogleReviews == 'false'){
     $b_showGoogle = false;
     echo '
    <style>
    .google{
      display:none; 
    }
    </style>
     ';
 }


 // DO WE SHOW GOOGLE / FACEBOOK AVERAGE REVIEWS
 $b_showFacebook = true;
 $b_showGoogle = true;
 if(get_option('ShowFacebookAverageReviewOptionValue') === false){
  update_option('ShowFacebookAverageReviewOptionValue', 'true', false);
}
if(get_option('ShowGoogleAverageReviewOptionValue') === false){
  update_option('ShowGoogleAverageReviewOptionValue', 'true', false);
}
 $s_showFacebookAverageReviews = get_option('ShowFacebookAverageReviewOptionValue');
 $s_showGoogleAverageReviews = get_option('ShowGoogleAverageReviewOptionValue');
 if($s_showFacebookAverageReviews == 'false'){
     $b_showFacebookAverageReviews = false;
     echo '
     <style>
     .facebookOverall{
       display:none; 
     }
     </style>
      ';
 }
 if($s_showGoogleAverageReviews == 'false'){
     $b_showGoogleAverageReviews = false;
     echo '
    <style>
    .googleOverall{
      display:none; 
    }
    </style>
     ';
 }


?>

<div class="page-content clearfix">

<section class="reviewHeader">
  <div class="MaxWidth">
    <h1>Latest Reviews: </h1>
    <div class="col-sm-6 col-xs-12">
      <div class="google">
        <img class="googleImg" src="/wp-content/plugins/_dld_dealer_reviews/src/images/googlelogo.png" />
        <div id="googleRating" class="googleOverall"></div>
        <p class="googleOverall"><a class="googleLink" href="<?php echo $googleLink; ?>" target="_blank">Read All Google Reviews</a></p>
      </div>
    </div>
    <div class="col-sm-6 col-xs-12">
      <div class="facebook">
        <img class="facebookImg" src="/wp-content/plugins/_dld_dealer_reviews/src/images/facebook-logo.png" />
        <div class="facebookOverall" id="facebookRating" style="color:blue"><?php echo $f_facebookAverageReview.' '.$s_facebook_average_review_span.' Rating'; ?></div>
        <p class="facebookOverall"><a class="facebookLink" href="<?php echo $facebookLink; ?>" target="_blank">Read All Facebook Reviews</a></p>
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
