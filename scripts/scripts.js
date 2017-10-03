var activeReviewIDs = new Array();

// ****************************************** ONLOAD ******************************************
$(document).ready(function () {
     // alert('me');
    
    

    $(function() {
      $( "#sortable" ).sortable();
      $( "#sortable" ).disableSelection();
    });

    // AN ITEM POSITION HAS BEEN CHANGED
    $( "#sortable" ).sortable({
        update: function( event, ui ) {
            console.clear();
            var ul = $(".ulSortable");
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
    $( ".DealerReviewIsVisible" ).click(function( ) {
        console.log( "VISIBLE? : " + $(this).val() );
        console.log("ID : " + $(this).attr('name'));
        var containsThisID = $(this).attr('name');
        if($(this).val() == 'Hide'){
            
            var thisID = 'not set';

            thisID = containsThisID.replace('showReview-','');
           // alert(thisID);
        
            var ulDetachFrom = $(".ulSortable");
            var ulAppendTo = $(".ulSortableInactive");
            var li = ulDetachFrom.children("#li-"+ thisID);
            li.detach();
            ulAppendTo.append(li);
            logReviewOrder();
        }

        if($(this).val() == 'Show'){
            var thisID = 'not set';
            thisID = containsThisID.replace('showReview-','');
           // alert(thisID);
            
            var ulDetachFrom = $(".ulSortableInactive");
            var ulAppendTo = $(".ulSortable");
            var li = ulDetachFrom.children("#li-"+ thisID);
            li.detach();
            ulAppendTo.append(li);
            logReviewOrder();
        }


    });
    
    // ONCLICK radio button Visible / Do Not Show is clicked
    $( "#btnShowAllReviews" ).click(function( ) {
        alert('hi');
        $( "#DROrderByPostID" ).val('');
        submit();
       // console.log('ALL IDs : ');
       // console.log(activeReviewIDs);
    });
    

    
    
});


function logReviewOrder(){
    // var allReviews = document.getElementsByName("fname");
    // alert('sorted');

    // reset text areas for active and inactive ids
     $( "#DROrderByPostID" ).val('');
     $( "#DRInactiveByPostID" ).val('');
    // create arrays to hold active/inactive ids
    activeReviewIDs = [];
    inactiveReviewIDs = [];
    
   

    $( ".liSortable" ).each(function( index ) {
        var thisPostID = $(this).find(".DealerReviewPostId").val();
        var parentClass = $(this).parent().attr('class');
        
        // IF the review is not inactive, push to activeReviewIDs array, add id to text area for active ids, plus comma
        if(parentClass != 'ulSortableInactive'){
            activeReviewIDs.push(thisPostID);
             $( "#DROrderByPostID" ).val($("#DROrderByPostID").val()  + thisPostID + ',');
        }
        // ELSE review is inactive, push to inactiveReviewIDs array, add to inactive reviews text area
        else {
            inactiveReviewIDs.push(thisPostID);
            $( "#DRInactiveByPostID" ).val($("#DRInactiveByPostID").val()  + thisPostID + ',');
        }
        
        
        //console.log( index + ": " + $(this).find(".DealerReviewPostId").val() );
        // console.log( index + ": " + $(this).find(".DealerReviewIsVisible").val() );
        // console.log( index + ": " + $(this).find(".DealerReviewImage").val() );
        // console.log( index + ": " + $(this).find(".DealerReviewName").val() );
        // console.log( index + ": " + $(this).find(".DealerReviewRating").val() );
        // console.log( index + ": " + $(this).find(".DealerReviewText").text() );

        // console.log('Parents class : ' + $(this).parent().attr('class'));
        
      });
      
      var textAreaValue = $( "#DROrderByPostID" ).val();
      //REMOVE TRAILING COMMA
      if(textAreaValue.length > 0){
         var textAreaValueTrimLastComma = textAreaValue.substring(0,textAreaValue.length - 1);
          $("#DROrderByPostID").val(textAreaValueTrimLastComma);
      }

      var textAreaValueInactive = $( "#DRInactiveByPostID" ).val();
      //REMOVE TRAILING COMMA
      if(textAreaValueInactive.length > 0){
         var textAreaInactiveValueTrimLastComma = textAreaValueInactive.substring(0,textAreaValueInactive.length - 1);
          $("#DRInactiveByPostID").val(textAreaInactiveValueTrimLastComma);
      }
      
    //   console.log('ALL IDs : ');
    //   console.log(activeReviewIDs);
}



// ****************************************** FUNCTIONS ******************************************
function getLoginStatus(){
   FB.getLoginStatus(function (response) {
       if (response.authResponse) {
           var s_accessToken = response.authResponse.accessToken;
         // $('#divTest').val(response.authResponse.accessToken);
           var redirectLocation = window.location+"?token="+s_accessToken;
            window.location = redirectLocation;
       } else {
      // $('#divTest').val('no token');
       }
   });
}


function logout(){
   FB.logout(function(response){
       document.getElementById('status').innerHTML = '</br>logged out';
      // $('#divTest').val('no token');
   });
}




   