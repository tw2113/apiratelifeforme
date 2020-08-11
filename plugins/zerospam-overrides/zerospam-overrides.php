<?php
/**
 * Plugin Name: Zerospam Overrides.
 * Plugin URI: http://apiratelifefor.me
 * Author: Michael Beckwith
 * Version: 2.113
 */

namespace tw2113;

function zerospam() {
	wp_dequeue_script( 'wpzerospam' );
	wp_enqueue_script(
		'zerospam-override',
		plugins_url( 'js/zerospam-overrides.js', __FILE__ ),
		[],
		[],
		true
	);
	wp_localize_script( 'zerospam-override', 'zerospam', array( 'key' => wpzerospam_get_key() ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\zerospam', 11 );
