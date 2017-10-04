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
                // TEST - echo '</br>the token : '.$token.'</br>token was last line</br>';
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
                // TEST - echo '</br>got long lived acc tok : '.$longLivedAccessToken.'</br>';
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
 // LOOP through all returned reviews, pull photos and display data
 $i_count = 0;
 foreach($json->data as $record){

    // only show review if above or equal to minimum review number (set as option)
     if(intval($record->rating) >= intval($i_minimum_review_num)){ 
        pre_var_dump(intval($record->rating), 'intval record rating : ');
        pre_var_dump($i_minimum_review_num, 'i_minimum_review_num');

         // GET reviewer's picture using their fb id
         $response = $fb->get('/'.$record->reviewer->id.'/picture?type=large', $page_token); 
         $graphNode = $response->getHeaders();
         $s_image_url = $graphNode['Location'];
         $s_fb_image = '<img src="'.$s_image_url.'" class="photoDisplay"/>';  
         $s_fb_name = $record->reviewer->name;
         $i_fb_rating = $record->rating;
         $s_author_id = $record->reviewer->id;
         pre_var_dump($s_author_id, 'author id');
         if (isset($record->review_text)) { 
             $s_fb_review_text = RemoveBS($record->review_text);
         } else {
             $s_fb_review_text = '';
         } 

        // CREATE REVIEW OBJECT
        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, 'facebook', $s_author_id);
        $i_count++;
       
        // SAVE REVIEW TO DB
        $o_Review->dld_dealer_reviews_save_to_db();
         }
    }
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
            var sel = document.getElementById('selectMinimumReview');
            var opt = sel.options[sel.selectedIndex];
            var minimumRating =  opt.value;

             var rating = document.getElementById('rating');
              var allGoogleReviews = document.getElementById('textAreaGoogleReviews');
              var showThereAreResults = document.getElementById('spanGoogleReviewsResults');
              var showThereAreResultsAfter = document.getElementById('spanGoogleReviewsResultsAfter');
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
					if(rev[i]['rating'] >= minimumRating) {
                        
                        var reviewNo = count.toString();
                        allGoogleReviews.innerHTML += 
                        "GOOGLEREVIEWNAME:" + rev[i]['author_name'] + ",,," +
                        "GOOGLEREVIEWIMAGE:" + rev[i]['profile_photo_url'] + ",,," +
                        "GOOGLEREVIEWRATING:" + rev[i]['rating'] + ",,," +
                        "GOOGLEREVIEWTEXT:" + rev[i]['text'] + ",,," +
                        "GOOGLEREVIEWTIME:" + rev[i]['time'] + ",.,.,";
                        count++;

                    }
                }
                count--;
                    saveGoogleReviews.style.display = 'inline';
                    showThereAreResults.innerHTML = "</br></br>" + count + " Google reviews retrieved.</br></br>  To save, please click:";
                    
        });
    }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>
<div id="map" style="display:none"></div>
    <?php
 }



