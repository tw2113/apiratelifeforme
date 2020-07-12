<?php

namespace tw2113\pbc;

/**
 * Removes our books post type from what gets considered for the Creative Commons metabox.
 * @param array $supported_types
 * @return mixed
 */
function remove_book_from_creative_commons( $supported_types ) {
	$key = array_search( 'books', $supported_types, true );
	if ( ! empty( $key ) ) {
		unset( $supported_types[ $key ] );
	}
	return $supported_types;
}
add_filter( 'bccl_metabox_post_types', __NAMESPACE__ . '\remove_book_from_creative_commons' );

function remove_creative_commons_on_single_book() {
	global $post;

	if ( 'books' === $post->post_type ) {
		remove_filter('the_content', 'bccl_append_to_post_body', apply_filters( 'bccl_append_to_post_body_filter_priority', 250 ) );
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\remove_creative_commons_on_single_book' );
