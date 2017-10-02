<?php

function display_dld_fb_reviews_button(){
    ?>
        <fb:login-button
            scope='public_profile,email,user_birthday,user_location,manage_pages,user_posts,pages_show_list,ads_management'
            onlogin="getLoginStatus();" id="loginButton" style="display:block">
        </fb:login-button>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="#" id='logout' onclick='logout();'>logout</a>
    <?php
    }



function refresh_dld_fb_reviews($s_pageID, $s_appID, $s_appSecret, $s_llAccessToken, $i_minimum_review_num ){

    session_start();
 $token = '';
             //TEST - show token from url -  echo '</br>token from url : ' . htmlspecialchars($_GET["token"]).'</br>';
 $token = htmlspecialchars($_GET["token"]);
 // echo '</br>the token : '.$token.'</br>token was last line</br>';
 $_SESSION['facebook_access_token'] = $token;
             // TEST - show access token -  echo 'fb access token : '.$token.'</br>'; var_dump($_SESSION['facebook_access_token']);
 // CREATE Facebook object
 $fb = new Facebook\Facebook([
 'app_id' => $s_appID , // Replace {app-id} with your app id
 'app_secret' => $s_appSecret,
 'default_graph_version' => 'v2.2',
  ]);
             // TEST - show facebook object -   echo '<pre>'; var_dump($fb); echo '</pre>';
 $helper = $fb->getRedirectLoginHelper();
 

// IF Long Lived Access Token exists as option value, GET Long Lived Access Token from option value
 if ( get_option( 'LongLivedAccessToken') != false ) {
     $longLivedAccessToken = get_option( 'LongLivedAccessToken' );
             // TEST - show long lived access token -   
            // echo '</br>got long lived acc tok : '.$longLivedAccessToken.'</br>';
 }
// ELSE - get long lived access token using short lived access token
 else {
     if(strlen($token)>0){
         $fb->setDefaultAccessToken($token);
         // oauth 2.0 client handler
         $oAuth2Client = $fb->getOAuth2Client();
         // exchange a short-lived token for a long-lived one
          $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
             // TEST - show long lived access token -   echo '</br>long lived access token :'.$longLivedAccessToken.'</br>';
         update_option( 'LongLivedAccessToken', $longLivedAccessToken, null, 'no' );
     }
     else{
         echo 'Sorry, no reviews are available';
         exit;
     }
 }
// END IF ELSE
 
 // Use long lived access token as session and default token
 $_SESSION['facebook_access_token'] = $longLivedAccessToken;
 $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
 // TEST - show long lived access token -   echo '</br>long lived access token : '.$longLivedAccessToken.'</br>';
 
 try {
     // GET page access token 
     $response = $fb->get('/'.$s_pageID.'?fields=access_token ');
     $json = json_decode($response->getBody());
     $page_token = $json->access_token;
             // TEST - show page token -   echo '<pre>';  echo '</br>page token : </br>';  var_dump($page_token); echo '</pre>';
     $response = $fb->get('/'.$s_pageID.'/ratings', $page_token);
     $json = json_decode($response->getBody());
             // TEST - show response -   echo '<pre>'; echo '</br>ratings response: </br>'; var_dump($response); echo '</br>json: </br>'; var_dump($json);
 } catch(Facebook\Exceptions\FacebookResponseException $e){
     // SHOW ERROR if graph returns an error
     echo 'graph returned an error : '.$e->getMessage();
     unset($_SESSION['facebook_access_token']);
     exit;
 } catch(Facebook\Exceptions\FacebookSDKException $e){
     // SHOW ERROR if validation fails or other local issues
     echo 'facebook sdk returned an error : '.$e->getMessage();
     exit;
 }
 $a_allFBReviews = array();
 // LOOP through all returned reviews, pull photos and display data
 $i_count = 0;
 foreach($json->data as $record){
     // only show review if above or equal to minimum review number (set as option)
     if($record->rating >= $i_minimum_review_num){ 
        

        // $o_Review->order = 9;
        // echo $o_Review->order;
        
         // GET reviewer's picture using their fb id
         $response = $fb->get('/'.$record->reviewer->id.'/picture?type=large', $page_token); 
         $graphNode = $response->getHeaders();
         $s_image_url = $graphNode['Location'];
         $s_fb_image = '<img src="'.$s_image_url.'" class="photoDisplay"/>';  
         $s_fb_name = $record->reviewer->name;
         $i_fb_rating = $record->rating;
         if (isset($record->review_text)) { 
             $s_fb_review_text = RemoveBS($record->review_text);
         } else {
             $s_fb_review_text = '';
         } 
         // DISPLAY all reviews
         ?>  
         <?php // fb_show_reviews($s_fb_image, $s_fb_name, $i_fb_rating, $s_fb_review_text); ?>
         <?php 



        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text);
        $i_count++;
       
        $o_Review->dld_facebook_reviews_save_to_db();

         // SAVE REVIEW TO DB

         // pre_var_dump($o_Review, 'review : ');
         $a_allFBReviews[] = $o_Review;
         echo '<div class="fb_reviews" style="width:600px;padding:15px;">';
         $o_Review->show_dealer_review();
         echo '</div>';
         }
    }
    // pre_var_dump($a_allFBReviews, 'all fb reviews : ');
    // foreach
    // show_dealer_reviews($a_allFBReviews);
 }


 function refresh_dld_google_reviews($i_minimum_review_num ){
    ?>

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
              var allGoogleReviews = document.getElementById('textAreaGoogleReviews');
              var showThereAreResults = document.getElementById('spanGoogleReviewsResults');
              var saveGoogleReviews = document.getElementById('btnSaveGoogleReviews');


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
				// allGoogleReviews.innerHTML +=  "AVERAGE RATING FOR " + place + ": " + rate_avg + " " + veh_stars + "</br>";
                

                console.log(rev);
                var count = 1;
				for (i = 0; i < 5; i++) {
					if(rev[i]['rating'] >= 3) {
                        
                        var reviewNo = count.toString();
                        allGoogleReviews.innerHTML += 
                        "GOOGLEREVIEWNAME:" + rev[i]['author_name'] + ",,," +
                        "GOOGLEREVIEWIMAGE:" + rev[i]['profile_photo_url'] + ",,," +
                        "GOOGLEREVIEWRATING:" + rev[i]['rating'] + ",,," +
                        "GOOGLEREVIEWTEXT:" + rev[i]['text'] + ",.,.,";
                        count++;

                    }
                }
                count--;
                    saveGoogleReviews.style.display = 'block';
                    showThereAreResults.innerHTML = count + " Google reviews retrived.</br></br>  Please click 'Save New Google Reviews' to store them.";
        });
    }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>
