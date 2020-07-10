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