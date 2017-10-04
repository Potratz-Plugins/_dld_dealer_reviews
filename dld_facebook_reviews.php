<?php
/**
 * Plugin Name: DLD Facebook Reviews
 * Description: Shows facebook reviews on page
 * Version: 1.0
 * Author: Tom Molinaro
 *
 */


// CREATE ADMIN PAGE - main page, used for postback
function dld_facebook_reviews_admin_menu() {

        add_menu_page (
            'Facebook Reviews Plugin Page',					// string $page_title
            'Facebook Reviews Plugin',					// string $menu_title
            'read',							// string $capability
            'dld_manage_facebook_reviews',		// string $menu_slug
            'dld_facebook_reviews_init',		// callback $function
            'dashicons-admin-page',			// string $icon_url
            '94'							// int $position
        );

        add_menu_page (
            'Facebook Reviews Plugin Page2',					// string $page_title
            null ,					// string $menu_title
            'read',							// string $capability
            'dld_manage_facebook_reviews2',		// string $menu_slug
            'dld_facebook_handle_postbacks'	// callback $function
        );

} add_action( 'admin_menu', 'dld_facebook_reviews_admin_menu' );



// TODO: ACTIVATION script
function dld_facebook_reviews_activate(){
    $page = get_page_by_title('Dealer Reviews');
    if( empty( $page )){
        dld_facebook_reviews_add_template_page();
    }
}
register_activation_hook( __FILE__, 'dld_facebook_reviews_activate' );


// TODO: DEACTIVATION script
function dld_facebook_reviews_deactivate(){
    
}
register_deactivation_hook( __FILE__, 'dld_facebook_reviews_deactivate' );



function dld_facebook_reviews_admin_enqueue_scripts() {

    wp_register_style( 'prefix-style', plugins_url('/styles/styles.css', __FILE__) );
    wp_enqueue_style( 'prefix-style', plugins_url('/styles/styles.css', __FILE__) );

    wp_register_style( 'prefix-style', plugins_url('/styles/jquery-ui.css', __FILE__) );
    wp_enqueue_style( 'prefix-style', plugins_url('/styles/jquery-ui.css', __FILE__) );

    wp_register_script('load_js', plugins_url('/scripts/jquery-1.12.4.min.js', __FILE__));
    wp_enqueue_script('load_js', plugins_url('/scripts/jquery-1.12.4.min.js', __FILE__));

    // SORTABLE SCRIPT - NOT NEEDED IN THIS FORMAT - see next uncommented line - 'jquery-ui-sortable'
    // wp_register_script('load_js', plugins_url('/scripts/jquery-ui.js', __FILE__));
    // wp_enqueue_script('load_js', plugins_url('/scripts/jquery-ui.js', __FILE__));

    wp_enqueue_script( 'jquery-ui-sortable' );

    wp_register_script( 'prefix-style', plugins_url('/scripts/fb_api.js', __FILE__) );
    wp_enqueue_script( 'prefix-style', plugins_url('/scripts/fb_api.js', __FILE__) );
   
    wp_register_script( 'custom-js', plugins_url('/scripts/scripts.js', __FILE__));
    wp_enqueue_script( 'custom-js', plugins_url('/scripts/scripts.js', __FILE__));

  
} add_action( 'admin_enqueue_scripts', 'dld_facebook_reviews_admin_enqueue_scripts' );



// ADD connection to page template
function dld_facebook_reviews_template( $page_template ){
    if ( is_page( 'dealer-reviews' ) ) {
        $page_template = dirname( __FILE__ ) . '/templates/template-reviews.php';
    }
    return $page_template;
 }
 add_filter('page_template', 'dld_facebook_reviews_template' );


// INSERT PAGE for plugin
function dld_facebook_reviews_add_template_page(){
    
      $post = array(
        'post_title'     => "Dealer Reviews", // The title of your post.
        'post_type'      => "page",
        'post_status'    => "publish"
        );
    
      wp_insert_post( $post, $wp_error );
    
    }

