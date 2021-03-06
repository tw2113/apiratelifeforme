<?php
/**
 * Plugin Name: APirateLifeFor.me Shortcodes.
 * Plugin URI: http://apiratelifefor.me
 * Author: Michael Beckwith
 * Version: 2.113
 */

namespace tw2113;

function social_pages( $atts, $content ) {
	return wp_list_pages(
		[
			'title_li' => '',
			'parent'   => get_the_ID(),
			'echo'     => false,
		]
	);
}
add_shortcode( 'social_pages', __NAMESPACE__ . '\social_pages' );