<div id="map" style="display:none"></div>
    <?php
 }



 function dld_dealer_reviews_show_all_on_page_template($a_reorderReviewsArray = null) {
    $a_allReviewsFromDB = dld_facebook_get_all_reviews_raw_data();
    //  pre_var_dump($a_allReviewsFromDB);
    $a_allReviewsAsDealerReviews = array();

    foreach($a_allReviewsFromDB->posts as $o_ReviewData){
        // pre_var_dump($o_ReviewData, 'SOME REVIEW DATA');
        // SET object variables
        $s_review_type = $o_ReviewData->post_type;
        $s_fb_name = $o_ReviewData->post_excerpt;
        $s_image_url = $o_ReviewData->post_title;
        $i_fb_rating = $o_ReviewData->comment_status;  
        $s_fb_review_text = $o_ReviewData->post_content;
        $i_postID = $o_ReviewData->ID;

        // CREATE DealerReviews object
        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, $i_postID );
        echo '<div class="fb_reviews" style="width:600px;padding:15px;">';
       $o_Review->show_dealer_review();
        echo '</div>';

        // Add this DealerReview object to array
        $a_allReviewsAsDealerReviews[] = $o_Review;
    }
}



function dld_dealer_reviews_show_all_from_db_sortable($s_googleReviewsRawData) {
    if(strlen($s_googleReviewsRawData) > 0){
        // SAVE ALL GOOGLE REVIEWS TO DB
        dld_process_google_reviews_from_string($s_googleReviewsRawData);
    }
    
    $a_allReviewsFromDB = dld_facebook_get_all_reviews_raw_data();
    $a_inactiveReviews = array();

    TODO: // get array (option value)
	if ( get_option( 'DealerReviewsActivePostIds') == false ) {
		add_option( 'DealerReviewsActivePostIds', '', null, 'no');
	}
    $s_postIdsOfActiveReviews = get_option('DealerReviewsActivePostIds');

    echo '<h2><strong>Active Reviews:</strong></h2>';
    echo '<ul id="sortable" class="ulSortable">';

    // DISPLAY ALL DATABASE STORED REVIEWS
    foreach($a_allReviewsFromDB->posts as $o_ReviewData){

        // SET object variables
        $s_review_type = $o_ReviewData->post_type;
        $s_fb_name = $o_ReviewData->post_excerpt;
        $s_image_url = $o_ReviewData->post_title;
        $i_fb_rating = $o_ReviewData->comment_status;  
        $s_fb_review_text = $o_ReviewData->post_content;
        $i_postID = $o_ReviewData->ID;
        $s_postIDString = strval($i_postID);

        // CREATE DealerReviews object
        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, $i_postID );

        // TODO: if post id is in array, show review, else add it to array of inactive reviews 

        if(strpos($s_postIdsOfActiveReviews, $s_postIDString) !== false){
            $o_Review->show_dealer_review_sortable(true);
        } else{
            // Add this DealerReview object to inactive reviews array
            $a_inactiveReviews[] = $o_Review;
        }
    }

   echo '</ul>';
  echo '<hr  style="width:80%;" align="left">';
   echo '<h2><strong>Inactive Reviews:</strong></h2>';
   echo '<ul id="sortableInactive" class="ulSortableInactive">';

    foreach($a_inactiveReviews as $o_inactiveReview){
        $o_inactiveReview->show_dealer_review_sortable();
    }
   echo '</ul>';
}


