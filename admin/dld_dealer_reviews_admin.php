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

    $s_pageID =  get_option('FacebookPageIDOptionValue');
    $s_appID = get_option('FacebookAppIDOptionValue');
    $s_appSecret = get_option('FacebookAppSecretOptionValue');
    $s_llAccessToken = get_option('FacebookLLAccessTokenOptionValue');
    $i_minimum_review_num = intval(get_option('DealerReviewMinimumRatingOptionValue'));
    $i_mrn = $i_minimum_review_num;
    

    ?>
<hr>
<div class="hero-unit">
<h1>DLD Dealer Reviews</h1>
<!-- <a id="aHideShow" style="font-weight:bold;font-size:1.5em;" class="btn btn-primary">SHOW HELP INFO</a> -->

<p class="expandableP">
<strong><big> &nbsp;&#8226;&nbsp; Facebook Connect Data - </big></strong> This will likely not need to be changed.  These are fields used to access Facebook's Api, through which reviews are pulled.</br>
<strong><big> &nbsp;&#8226;&nbsp; Update Minimum Review Rating - </big></strong> This will set a minimal numeric rating for reviews that will be displayed, as well as reviews that will be pulled in for Facebook.  
Google's Api works differently - five reviews at most can be pulled in, and they cannot be limited by a minimum rating.  However, setting the minimum review limit will still limit which Google reviews will display on the site.</br>
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
<h2><strong>Get New Reviews:</strong></h2>

<!-- ******************************************** GET GOOGLE REVIEWS ******************************************** -->
<form action="admin.php?page=dld_manage_dealer_reviews" method="post" enctype="multipart/form-data">
    <table>
        <tr><td colspan="2">
            <input type="text" name="refreshGoogleReviews" id="txtRefreshGoogleReviews" style="display:none;" value="yes">
            <textarea class="textAreaGoogleReviews" name="PostedGoogleReviews" id="textAreaGoogleReviews" style="width:100%;height:375px;display:none;"></textarea>
        </td></tr>
        <tr><td colspan="2">
            <input type="submit" value="Get New Google Reviews" name="submit"  id="btnRefreshGoogleReviews" class="myRedButton" style="width:600px;height:35px;"  >
            <h2 id="spanGoogleReviewsResults" style="font-weight:bold;display:inline;"></h2>
            <input type="submit" value="Save Google Reviews" name="submit"  id="btnSaveGoogleReviews" class="myRedButton" style="width:50%;height:35px;display:none;"  >
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
            document.getElementById('btnRefreshReviews').click();
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

?>