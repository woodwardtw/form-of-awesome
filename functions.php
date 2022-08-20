<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/block-editor.php',                    // Load Block Editor functions.
	'/deprecated.php',                      // Load deprecated functions.
);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$understrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $understrap_includes as $file ) {
	require_once get_theme_file_path( $understrap_inc_dir . $file );
}


//add feedback form to all things

// function awesome_feedback_form ( $content ) {
 
//     return $content . do_shortcode('[gravityform id="5" title="false" description="false" ajax="true"]');
// }
// add_filter( 'the_content', 'awesome_feedback_form');



//simple GF merge field modifier that replaces the space with a dash
add_filter( 'gform_merge_tag_filter', function ( $value, $merge_tag, $modifier, $field, $raw_value, $format ) {
    if ( $merge_tag != 'all_fields' && $modifier == 'urlmaker' ) {
        $value = str_replace(" ", "-", $value);
    }
 
    return $value;
}, 10, 6 );


//more complex GF merge field modifier that deals with mulitple entries
add_filter( 'gform_merge_tag_filter', 'bava_modifier', 10, 6 );
function bava_modifier ( $value, $merge_tag, $modifier, $field, $raw_value, $format ) {
    $html = '';
    if ( $merge_tag != 'all_fields' && $modifier == 'bavait' ) {
        $array_it = explode(', ',$value);
        foreach ($array_it as $item) {
        	$hyphen = str_replace(" ", "-", $item);
        	$url = site_url();
    		$html .= "<a class='bava-link' href='{$url}/tag/{$hyphen}'>{$item}</a><br>";
		}
        $value = $html;
    }
 	
    return $value;
}


//as shortcode that doesn't care about gf at all, it just shows the post's tags
function bava_tags(){
  $post_tags = get_the_tags();
    $html = '';
 
    if ( ! empty( $post_tags ) ) {
        foreach ( $post_tags as $tag ) {
            $html .= '<a href="' . esc_attr( get_tag_link( $tag->term_id ) ) . '">' . __( $tag->name ) . '</a><br>';
        }
    }
 
    return trim( $html );
}
add_shortcode('bava-tags', 'bava_tags');




//fancier submission of discord submissions
//add_filter( 'gform_webhooks_request_args', 'clean_hook_data', 10, 4 );



//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}