function dld_dealer_reviews_show_all_on_page_template() {
   
    // GET MINIMUM REVIEW RATING
    if(get_option('FacebookMinimumReviewOptionValue') === false){
        update_option('FacebookMinimumReviewOptionValue', '3', false);
    }
    $i_minimum_review_num = intval(get_option('FacebookMinimumReviewOptionValue'));
    
    $a_allReviewsFromDB = dld_dealer_reviews_get_all_reviews_raw_data();

    // pre_var_dump($a_allReviewsFromDB, 'all reviews from db');
    $a_activeReviews = array();
    $a_activeReviewsSorted = array();

    // GET ACTIVE / INACTIVE REVIEW POST ID's
    // get array of active reviews post ids, which is also used to display the order
    if ( get_option( 'DealerReviewsActivePostIds') === false ) {
		add_option( 'DealerReviewsActivePostIds', '', null, 'no');
    }
    $a_postIdsActive = array();
    $s_postIdsOfActiveReviews = get_option('DealerReviewsActivePostIds');
    $a_postIdsActive = explode(",", $s_postIdsOfActiveReviews);
    // echo $DealerReviewsActivePostIds;


    // DISPLAY ALL DATABASE STORED REVIEWS
    foreach($a_allReviewsFromDB->posts as $o_ReviewData){

       // pre_var_dump($o_ReviewData, 'review data');
        // SET object variables
        // TODO: NOT IN post_type
        $s_author_id = $o_ReviewData->post_mime_type;
        $s_review_type = $o_ReviewData->post_status;
        $s_fb_name = $o_ReviewData->post_excerpt;
        $s_image_url = $o_ReviewData->post_title;
        $i_fb_rating = $o_ReviewData->comment_status;  
        $s_fb_review_text = $o_ReviewData->post_content;
        $i_postID = $o_ReviewData->ID;
        $s_postIDString = strval($i_postID);

        // CREATE DealerReviews object
        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, $s_review_type, $s_author_id, $i_postID);

        // TODO: add active reviews to array in in order of DealerReviewsActivePostIds
        // IF the post id is found in array of active review post id's
        
        if(strpos($s_postIdsOfActiveReviews, $s_postIDString) !== false){
            $a_activeReviews[] = $o_Review;
        } 
    }

    foreach($a_postIdsActive as $s_postIdActive){
        foreach($a_activeReviews as $a_activeReview){
            if($s_postIdActive == $a_activeReview->id){
                $a_activeReviewsSorted[] = $a_activeReview;
            }
        }
    }

    // ************* DISPLAY REVIEWS **************
    // NEW REVIEWS
    echo '<hr  style="width:100%;" align="left">';
    // ACTIVE REVIEWS
    foreach($a_activeReviewsSorted as $o_activeReview){
        $o_activeReview->show_dealer_review_on_page(true);
    }
}