function dld_process_google_reviews_from_string($s_rawData){
    
    // Break string of all reviews into strings for each review
    $a_almostObjects = explode(",.,.,", $s_rawData);
    // pre_var_dump($a_lessRawData, 'less raw data');
    foreach($a_almostObjects as $s_review){
        // check if review string has data
        if(strlen($s_review) > 0){
            // break this review string into fields 
            $a_reviewFields = explode(",,,", $s_review);
            // pre_var_dump($a_reviewFields, 'a review');
            foreach($a_reviewFields as $s_reviewField){
                $a_reviewFieldPair = explode(":", $s_reviewField, 2);
                //pre_var_dump($a_reviewFieldPair, 'Rev Field Pair :');
                if($a_reviewFieldPair[0] == 'GOOGLEREVIEWNAME'){
                    $s_fb_name = $a_reviewFieldPair[1];
                }
                if($a_reviewFieldPair[0] == 'GOOGLEREVIEWIMAGE'){
                    $s_image_url = $a_reviewFieldPair[1];
                }
                if($a_reviewFieldPair[0] == 'GOOGLEREVIEWRATING'){
                    $i_fb_rating = intval($a_reviewFieldPair[1]);
                }
                if($a_reviewFieldPair[0] == 'GOOGLEREVIEWTEXT'){
                    $s_fb_review_text = $a_reviewFieldPair[1];
                }
            }

            // CREATE DealerReviews object
            $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text );
            $o_Review->dld_facebook_reviews_save_to_db();
        }
    }
}


function fb_get_stars($rating) {
 foreach (array(1,2,3,4,5) as $val) {
     $score = $rating - $val;
     if ($score >= 0) {
         ?><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="28" height="28" viewBox="0 0 1792 1792"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="#4080ff"></path></svg><?php
     } else if ($score > -1 && $score < 0) {
         ?><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="28" height="28" viewBox="0 0 1792 1792"><path d="M1250 957l257-250-356-52-66-10-30-60-159-322v963l59 31 318 168-60-355-12-66zm452-262l-363 354 86 500q5 33-6 51.5t-34 18.5q-17 0-40-12l-449-236-449 236q-23 12-40 12-23 0-34-18.5t-6-51.5l86-500-364-354q-32-32-23-59.5t54-34.5l502-73 225-455q20-41 49-41 28 0 49 41l225 455 502 73q45 7 54 34.5t-24 59.5z" fill="#4080ff"></path></svg><?php
     } else {
         ?><svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="28" height="28" viewBox="0 0 1792 1792"><path d="M1201 1004l306-297-422-62-189-382-189 382-422 62 306 297-73 421 378-199 377 199zm527-357q0 22-26 48l-363 354 86 500q1 7 1 20 0 50-41 50-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z" fill="#ccc"></path></svg><?php
     }
 }
}



function RemoveBS($Str) {  
$StrArr = str_split($Str); $NewStr = '';
foreach ($StrArr as $Char) {    
 $CharNo = ord($Char);
 if ($CharNo == 163) { $NewStr .= $Char; continue; } // keep £ 
 if ($CharNo > 31 && $CharNo < 127) {
   $NewStr .= $Char;    
 }
}  
return $NewStr;
}



function dld_facebook_get_all_reviews_raw_data(){
    $a_allReviewsFromDB =
        new WP_Query(
                array(
                        'post_type' 	=> 'dealerreview',
                        'post_status' 	=> 'publish',
                        'posts_per_page' 	=> '-1'
                )
        );
return $a_allReviewsFromDB;
    }



function dld_save_new_review_order($s_postIdsActiveReviews){
    update_option( 'DealerReviewsActivePostIds', $s_postIdsActiveReviews, null );
}






// ***************** FUNCTION - FOR TESTING - PASS ARRAY / OBJECT FOR 1st param, optional heading string for 2nd param
 function pre_var_dump($a_var, $s_heading = ''){if($s_heading != ''){ echo "<h2>$s_heading</h2>";} echo '<pre>';var_dump($a_var);echo '</pre>';}
// ***************** END FOR TESTING 


?>