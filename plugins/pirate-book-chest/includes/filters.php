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

	if ( isset( $post->post_type ) && 'books' === $post->post_type ) {
		remove_filter('the_content', 'bccl_append_to_post_body', apply_filters( 'bccl_append_to_post_body_filter_priority', 250 ) );
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\remove_creative_commons_on_single_book' );

function books_query_mods( $wp_query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! is_post_type_archive( 'books' ) && ! is_tax( 'book_status' ) ) {
		return;
	}

	$wp_query->set( 'posts_per_page', 30 );
	$wp_query->set( 'orderby', 'rand' );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\books_query_mods' );

function books_chest_archive_title( $title ) {
	if ( is_archive( 'book' ) ) {
		return 'Pirate Book Chest';
	}
	return $title;
}
add_filter( 'get_the_archive_title', __NAMESPACE__ . '\books_chest_archive_title' );