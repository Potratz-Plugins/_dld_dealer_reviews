<?php
/**
 * Plugin Name: DLD Dealer Reviews
 * Plugin URI: https://github.com/Potratz-Plugins/_dld_dealer_reviews/
 * Description: Retrieves, manages and displays facebook and google reviews on 'dealer reviews' page, which is generated on activation
 * Version: 1.0.11
 * Author: Tom Molinaro
 *
 * DB USAGE :
 * Creates and utilizes several option values for facebook login credentials and minimum review rating
 * Creates DealerReview objects which are stored in dld_post table
 *
 *
 */

 require 'plugin_update_check.php';
 $MyUpdateChecker = new PluginUpdateChecker_2_0 (
    'https://kernl.us/api/v1/updates/59de6cbd51ed4f7ddc13822e/',
    __FILE__,
    '_dld_dealer_reviews',
    1
 );
 // $MyUpdateChecker->purchaseCode = "somePurchaseCode";  <---- optional!
 // $MyUpdateChecker->remoteGetTimeout = 5; <--- optional

// CREATE ADMIN PAGE - main page, used for postback
function dld_dealer_reviews_admin_menu() {

        add_menu_page (
            'Dealer Reviews Plugin Page',					// string $page_title
            'Dealer Reviews',					// string $menu_title
            'read',							// string $capability
            '_dld_dealer_reviews',		// string $menu_slug
            'dld_dealer_reviews_init',		// callback $function
            'dashicons-admin-page',			// string $icon_url
            '94'							// int $position
        );
      
} add_action( 'admin_menu', 'dld_dealer_reviews_admin_menu' );



// TODO: ACTIVATION script
function dld_dealer_reviews_activate(){
    $page = get_page_by_title('Dealer Reviews');
    if( empty( $page )){
        dld_dealer_reviews_add_template_page();
    }
}
register_activation_hook( __FILE__, 'dld_dealer_reviews_activate' );


// TODO: DEACTIVATION script
function dld_dealer_reviews_deactivate(){
    
}
register_deactivation_hook( __FILE__, 'dld_dealer_reviews_deactivate' );



function dld_dealer_reviews_admin_enqueue_scripts($s_PageTitle) {

    if (  preg_match( '/_dld_dealer_reviews/i', $s_PageTitle ) ) {
        wp_register_style( 'prefix-style', plugins_url('/styles/styles.css', __FILE__) );
        wp_enqueue_style( 'prefix-style', plugins_url('/styles/styles.css', __FILE__) );
    
        wp_register_style( 'prefix-style', plugins_url('/styles/jquery-ui.css', __FILE__) );
        wp_enqueue_style( 'prefix-style', plugins_url('/styles/jquery-ui.css', __FILE__) );
    
        wp_enqueue_script('jquery');
    
        wp_enqueue_script( 'jquery-ui-sortable' );
    
        wp_register_script( 'prefix-style', plugins_url('/scripts/fb_api.js', __FILE__) );
        wp_enqueue_script( 'prefix-style', plugins_url('/scripts/fb_api.js', __FILE__) );
       
        wp_register_script( 'custom-js', plugins_url('/scripts/scripts.js', __FILE__));
        wp_enqueue_script( 'custom-js', plugins_url('/scripts/scripts.js', __FILE__));
    
	} else {
		return;
	}

   
  
} add_action( 'admin_enqueue_scripts', 'dld_dealer_reviews_admin_enqueue_scripts' );



// ADD connection to page template
function dld_dealer_reviews_template( $page_template ){
    if ( is_page( 'dealer-reviews' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/template-reviews.php';
    }
    return $page_template;
 }
 add_filter('page_template', 'dld_dealer_reviews_template' );


// INSERT PAGE for plugin
function dld_dealer_reviews_add_template_page(){
    
      $post = array(
        'post_title'     => "Dealer Reviews", // The title of your post.
        'post_type'      => "page",
        'post_status'    => "publish"
        );
    
      wp_insert_post( $post, $wp_error );
    
    }

// FUNCTION - sets up main admin page
function dld_dealer_reviews_init(){

include_once 'admin/dld_dealer_reviews_admin.php';
include_once 'src/Facebook/autoload.php';
include_once 'include/DealerReviews.class.php';
include_once 'include/functions.php';

dld_setup_dealer_reviews_admin_page();
}

    
?>