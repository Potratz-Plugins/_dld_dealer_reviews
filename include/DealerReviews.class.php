<?php

/**
 *
 * @author Tom Molinaro
 *
 */
class DealerReviews {
    
    public $reviewerName = '';
    public $imageUrl = '';
    public $numericRating = 0;
    public $reviewText = '';
    public $reviewType = '';
    public $authorId = '';
    public $id = 0;

    public function __construct($s_reviewer_name = '', $s_image_url = '', $i_numeric_rating = 5,  $s_review_text = '', $s_review_type = 'facebook', $s_author_id = '', $i_postID = null){

        $this->reviewerName = $s_reviewer_name;
        $this->imageUrl = $s_image_url;
        $this->numericRating = $i_numeric_rating;
        $this->reviewText = $s_review_text;
        $this->reviewType = $s_review_type;
        $this->authorId = $s_author_id;
        $this->id = $i_postID;
        }

    public function __destruct(){}

    

    protected function FBReviewSaveToDB(){

    }


 // **************************************** ALL FUNCTIONS ****************************************
 // THIS FUNCTION for displaying in admin as sortable list
public function show_dealer_review_sortable($b_is_active = false) {  

                // GET object fields
                $s_reviewer_name = $this->reviewerName;
                $s_image_url = $this->imageUrl;
                $i_numeric_rating = $this->numericRating;
                $s_review_text = $this->reviewText;
                $s_review_type = $this->reviewType;
                // $s_author_id = $this->authorId;
                $i_postID = $this->id;

                $s_reviewer_image_display = '<img src="'.$s_image_url.'" class="photoDisplay"/>'; 
                $s_path_to_icons = plugins_url( '_dld_dealer_reviews/src/images/' );
                $s_full_path_to_icon = $s_path_to_icons.$s_review_type;
                $s_review_type_icon = '<img src="'.$s_full_path_to_icon.'.png" style="height:35px;width:35px;"/>'; 
                
                echo '<li class="liSortable" id="li-'.$i_postID.'">';

            
                echo '<div id="post-'.$i_postID.'" class="ReviewContainer">
                <table style="width:100%;">
                    <tr><td style="width:20%;border:1px;">';
                
                if($b_is_active){
                    echo '
                    <table><tr><td>'.$s_review_type_icon.'</td><td>
                    <form>
                        <input type="radio" class="DealerReviewIsVisible" name="showReview-'.$i_postID.'" value="Show" checked="checked"> Visible </input></br>
                        <input type="radio" class="DealerReviewIsVisible" name="showReview-'.$i_postID.'" value="Hide"> Do Not Show</input>
                    </form>
                    </td></tr></table>
                    ';
                    
                } else {
                    echo '
                    <table><tr><td>'.$s_review_type_icon.'</td><td>
                    <form>
                        <input type="radio" class="DealerReviewIsVisible" name="showReview-'.$i_postID.'" value="Show" > Visible </input></br>
                        <input type="radio" class="DealerReviewIsVisible" name="showReview-'.$i_postID.'" value="Hide" checked="checked"> Do Not Show</input>
                    </form>
                    </td></tr></table>
                ';
                }
                        echo '
                        '.$s_reviewer_image_display.'
                    </td><td>';
                        echo '
                        
                        <strong>'.$s_reviewer_name.'</strong></br>';
                        $this->reviews_get_stars($i_numeric_rating);
                        echo '</br>
                        '.$s_review_text.'</br>
                        POST ID : '.$i_postID.'
                        <input type="text" class="DealerReviewPostId" name="postId-'.$i_postID.'" id="'.$i_postID.'" value="'.$i_postID.'" style="display:none;"></input>
                        <input type="text" class="DealerReviewImage" name="imageUrl-'.$i_postID.'" id="imageUrl-'.$i_postID.'" value="'.$s_image_url.'" style="display:none;"></input>
                        <input type="text" class="DealerReviewName" name="name-'.$i_postID.'" id="name-'.$i_postID.'" value="'.$s_reviewer_name.'" style="display:none;"></input>
                        <input type="text" class="DealerReviewRating" name="rating-'.$i_postID.'" id="rating-'.$i_postID.'" value="'.$i_numeric_rating.'" style="display:none;"></input>
                        <textarea class="DealerReviewText" name="text-'.$i_postID.'" id="text-'.$i_postID.'"style="display:none;">'.$s_review_text.'</textarea>
                    </td></tr>
                    </table>
                    </div>
                        ';
                echo '</li>';
                        
    }
 // THIS FUNCTION for displaying in admin as sortable list
// public function show_dealer_review_sortable1() {  

//             $i_postID = $this->id;
//             $b_is_active = $this->active;
//             $s_image_url = $this->imageUrl;
//             $s_reviewer_image_display = '<img src="'.$s_image_url.'" class="photoDisplay"/>'; 
//             $s_reviewer_name = $this->reviewerName;
//             $i_numeric_rating = $this->numericRating;
//             $s_review_text = $this->reviewText;
        
//             echo '
//             <table style="width:100%">
//                 <tr><td style="width:20%">';
            
//             if(true){
//                 echo '
//                     <input type="radio" name="showReview'.$i_postID.'" value="Show" checked="checked"> Visible </input>
//                     <input type="radio" name="showReview'.$i_postID.'" value="Hide"> Do Not Show</br>
//                 ';
//             } else {
//                 echo '
//                     <input type="radio" name="showReview'.$i_postID.'" value="Show" > Visible </input>
//                     <input type="radio" name="showReview'.$i_postID.'" value="Hide" checked="checked"> Do Not Show</br>
//                 ';
//             }
//                     echo '
//                     </br>'.$s_reviewer_image_display.'
//                 </td><td>';
//                     echo '
//                     <strong>'.$s_reviewer_name.'</strong></br></br>';
//                     $this->reviews_get_stars($i_numeric_rating);
//                     echo '
//                     </br></br>'.$s_review_text.'</br></br>
//                     POST ID : &nbsp;&nbsp;&nbsp;&nbsp;
//                     <span style="font-size:16px">'.$i_postID.'</span>
//                 </td></tr>
//             </table>
//                     ';
// }

// THIS FUNCTION for displaying in site
public function show_dealer_review() {  
    
                $i_postID = $this->id;
                //$b_is_active = $this->active;
                $s_image_url = $this->imageUrl;
                $s_reviewer_image_display = '<img src="'.$s_image_url.'" class="photoDisplay"/>'; 
                $s_reviewer_name = $this->reviewerName;
                $i_numeric_rating = $this->numericRating;
                $s_review_text = $this->reviewText;
        
        
                echo '
                <div class="fb_reviews" style="width:600px;padding:15px;">
                    <table style="width:100%">
                        <tr><td style="padding:10px;width:25%;">';
    
                    
    
                if(true){
                    echo '
                            <input type="radio" name="showReview'.$i_postID.'" value="Show" checked="checked"> Visible </input>
                        </td><td>
                            <input type="radio" name="showReview'.$i_postID.'" value="Hide"> Do Not Show
                        </td></tr>';
                } else {
                    echo '
                            <input type="radio" name="showReview'.$i_postID.'" value="Show" > Visible </input>
                        </td><td>
                            <input type="radio" name="showReview'.$i_postID.'" value="Hide" checked="checked"> Do Not Show
                        </td></tr>';
                }
                echo    '
                        <tr><td style="width:25%">';
                            echo $s_reviewer_image_display.'
                        </td>
                        <td style="text-align:left">';
                            echo '<strong>'.$s_reviewer_name.'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</br>';
                            $this->reviews_get_stars($i_numeric_rating);
                            echo '
                        </td></tr>
                        <tr><td colspan="2" style="padding:10px">
                            <span style="font-size:16px">'.$s_review_text.'</span>
                        </td></tr>
                        <tr><td style="padding:10px;font-size:16px">
                            POST ID : 
                        </td><td>
                            <span style="font-size:16px">'.$i_postID.'</span>
                        </td></tr>
                    </table>
                </div>';
               
    }

protected function reviews_get_stars($rating) {
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

   public function dld_dealer_reviews_save_to_db(){ 

    // echo '<h1>TRYING TO SAVE</h1>';
        //pre_var_dump($this, 'THIS REVIEW');
        // $i_postID = $this->id;
      //  $b_is_active = $this->active;
        $s_image_url = $this->imageUrl;
        $s_author_id = $this->authorId;
        // $s_reviewer_image = '<img src="'.$s_image_url.'" class="photoDisplay"/>'; 
        $s_reviewer_name = $this->reviewerName;
        $i_numeric_rating = $this->numericRating;
        $s_review_text = $this->reviewText;
        $s_review_type = $this->reviewType;

        $a_CreateRating = array(
                'ID'           	=> '',
                'post_mime_type'=> $s_author_id,
                'post_status' 	=> $s_review_type,
                'post_title'	=> $s_image_url,
                'post_excerpt'   => $s_reviewer_name,
                'comment_status'=> $i_numeric_rating,
                'post_content'	=> $s_review_text,
                'post_type'		=> 'dealerreview'
            );
        $s_NewlyInsertedPostID = wp_insert_post( $a_CreateRating );
    
        if ( $s_NewlyInsertedPostID != false && $s_NewlyInsertedPostID > 0 ) {
            // echo '<h1>saving to db</h1>';
            return $s_NewlyInsertedPostID;
        } else {
            echo '<h1>error saving to db</h1>';
            return false;
        }
    }

  

}
