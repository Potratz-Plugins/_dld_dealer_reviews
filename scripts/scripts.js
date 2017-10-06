var activeReviewIDs = new Array();

// ****************************************** ONLOAD ******************************************
jQuery(document).ready(function () {
     // alert('me');
    
    

    jQuery(function() {
      jQuery( "#sortable" ).sortable();
      jQuery( "#sortable" ).disableSelection();
    });

    // AN ITEM POSITION HAS BEEN CHANGED
    jQuery( "#sortable" ).sortable({
        update: function( event, ui ) {
            console.clear();
            var ul = jQuery(".ulSortable");
            var li = ul.children(".liSortable");
            // li.detach().sort();
            li.detach();
            ul.append(li);
            logReviewOrder();
        }
      });

   
    FB.init({ 
       appId: '251021862073231', 
       cookie: true, 
       xfbml: true, 
       status: true 
    });
    
   logReviewOrder();
   
    // ONCLICK radio button Visible / Do Not Show is clicked
    jQuery( ".DealerReviewIsVisible" ).click(function( ) {
        console.log( "VISIBLE? : " + jQuery(this).val() );
        console.log("ID : " + jQuery(this).attr('name'));
        var containsThisID = jQuery(this).attr('name');
        if(jQuery(this).val() == 'Hide'){
            
            var thisID = 'not set';

            thisID = containsThisID.replace('showReview-','');
           // alert(thisID);
        
            var ulDetachFrom = jQuery(".ulSortable");
            var ulAppendTo = jQuery(".ulSortableInactive");
            var li = ulDetachFrom.children("#li-"+ thisID);
            li.detach();
            ulAppendTo.append(li);
            logReviewOrder();
        }

        if(jQuery(this).val() == 'Show'){
            var thisID = 'not set';
            thisID = containsThisID.replace('showReview-','');
           // alert(thisID);
            
            var ulDetachFrom = jQuery(".ulSortableInactive");
            var ulAppendTo = jQuery(".ulSortable");
            var li = ulDetachFrom.children("#li-"+ thisID);
            li.detach();
            ulAppendTo.append(li);
            logReviewOrder();
        }


    });
    
    // ONCLICK radio button Visible / Do Not Show is clicked
    jQuery( "#btnShowAllReviews" ).click(function( ) {
        alert('hi');
        jQuery( "#DROrderByPostID" ).val('');
        submit();
       // console.log('ALL IDs : ');
       // console.log(activeReviewIDs);
    });
    

    

    jQuery('#aHideShow').click(function() {
        var moreArrow = String.fromCharCode(62);
        var lessArrow = String.fromCharCode(60);
        var p = jQuery('#aHideShow').prev('p')
        var lineheight = parseInt(p.css('line-height'))
        if (parseInt(p.css('height')) == lineheight*0) {
           p.css('height','auto');
           jQuery(this).text('Hide Help')
        } else {
           p.css('height',lineheight*0+'px');
           jQuery(this).text('Show Help')
        }
    });
    
});


function logReviewOrder(){
    // var allReviews = document.getElementsByName("fname");
    // alert('sorted');

    // reset text areas for active and inactive ids
     jQuery( "#DROrderByPostID" ).val('');
     jQuery( "#DRInactiveByPostID" ).val('');
    // create arrays to hold active/inactive ids
    activeReviewIDs = [];
    inactiveReviewIDs = [];
    
   

    jQuery( ".liSortable" ).each(function( index ) {
        var thisPostID = jQuery(this).find(".DealerReviewPostId").val();
        var parentClass = jQuery(this).parent().attr('class');
        
        // IF the review is not inactive, push to activeReviewIDs array, add id to text area for active ids, plus comma
        if(parentClass != 'ulSortableInactive'){
            activeReviewIDs.push(thisPostID);
             jQuery( "#DROrderByPostID" ).val(jQuery("#DROrderByPostID").val()  + thisPostID + ',');
        }
        // ELSE review is inactive, push to inactiveReviewIDs array, add to inactive reviews text area
        else {
            inactiveReviewIDs.push(thisPostID);
            jQuery( "#DRInactiveByPostID" ).val(jQuery("#DRInactiveByPostID").val()  + thisPostID + ',');
        }
        
        
        //console.log( index + ": " + jQuery(this).find(".DealerReviewPostId").val() );
        // console.log( index + ": " + jQuery(this).find(".DealerReviewIsVisible").val() );
        // console.log( index + ": " + jQuery(this).find(".DealerReviewImage").val() );
        // console.log( index + ": " + jQuery(this).find(".DealerReviewName").val() );
        // console.log( index + ": " + jQuery(this).find(".DealerReviewRating").val() );
        // console.log( index + ": " + jQuery(this).find(".DealerReviewText").text() );

        // console.log('Parents class : ' + jQuery(this).parent().attr('class'));
        
      });
      
      var textAreaValue = jQuery( "#DROrderByPostID" ).val();
      //REMOVE TRAILING COMMA
      if(textAreaValue.length > 0){
         var textAreaValueTrimLastComma = textAreaValue.substring(0,textAreaValue.length - 1);
          jQuery("#DROrderByPostID").val(textAreaValueTrimLastComma);
      }

      var textAreaValueInactive = jQuery( "#DRInactiveByPostID" ).val();
      //REMOVE TRAILING COMMA
      if(textAreaValueInactive.length > 0){
         var textAreaInactiveValueTrimLastComma = textAreaValueInactive.substring(0,textAreaValueInactive.length - 1);
          jQuery("#DRInactiveByPostID").val(textAreaInactiveValueTrimLastComma);
      }
      
    //   console.log('ALL IDs : ');
    //   console.log(activeReviewIDs);
}



// ****************************************** FUNCTIONS ******************************************
function getLoginStatus(){
   FB.getLoginStatus(function (response) {
       if (response.authResponse) {
           var s_accessToken = response.authResponse.accessToken;
         // jQuery('#divTest').val(response.authResponse.accessToken);
           var redirectLocation = window.location+"?token="+s_accessToken;
            window.location = redirectLocation;
       } else {
      // jQuery('#divTest').val('no token');
       }
   });
}


function logout(){
   FB.logout(function(response){
       document.getElementById('status').innerHTML = '</br>logged out';
      // jQuery('#divTest').val('no token');
   });
}




   