// FUNCTION - sets up main admin page
function dld_facebook_reviews_init(){

// TODO: does admin/... need to be after the other 3?
include_once 'admin/dld_facebook_reviews_admin.php';
include_once 'src/Facebook/autoload.php';
include_once 'include/DealerReviews.class.php';
include_once 'include/functions.php';

dld_setup_facebook_reviews_admin_page();
}





// TODO: CREATE PAGE TEMPLATE AT STARTUP
// // ADD PAGE TEMPlATE
// class PageTemplater {

//          // A Unique Identifier
// 		 protected $plugin_slug;
    
//         // A reference to an instance of this class.
//         private static $instance;
        
//         // The array of templates that this plugin tracks.
//         protected $templates;
        

//         // Returns an instance of this class. 
//         public static function get_instance() {
    
//             if ( null == self::$instance ) {
//                 self::$instance = new PageTemplater();
//             } 
//             return self::$instance;
//         } 
    


//         // Initializes the plugin by setting filters and administration functions.
//         private function __construct() {
    
//             $this->templates = array();
    
    
//             // Add a filter to the attributes metabox to inject template into the cache.
//             if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
    
//                 // 4.6 and older
//                 add_filter(
//                     'page_attributes_dropdown_pages_args',
//                     array( $this, 'register_project_templates' )
//                 );
    
//             } else {
    
//                 // Add a filter to the wp 4.7 version attributes metabox
//                 add_filter(
//                     'theme_page_templates', array( $this, 'add_new_template' )
//                 );
    
//             }
    
//             // Add a filter to the save post to inject out template into the page cache
//             add_filter(
//                 'wp_insert_post_data', 
//                 array( $this, 'register_project_templates' ) 
//             );
    
    
//             // Add a filter to the template include to determine if the page has our 
//             // template assigned and return it's path
//             add_filter(
//                 'template_include', 
//                 array( $this, 'view_project_template') 
//             );
    
    
//             // Add your templates to this array.
//             $this->templates = array(
//                 'goodtobebad-template.php' => 'It\'s Good to Be Bad',
//             );
                
//         } 
    
//         /**
//          * Adds our template to the page dropdown for v4.7+
//          *
//          */
//         public function add_new_template( $posts_templates ) {
//             $posts_templates = array_merge( $posts_templates, $this->templates );
//             return $posts_templates;
//         }
    
//         /**
//          * Adds our template to the pages cache in order to trick WordPress
//          * into thinking the template file exists where it doens't really exist.
//          */
//         public function register_project_templates( $atts ) {
    
//             // Create the key used for the themes cache
//             $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
    
//             // Retrieve the cache list. 
//             // If it doesn't exist, or it's empty prepare an array
//             $templates = wp_get_theme()->get_page_templates();
//             echo "<h2>vardump</h2><pre>";
//             var_dump($templates);
//             echo "</pre>";
//             if ( empty( $templates ) ) {
//                 $templates = array();
//             } 
    
//             // New cache, therefore remove the old one
//             wp_cache_delete( $cache_key , 'themes');
    
//             // Now add our template to the list of templates by merging our templates
//             // with the existing templates array from the cache.
//             $templates = array_merge( $templates, $this->templates );
    
//             // Add the modified cache to allow WordPress to pick it up for listing
//             // available templates
//             wp_cache_add( $cache_key, $templates, 'themes', 1800 );
    
//             return $atts;
    
//         } 
    
//         /**
//          * Checks if the template is assigned to the page
//          */
//         public function view_project_template( $template ) {
            
//             // Get global post
//             global $post;
    
//             // Return template if post is empty
//             if ( ! $post ) {
//                 return $template;
//             }
    
//             // Return default template if we don't have a custom one defined
//             if ( ! isset( $this->templates[get_post_meta( 
//                 $post->ID, '_wp_page_template', true 
//             )] ) ) {
//                 return $template;
//             } 
    
//             $file = plugin_dir_path( __FILE__ ). get_post_meta( 
//                 $post->ID, '_wp_page_template', true
//             );
    
//             // Just to be safe, we check if the file exist first
//             if ( file_exists( $file ) ) {
//                 return $file;
//             } else {
//                 echo $file;
//             }
    
//             // Return template
//             return $template;
    
//         }
    
//     } 
//     add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );
    
?>