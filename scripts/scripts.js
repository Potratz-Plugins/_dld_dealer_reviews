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
    $( "#btnSaveReviewOrderTest" ).click(function( ) {
        alert('hi');
        console.log('ALL IDs : ');
        console.log(activeReviewIDs);
    });
    
    
});


function logReviewOrder(){
    // var allReviews = document.getElementsByName("fname");
    // alert('sorted');
     $( "#DROrderByPostID" ).val('');
    activeReviewIDs = [];
    
   

    $( ".liSortable" ).each(function( index ) {
        var thisPostID = $(this).find(".DealerReviewPostId").val();
        var parentClass = $(this).parent().attr('class');
        

        if(parentClass != 'ulSortableInactive'){
            activeReviewIDs.push(thisPostID);
             $( "#DROrderByPostID" ).val($("#DROrderByPostID").val()  + thisPostID + ',');
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




   