function dld_dealer_reviews_show_all_from_db_sortable($s_googleReviewsRawData = '') {
    // GET GOOGLE REVIEWS DATA IF PASSED IN
    if(strlen($s_googleReviewsRawData) > 0){
        // PROCESS AND SAVE ALL GOOGLE REVIEW DATA TO DB
        dld_process_google_reviews_from_string($s_googleReviewsRawData);
    }

    // GET MINIMUM REVIEW RATING
    if(get_option('FacebookMinimumReviewOptionValue') === false){
        update_option('FacebookMinimumReviewOptionValue', '3', false);
    }
    $i_minimum_review_num = intval(get_option('FacebookMinimumReviewOptionValue'));
    $a_allReviewsFromDB = dld_dealer_reviews_get_all_reviews_raw_data();
    $a_allDealerReviews = array();
    $a_activeReviews = array();
    $a_activeReviewsSorted = array();
    $a_inactiveReviews = array();
    $a_newReviews = array();

    // GET ACTIVE / INACTIVE REVIEW POST ID's
    // get array of active reviews post ids, which is also used to display the order
    if ( get_option( 'DealerReviewsActivePostIds') === false ) {
		add_option( 'DealerReviewsActivePostIds', '', null, 'no');
	}
    $s_postIdsOfActiveReviews = get_option('DealerReviewsActivePostIds');
    $a_postIdsActive = explode(",", $s_postIdsOfActiveReviews);

    // get array of inactive review post ids, signifying they are not a new review and therefore will not be displayed as active
	if ( get_option( 'DealerReviewsInactivePostIds') === false ) {
		add_option( 'DealerReviewsInactivePostIds', '', null, 'no');
	}
    $s_postIdsOfInactiveReviews = get_option('DealerReviewsInactivePostIds');

    // DISPLAY ALL DATABASE STORED REVIEWS
    foreach($a_allReviewsFromDB->posts as $o_ReviewData){

        // SET object variables
        $s_author_id = $o_ReviewData->post_mime_type;
        $s_review_type = $o_ReviewData->post_status;
        $s_fb_name = $o_ReviewData->post_excerpt;
        $s_image_url = $o_ReviewData->post_title;
        $i_fb_rating = $o_ReviewData->comment_status;  
        $s_fb_review_text = $o_ReviewData->post_content;
        $i_postID = $o_ReviewData->ID;
        $s_postIDString = strval($i_postID);

        // CREATE DealerReviews object
        $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, $s_review_type, $s_author_id, $i_postID);

        // IF the post id is found in array of active review post id's
        if(strpos($s_postIdsOfActiveReviews, $s_postIDString) !== false && intval($i_fb_rating) >= $i_minimum_review_num){
            $a_activeReviews[] = $o_Review;
        } 
        // ELSE IF the post id is found in array of inactive review post id's
        else if(strpos($s_postIdsOfInactiveReviews, $s_postIDString) !== false || intval($i_fb_rating) < $i_minimum_review_num) {
            // Add this DealerReview object to inactive reviews array
            $a_inactiveReviews[] = $o_Review;
        }
        // ELSE the post id has not been found in active or inactive reviews, therefore this is a new review. 
        else{
            $a_newReviews[] = $o_Review;
        }
    }

    foreach($a_postIdsActive as $s_postIdActive){
            foreach($a_activeReviews as $a_activeReview){
                if($s_postIdActive == $a_activeReview->id){
                    $a_activeReviewsSorted[] = $a_activeReview;
                }
            }
        }

    $b_isFirstNew = true;
    $b_isFirstActive = true;
    // ************* DISPLAY REVIEWS **************
    // NEW REVIEWS
    echo '<ul id="sortable" class="ulSortable">';
    foreach($a_newReviews as $o_newReview){
        if($b_isFirstNew){
            echo '<h2><strong>New Reviews:</strong></h2>';
            $b_isFirstNew = false;
        }
        $o_newReview->show_dealer_review_sortable(true);
    }

    // ACTIVE REVIEWS
    foreach($a_activeReviewsSorted as $o_activeReview){
        if($b_isFirstActive){
            echo '<hr  style="width:100%;" align="left">';
            echo '<h2><strong>Active Reviews:</strong></h2>';
            $b_isFirstActive = false;
        }
        $o_activeReview->show_dealer_review_sortable(true);
    }
    echo '</ul>';
    echo '<hr  style="width:80%;" align="left">';


    // INACTIVE REVIEWS
   echo '<ul id="sortableInactive" class="ulSortableInactive">';
   echo '<hr  style="width:100%;" align="left">';
   echo '<h2><strong>Inactive Reviews:</strong></h2>';

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
        $i_auth_id = 0;
        $s_time_string = '';
        $s_length_of_text = '';
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
                    $s_length_of_text = strval(strlen($s_fb_review_text));
                }
                if($a_reviewFieldPair[0] == 'GOOGLEREVIEWTIME'){
                    if(strlen($a_reviewFieldPair[1]) > 0){
                        $s_time_string = $a_reviewFieldPair[1];
                    } 
                } 
            }

              //Create unique author id / post_author by concatenating time string and text length
              $s_author_id = $s_time_string.$s_length_of_text;

            // CREATE DealerReviews object
            $o_Review = new DealerReviews($s_fb_name, $s_image_url, $i_fb_rating,  $s_fb_review_text, 'google', $s_author_id );
            $o_Review->dld_dealer_reviews_save_to_db();
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



function dld_dealer_reviews_get_all_reviews_raw_data(){
    $a_allReviewsFromDB = 
        new WP_Query(
                array(
                        'post_type' 	=> 'dealerreview',
                        'post_status' 	=> 'google',
                        'posts_per_page' 	=> '-1'
                ) 
        );
       
return $a_allReviewsFromDB;
    }



function dld_save_new_review_order($s_postIdsActiveReviews, $s_postIdsInactiveReviews){
    update_option( 'DealerReviewsActivePostIds', $s_postIdsActiveReviews, null );
    update_option( 'DealerReviewsInactivePostIds', $s_postIdsInactiveReviews, null );
}

?>