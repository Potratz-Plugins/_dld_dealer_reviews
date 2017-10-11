<?php

function dld_setup_dealer_reviews_admin_page(){

    // ************** TEST BEGIN - NOT SURE WHAT OF THIS WILL NEED
    // Need to require the config file to have access to the wordpress functions
    require_once( ABSPATH."wp-admin/includes/file.php" );
    require_once( ABSPATH."wp-admin/includes/media.php" );
    require_once( ABSPATH."wp-admin/includes/image.php" );

    // include the sharedin database connector
    if ( $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ) {
        include_once '/Applications/XAMPP/xamppfiles/htdocs/sharedin/resources/DBSharedBase.php';
    } else {
        include_once '/home/sharedin/public_html/resources/DBSharedBase.php';
    }
    // ************** TEST END - NOT SURE WHAT OF THIS WILL NEED


    // GET OPTION VALUES 
    if(get_option('FacebookPageIDOptionValue') === false){
        update_option('FacebookPageIDOptionValue', '321496564547254', false);
    }
    if(get_option('FacebookAppIDOptionValue') === false){
        update_option('FacebookAppIDOptionValue', '251021862073231', false);
    }
    if(get_option('FacebookAppSecretOptionValue') === false){
        update_option('FacebookAppSecretOptionValue', '76d9e8dee48fd7dbc2497c182e36d3eb', false);
    }
    if(get_option('FacebookLLAccessTokenOptionValue') === false){
        update_option('FacebookLLAccessTokenOptionValue', 'EAADkTZATrc48BABbW2z48zyZCOqd3GEWvvXftDxKGuBVOTpAImGCaxZBiu2QXOeqdP5eM1nZBATdF9OHKEJgHa2yTN0VFZCvZBZBQy43rGyEXEGYSZBzyULh6NPr4WKA6lXaZCEYJhYmUjku5WNctpCKWX1VcyCRJ5GIZD', false);
    }
    if(get_option('DealerReviewMinimumRatingOptionValue') === false){
        update_option('DealerReviewMinimumRatingOptionValue', '3', false);
    }
    if(get_option('FacebookAverageReviewOptionValue') === false){
        update_option('FacebookAverageReviewOptionValue', '0', false);
    }
    if(get_option('GoogleAverageReviewOptionValue') === false){
        update_option('GoogleAverageReviewOptionValue', '0', false);
    }
    if(get_option('ShowFacebookAverageReviewOptionValue') === false){
        update_option('ShowFacebookAverageReviewOptionValue', 'true', false);
    }
    if(get_option('ShowGoogleAverageReviewOptionValue') === false){
        update_option('ShowGoogleAverageReviewOptionValue', 'true', false);
    }
    if(get_option('ShowFacebookReviewsOptionValue') === false){
        update_option('ShowFacebookReviewsOptionValue', 'true', false);
    }
    if(get_option('ShowGoogleReviewsOptionValue') === false){
        update_option('ShowGoogleReviewsOptionValue', 'true', false);
    }

    $s_pageID =  get_option('FacebookPageIDOptionValue');
    $s_appID = get_option('FacebookAppIDOptionValue');
    $s_appSecret = get_option('FacebookAppSecretOptionValue');
    $s_llAccessToken = get_option('FacebookLLAccessTokenOptionValue');
    $i_minimum_review_num = intval(get_option('DealerReviewMinimumRatingOptionValue'));
    $i_mrn = $i_minimum_review_num;
    $f_googleAverageReview = floatval(get_option('GoogleAverageReviewOptionValue'));    
    $f_facebookAverageReview = floatval(get_option('FacebookAverageReviewOptionValue'));  
    $s_showFacebookAverageReview = get_option('ShowFacebookAverageReviewOptionValue');
    $s_showGoogleAverageReview = get_option('ShowGoogleAverageReviewOptionValue');
    $s_showFacebookReviews = get_option('ShowFacebookReviewsOptionValue');
    $s_showGoogleReviews = get_option('ShowGoogleReviewsOptionValue');

   

    ?>
<hr>
<div class="hero-unit">
<h1>DLD Dealer Reviews</h1>
<!-- <a id="aHideShow" style="font-weight:bold;font-size:1.5em;" class="btn btn-primary">SHOW HELP INFO</a> -->

<p class="expandableP">
<strong><big> &nbsp;&#8226;&nbsp; Facebook Connect Data - </big></strong> This will likely not need to be changed.  These are fields used to access Facebook's Api, through which reviews are pulled.</br>
<strong><big> &nbsp;&#8226;&nbsp; Set Minimum Review Rating - </big></strong> This will set/update a minimal numeric rating for reviews that will be displayed, as well as reviews that will be pulled in for Facebook.  
Google's Api works differently - five reviews at most can be pulled in, and they cannot be limited by a minimum rating.  However, setting the minimum review limit will still limit which Google reviews will display on the site.</br>
<strong><big> &nbsp;&#8226;&nbsp; Show Review Types / Show Average Reviews - </big></strong> All Facebook or Google reviews can be hidden from display on page.  Also the average review section which include a link to additional 
reviews can be hidden for each type of review.</br>
<strong><big> &nbsp;&#8226;&nbsp; Get New Reviews - </big></strong> Both Facebook and Google reviews must initially be retrieved from their respective Apis, using a slightly different process. While retrieving Facebook reviews 
is a one click process, retrieving Google Reviews requires an initial click of <strong>'Get New Google Reviews'</strong> after which a hidden button will appear to <strong>'Save Google Reviews'</strong>, which must be clicked to 
complete the retrieval process.  Once this is done, the reviews have been retrieved and stored, and can be displayed on site.</br>
<strong><big> &nbsp;&#8226;&nbsp; Updating Reviews - </big></strong> <strong>'Get New Google Reviews'</strong> and <strong>'Get New Facebook Reviews'</strong> can be clicked again after time has passed to retrieve new reviews, 
which will appear at the top of the reviews, listed as <strong>'New Reviews'</strong> below.</br>
<strong><big> &nbsp;&#8226;&nbsp; Save New Review Order - </big></strong><strong>Review order must be saved before any reviews will be displayed on site!</strong> The retrieved reviews can be reordered to display in any order by drag and drop.  In addition, reviews can be selected to not display. These reviews will 
not be deleted, but can be re-activated at any time and again added to the list of reviews displayed.</br>
<strong><big> &nbsp;&#8226;&nbsp; Setup Steps - </big></strong></br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>1. </strong> Make sure to enter Facebook Connect Data if fields are empty </br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>2. </strong> Set minimum review rating - 3 would be recommended</br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>3. </strong> Click <strong>'Get New Google Reviews'</strong>, then <strong>'Save Google Reviews'</strong> </br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>4. </strong> Click <strong>'Get New Facebook Reviews'</strong></br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>5. </strong> Rearrange the order of reviews as they should be displayed, and click <strong>'Do Not Show'</strong> for the reviews that should be hidden</br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>6. </strong> Click <strong>'Save New Review Order'</strong>.  Reviews should now be showing up on site in specified order. </br>
&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>7. </strong> In admin sidebar - click <strong>'Appearance'</strong>, then select <strong>'Menus'</strong>, finally select <strong>'Dealer Reviews'</strong> from list of pages and 
<strong>'Add to Menu'</strong> to add page to menu structure 
</p>
<!-- <a id="aHideShow" style="font-weight:bold;font-size:1.3em;text-decoration:underline;" class="btn btn-primary"></a> -->
<button type="button" id="aHideShow" class="myRedButton" style="width:150px;height:35px;"  >Help</button>
</div>

    </table>
    
    <form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table style="width:600px;">
        <tr><td colspan="2" >
        <hr>
            <h2><strong>Facebook Connect Data:</strong></h2>
        </td></tr>
        <tr><td>
            <fb:login-button
                scope='public_profile,email,user_birthday,user_location,manage_pages,user_posts,pages_show_list,ads_management'
                onlogin="getLoginStatus();" id="loginButton" style="display:block">
            </fb:login-button>
        </td><td>
           <a href="#" id='logout' onclick='logout();'>logout</a>
        </td></tr>

        <tr><td>
            PAGE ID : 
        </td><td>
            <strong><input type="text" name="txtPageID" id="txtPageID" style="width:400px;" value="<?php echo $s_pageID; ?>"></strong>
        </td></tr>
        <tr><td>
            APP ID : 
        </td><td>
            <strong><input type="text" name="txtAppID" id="txtAppID" style="width:400px;" value="<?php echo $s_appID; ?>"></strong>
        </td></tr>
        <tr><td>
            APP SECRET : 
        </td><td>
            <strong><input type="text" name="txtAppSecret" id="txtAppSecret" style="width:400px;" value="<?php echo $s_appSecret; ?>"></strong>
        </td></tr>
        <tr><td colspan="2" ></br>
    <input type="submit" value="Update Facebook Connect Data" name="submit"  id="btnUpdateFBConnectData" class="myButton" style="width:600px;height:35px;"  ></br></br>
    
        </td></tr>
        <tr><td colspan="2" >
        <hr>
            <h2><strong>Set Minimum Review Rating To Display:</strong></h2>
        </td></tr>
        <tr><td>
            MINIMUM REVIEW 0-5 : 
        </td><td>
            <strong>
            <select name="selectMinimumReview" id="selectMinimumReview">
                <?php
                if($i_mrn == 0) {?>
                    <option value="0" selected="selected">Show All</option>
                <?php } else { ?>
                    <option value="0" >Show All</option>
                <?php } 
                if($i_mrn == 1) {?>
                    <option value="1" selected="selected">&#x02605;&#x02729;&#x02729;&#x02729;&#x02729;</option>
                <?php } else { ?>
                    <option value="1">&#x02605;&#x02729;&#x02729;&#x02729;&#x02729;</option>
                <?php } 
                if($i_mrn == 2) {?>
                    <option value="2" selected="selected">&#x02605;&#x02605;&#x02729;&#x02729;&#x02729;</option>
                <?php } else { ?>
                    <option value="2">&#x02605;&#x02605;&#x02729;&#x02729;&#x02729;</option>
                <?php } 
                if($i_mrn == 3) {?>
                    <option value="3" selected="selected">&#x02605;&#x02605;&#x02605;&#x02729;&#x02729;</option>
                <?php } else { ?>
                    <option value="3">&#x02605;&#x02605;&#x02605;&#x02729;&#x02729;</option>
                <?php } 
                if($i_mrn == 4) {?>
                    <option value="4" selected="selected">&#x02605;&#x02605;&#x02605;&#x02605;&#x02729;</option>
                <?php } else { ?>
                    <option value="4">&#x02605;&#x02605;&#x02605;&#x02605;&#x02729;</option>
                <?php } 
                if($i_mrn == 5) {?>
                    <option value="5" selected="selected">&#x02605;&#x02605;&#x02605;&#x02605;&#x02605;</option>
                <?php } else { ?>
                    <option value="5">&#x02605;&#x02605;&#x02605;&#x02605;&#x02605;</option>
                <?php } ?>
            </select>
         </td></tr>
         <tr><td colspan="2" >
         </br>
            <input type="submit" value="Update Minimum Review Rating" name="submit"  id="btnUpdateMinimumReview" class="myButton" style="width:600px;height:35px;"  >
        </tr></td>
    </table>
               
    <div class="errorDiv" style="color:red;font-weight:bold;" style="display:none"></div>
</form>
</br>

<hr style="width:80%;" align="left">






<!-- ******************************************** GET NEW REVIEWS ******************************************** -->
<h2><strong>Show Review Types:</strong></h2>

<!-- ******************************************** DISPLAY AVERAGE REVIEWS ? ******************************************** -->
<?php
    if(isset($_POST["GoogleReviewsVisible"])){
        $s_showGoogleReviews = htmlspecialchars($_POST["GoogleReviewsVisible"]);
        update_option('ShowGoogleReviewsOptionValue', $s_showGoogleReviews, false);
    }
    if(isset($_POST["FacebookReviewsVisible"])){
        $s_showFacebookReviews = htmlspecialchars($_POST["FacebookReviewsVisible"]);
        update_option('ShowFacebookReviewsOptionValue', $s_showFacebookReviews, false);
    }


    $s_showFacebookReviews = get_option('ShowFacebookReviewsOptionValue');
    $s_showGoogleReviews = get_option('ShowGoogleReviewsOptionValue');

?>
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td style="font-weight:bold;font-size:1.2em;">
            SHOW GOOGLE REVIEWS: 
        </td><td style="font-weight:bold;font-size:1.2em">
            <?php if($s_showGoogleReviews == 'true') { ?>
                <input type="radio" class="ReviewsVisible" name="GoogleReviewsVisible" value="true" checked="checked"> Visible </input>
                <input type="radio" class="ReviewsVisible" name="GoogleReviewsVisible" value="false"> Do Not Show</input>
            <?php } else { ?>
                <input type="radio" class="ReviewsVisible" name="GoogleReviewsVisible" value="true" > Visible </input>
                <input type="radio" class="ReviewsVisible" name="GoogleReviewsVisible" value="false" checked="checked"> Do Not Show</input>
            <?php } ?>
        </td></tr>
        <tr><td style="font-weight:bold;font-size:1.2em">
            SHOW FACEBOOK REVIEWS: 
        </td><td style="font-weight:bold;font-size:1.2em;vertical-align:bottom;">
            <?php if($s_showFacebookReviews == 'true') { ?>
                <input type="radio" class="ReviewsVisible" name="FacebookReviewsVisible" value="true" checked="checked"> Visible </input>
                <input type="radio" class="ReviewsVisible" name="FacebookReviewsVisible" value="false"> Do Not Show</input>
            <?php } else { ?>
                <input type="radio" class="ReviewsVisible" name="FacebookReviewsVisible" value="true" > Visible </input>
                <input type="radio" class="ReviewsVisible" name="FacebookReviewsVisible" value="false" checked="checked"> Do Not Show</input>
            <?php } ?></br></br>
        </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Update Show Review Types" name="submit"  id="btnUpdateAverageReviewDisplay" class="myButton" style="width:600px;height:35px;"  ></strong>
        </td></tr>
    </table>
</form>
</br>
<hr style="width:80%;" align="left">
</br>







<!-- ******************************************** SHOW / HIDE FACEBOOK / GOOGLE REVIEWS ******************************************** -->
<h2><strong>Show Average Reviews:</strong></h2>

<!-- ******************************************** DISPLAY AVERAGE REVIEWS ? ******************************************** -->
<?php
    if(isset($_POST["GoogleAverageReviewVisible"])){
        $s_showGoogleAverageReview = htmlspecialchars($_POST["GoogleAverageReviewVisible"]);
        update_option('ShowGoogleAverageReviewOptionValue', $s_showGoogleAverageReview, false);
    }
    if(isset($_POST["FacebookAverageReviewVisible"])){
        $s_showFacebookAverageReview = htmlspecialchars($_POST["FacebookAverageReviewVisible"]);
        update_option('ShowFacebookAverageReviewOptionValue', $s_showFacebookAverageReview, false);
    }
    if(isset($_POST["GoogleAverageReviewValue"])){
        $f_googleAverageReview = htmlspecialchars($_POST["GoogleAverageReviewValue"]);
        update_option('GoogleAverageReviewOptionValue', $f_googleAverageReview, false);
    }

?>
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td style="font-weight:bold;font-size:1.2em">
            AVERAGE GOOGLE REVIEW: 
            <span name="GoogleAverageReview" id="spanGoogleAverageReview" style="color:red">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $f_googleAverageReview ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
            
        </td><td style="font-weight:bold;font-size:1.2em">
            <?php if($s_showGoogleAverageReview == 'true') { ?>
                <input type="radio" class="AverageReviewVisible" name="GoogleAverageReviewVisible" value="true" checked="checked"> Visible </input>
                <input type="radio" class="AverageReviewVisible" name="GoogleAverageReviewVisible" value="false"> Do Not Show</input>
            <?php } else { ?>
                <input type="radio" class="AverageReviewVisible" name="GoogleAverageReviewVisible" value="true" > Visible </input>
                <input type="radio" class="AverageReviewVisible" name="GoogleAverageReviewVisible" value="false" checked="checked"> Do Not Show</input>
            <?php } ?>
        </td></tr>
        <?php
            if($f_googleAverageReview == '0'){
                echo '
                <tr><td colspan="2">
                    <input type="submit" value="Get Average Google Review" name="submit"  id="btnUpdateShowReviewtypes" class="myRedButton" style="width:300px;height:30px;"  >
                    <hr style="width:80%;" align="left">
                </td></tr>
                ';
            }
        ?>

        <tr><td style="font-weight:bold;font-size:1.2em">
            AVERAGE FACEBOOK REVIEW: 
            <span name="FacebookAverageReview" id="spanFacebookAverageReview" style="color:darkblue">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $f_facebookAverageReview; ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </td><td style="font-weight:bold;font-size:1.2em;vertical-align:bottom;">
            <?php if($s_showFacebookAverageReview == 'true') { ?>
                <input type="radio" class="AverageReviewVisible" name="FacebookAverageReviewVisible" value="true" checked="checked"> Visible </input>
                <input type="radio" class="AverageReviewVisible" name="FacebookAverageReviewVisible" value="false"> Do Not Show</input>
            <?php } else {  ?>
                <input type="radio" class="AverageReviewVisible" name="FacebookAverageReviewVisible" value="true" > Visible </input>
                <input type="radio" class="AverageReviewVisible" name="FacebookAverageReviewVisible" value="false" checked="checked"> Do Not Show</input>
            <?php } ?>
        </td></tr>
        <?php
            if($f_facebookAverageReview == '0'){
                echo '
                <tr><td colspan="2">
                    <input type="submit" value="Get Average Facebook Review" name="submit"  id="btnUpdateShowReviewtypes2" class="myButton" style="width:300px;height:30px;"  >
                    <hr style="width:80%;" align="left">
                </td></tr>
                ';
            }
        ?>
        <tr><td colspan="2">
            <input type="text" value="<?php echo $f_googleAverageReview; ?>" name="GoogleAverageReviewValue" id="GoogleAverageReviewValue" style="display:none" ></input></br>
            <input type="submit" value="Update Average Review Display" name="submit"  id="btnUpdateShowReviewtypes" class="myButton" style="width:600px;height:35px;"  ></strong>
        </td></tr>
    </table>
</form>
</br>
<hr style="width:80%;" align="left">
</br>



<!-- ******************************************** GET NEW REVIEWS ******************************************** -->

<!-- ******************************************** GET GOOGLE REVIEWS ******************************************** -->
<h2><strong>Get New Reviews:</strong></h2>
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td colspan="2">
            <input type="text" name="refreshGoogleReviews" id="txtRefreshGoogleReviews" style="display:none;" value="yes">
            <textarea class="textAreaGoogleReviews" name="PostedGoogleReviews" id="textAreaGoogleReviews" style="width:100%;height:375px;display:none;"></textarea>
        </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Get New Google Reviews" name="submit"  id="btnRefreshGoogleReviews" class="myRedButton" style="width:600px;height:35px;"  >
            <h2 id="spanGoogleReviewsResults" style="font-weight:bold;display:inline;"></h2>
            <input type="submit" value="Save Google Reviews" name="submit"  id="btnSaveGoogleReviews" class="myRedButton" style="width:300px;height:30px;display:none;"  >
        </td></tr>
       
    </table>
</form>

<!-- ******************************************** GET FACEBOOK REVIEWS ******************************************** -->
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td colspan="2">
            <input type="text" name="refreshReviews" id="txtRefreshReviews" style="display:none;" value="yes">
        </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Get New Facebook Reviews" name="submit"  id="btnRefreshReviews" class="myButton" style="width:600px;height:35px;"  ></strong>
        </td></tr>
    </table>
</form>

<!-- ******************************************** GET DEALER REVIEWS ******************************************** -->
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td colspan="2">
            <input type="text" name="refreshDealerReviews" id="txtRefreshDealerReviews" style="display:none;" value="yes">
        </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Get New Dealer Reviews" name="submit"  id="btnRefreshDealerReviews" class="myGreyButton" style="width:600px;height:35px;"  ></strong>
        </td></tr>
    </table>
</form>
                </br>




</br>



<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td colspan="2">
            <hr style="width:80%;" align="left">
            <h2><strong>Save New Review Order:</strong></h2>
        </td></tr>
        <tr><td colspan="2">
            <textarea class="DROrderByPostID" name="DROrderByPostID" id="DROrderByPostID" style="width:100%;height:175px;display:none;"></textarea>
            <textarea class="DRInactiveByPostID" name="DRInactiveByPostID" id="DRInactiveByPostID" style="width:100%;height:175px;display:none;"></textarea>
            <input type="text" name="saveReviewOrder" id="txtSaveReviewOrder" style="display:none;" value="yes">
            <input type="submit" value="Save New Review Order" name="submit"  id="btnSaveReviewOrder" class="myButton" style="width:600px;height:35px;"  ></strong>
        </td></tr>
    </table>
</form>
                </br>
<hr  style="width:80%;" align="left">

<div id="divShowReviews">
    <?php 
        $s_googleReviews = '';
        if(strlen(htmlspecialchars($_POST["PostedGoogleReviews"])) > 0){
            $s_googleReviews = htmlspecialchars($_POST["PostedGoogleReviews"]);
        }
        dld_dealer_reviews_show_all_from_db_sortable($s_googleReviews);
    ?>
</div>

<?php


$s_postIdsActiveReviews = htmlspecialchars($_POST["DROrderByPostID"]);
$s_postIdsInactiveReviews = htmlspecialchars($_POST["DRInactiveByPostID"]);



$b_updatedValue = false;
$s_newPageID = htmlspecialchars($_POST["txtPageID"]);
$s_newAppID = htmlspecialchars($_POST["txtAppID"]);
$s_newAppSecret = htmlspecialchars($_POST["txtAppSecret"]);
$s_newMinReview = htmlspecialchars($_POST["selectMinimumReview"]);
if(strlen($s_newPageID) > 0 && get_option('FacebookPageIDOptionValue') != $s_newPageID){
    update_option('FacebookPageIDOptionValue', $s_newPageID, false);
    $b_updatedValue = true;
}
if(strlen($s_newAppID) > 0 && get_option('FacebookAppIDOptionValue') != $s_newAppID){
    update_option('FacebookAppIDOptionValue', $s_newAppID, false);
    $b_updatedValue = true;
}
if(strlen($s_newAppSecret) > 0 && get_option('FacebookAppSecretOptionValue') != $s_newAppSecret){
    update_option('FacebookAppSecretOptionValue', $s_newAppSecret, false);
    $b_updatedValue = true;
}
if(strlen($s_newMinReview) > 0 && get_option('DealerReviewMinimumRatingOptionValue') != $s_newMinReview){
    update_option('DealerReviewMinimumRatingOptionValue', $s_newMinReview, false);
    $b_updatedValue = true;
}
if($b_updatedValue){
    $b_updatedValue = false;
    echo "
    <script>
        var x = false;
        location.reload();</script>";
}


// ************************************************ PREVENT INFINITE POSTBACK LOOP ************************************************
$s_RefreshReviews = htmlspecialchars($_POST["refreshReviews"]);
if ($s_RefreshReviews == 'yes'){

    echo "
    <script>
        document.getElementById('divShowReviews').style.display = 'none'; 
    </script>";
        refresh_dld_fb_reviews($s_pageID, $s_appID, $s_appSecret, $s_llAccessToken, $i_minimum_review_num );
        echo "
        <script>
            document.getElementById('txtRefreshReviews').value = 'no';
            // document.getElementById('btnRefreshReviews').click();
            var x = false;
        </script>";
        
    
    
} else {
    echo "
    <script>
        document.getElementById('txtRefreshReviews').value = 'yes';
    </script>";
}


$s_RefreshGoogleReviews = htmlspecialchars($_POST["refreshGoogleReviews"]);
if ($s_RefreshGoogleReviews == 'yes'){

    echo "
    <script>
        document.getElementById('divShowReviews').style.display = 'none'; 
    </script>";
        refresh_dld_google_reviews($i_minimum_review_num );
        echo "
        <script>
            document.getElementById('txtRefreshGoogleReviews').value = 'no';
        // document.getElementById('btnRefreshGoogleReviews').click();
            var x = false;
        </script>";
        
    
    
} else {
    echo "
    <script>
        document.getElementById('txtRefreshGoogleReviews').value = 'yes';
    </script>";
}


$s_saveReviewOrder = htmlspecialchars($_POST["saveReviewOrder"]);
if ($s_saveReviewOrder == 'yes'){

    dld_save_new_review_order($s_postIdsActiveReviews, $s_postIdsInactiveReviews);
        echo "
        <script>
            document.getElementById('txtSaveReviewOrder').value = 'no';
         document.getElementById('btnSaveReviewOrder').click();
            var x = false;
        </script>";
        
    
    
} else {
    echo "
    <script>
        document.getElementById('txtSaveReviewOrder').value = 'yes';
    </script>";
}

}


$lat = '32.3669454';
$long = '-86.2096623';
$placesID = 'ChIJd3QeN3YpjIgR9-EZmWtzRIQ';
?>





<section>
  <div id="map" style="display:hidden"></div>
  <div id="reviews" style="display:hidden"></div>
</section>

</div>

<script>
// iniMap();
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
            var rating = document.getElementById('GoogleAverageReviewValue');
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
        rating.value = rate_avg;
        });
      }
    </script>
<script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDULu7XpqWlJZxNl9ZWcL5aQmt6Ra9OzjM&libraries=places&callback=initMap"></script>