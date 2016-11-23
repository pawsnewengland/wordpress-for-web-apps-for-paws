<?php

/**
 * Plugin Name: WordPress for Web Apps for PAWS
 * Plugin URI: https://github.com/pawsnewengland/wordpress-for-web-apps-for-paws/
 * GitHub Plugin URI: https://github.com/pawsnewengland/wordpress-for-web-apps-for-paws/
 * Description: Extends the <a href="https://github.com/cferdinandi/gmt-wordpress-for-web-apps">WordPress for Web Apps plugin</a>.
 * Version: 1.0.3
 * Author: Chris Ferdinandi
 * Author URI: http://gomakethings.com
 * License: GPLv3
 */


// Get the plugin options
require_once( plugin_dir_path( __FILE__ ) . 'options.php' );



function wpwa_for_paws_restrict_cpt_access() {

	// Validate that WP for Web Apps is installed
	if ( !function_exists( 'wpwebapp_get_theme_options' ) ) return;

	// Check if user is logged in already
	if ( is_user_logged_in() ) return;

	// Variables
	global $post;
	$wpwa_options = wpwebapp_get_theme_options();
	$options = wpwa_for_paws_get_theme_options();
	$post_type = get_post_type( $post->id );

	if ( empty( $post_type ) )

	// If user is logged out and content is restricted, redirect
	if ( empty( $post_type ) ) {
		$post_types = array();
		foreach( $options['post_types'] as $key => $value ) {
			$post_types[] = $key;
		}
		if ( is_post_type_archive( $post_types ) ) {
			wp_safe_redirect( wpwebapp_get_redirect_url( $wpwa_options['login_redirect'] ), 302 );
			exit;
		}
	} else {
		if ( array_key_exists( $post_type, $options['post_types'] ) && $options['post_types'][$post_type] === 'on' ) {
			wp_safe_redirect( wpwebapp_get_redirect_url( $wpwa_options['login_redirect'] ), 302 );
			exit;
		}
	}

}
add_action( 'wp', 'wpwa_for_paws_restrict_cpt_access' );



/**
 * Display notice if WordPress for Web Apps plugin is not installed
 */
function wpwa_for_paws_admin_notice() {

	if ( function_exists( 'wpwebapp_get_theme_options' ) ) return;

	?>

		<div class="notice notice-error"><p><?php printf( __( 'WordPress for Web Apps for PAWS will not work without the %sGMT WordPress for Web Apps plugin%s. Please install it now.', 'gmt_donations' ), '<a href="https://github.com/cferdinandi/gmt-wordpress-for-web-apps">', '</a>' ); ?></p></div>

	<?php
}
add_action( 'admin_notices', 'wpwa_for_paws_admin_notice' );