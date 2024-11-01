<?php
/*
Plugin Name: TextToMe
Plugin URI:  http://themes.tradesouthwest.com/plugins/texttome/
Description: Text Messaging to a phone from your WordPress Admin panel is as easy as pie using TextToMe plugin by Tradesouthwest. Covers the top ten mobile carriers in the US. Enter cell phone number then select carrier and write the message. Does not get much easier than that.
Version:     0.1.0
Author:      Larry Judd Oliver - Tradesouthwest
Author URI:  https://tradesouthwest.com
Text Domain: texttome
*/



/**
 * Prevent direct access to the file.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



define( 'TTM_VERSION', '0.1.0' );



//activate/deactivate hooks
function texttome_plugin_activation() {
return false;
}
register_activation_hook(__FILE__, 'texttome_plugin_activation');

function texttome_plugin_deactivation() {
return false;
}
register_deactivation_hook(__FILE__, 'texttome_plugin_deactivation');



/**
 * Include loadable plugin files
 */
// Initialise - load in translations
function texttome_loadtranslations () {
	$plugin_dir = basename(dirname(__FILE__)).'/languages';
	load_plugin_textdomain( 'texttome', false, $plugin_dir );
}
add_action('plugins_loaded', 'texttome_loadtranslations');



/**
 * Plugin Scripts
 *
 * Register and Enqueues plugin scripts
 *
 * @since 0.0.1
 */
function texttome_scripts() {

	// Register Scripts
	wp_register_script( 'texttome_script', plugins_url(
                        'js/texttome.js', __FILE__ ), array( 'jquery' ), true );
	// Register Styles
	wp_register_style( 'texttome_style', plugins_url(
                        'css/texttome.css', __FILE__ ) );

	// Enqueue Styles
	wp_enqueue_style( 'texttome_style' );
	// Enqueue Scripts
	wp_enqueue_script( 'texttome_script' );

}
add_action( 'admin_enqueue_scripts', 'texttome_scripts' );

if ( is_admin() ) {
include_once ( plugin_dir_path( __FILE__ ) . 'inc/admin.php' );
}
?>