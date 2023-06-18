<?php

namespace tw2113;
function algolia_exclude_post_types( $post_types ) {

	// Ignore these post types.
	unset( $post_types['tumblr'] );
	unset( $post_types['coffee_checkins'] );
	unset( $post_types['presentation'] );

	return $post_types;
}
add_filter( 'algolia_searchable_post_types', __NAMESPACE__ . '\algolia_exclude_post_types' );

function algolia_custom_fields( array $attributes, \WP_Post $post ) {

	$fields = [
		'pbc_book_authors',
	];

	foreach ( $fields as $field ) {
		$data = get_post_meta( $post->ID, $field, true );

		if ( ! empty( $data )  ) {
			$authors = array_filter( explode( ',', $data ) );
			if ( ! empty( $authors ) ) {
				$authors = array_map( 'trim', $authors );
			}
			$attributes[ $field ] = $authors;
		}
	}

	if ( 'books' === $post->post_type ) {
		$rating = get_post_meta( $post->ID, 'pbc_rating', true );
		if ( empty( $rating ) ) {
			$attributes['rating'] = 'n/a';
		} else {
			$attributes['rating'] = absint( substr( $rating, - 1, 1 ) );
		}
	}

	unset( $attributes['post_author'] );
	unset( $attributes['post_mime_type'] );
	unset( $attributes['menu_order'] );
	unset( $attributes['is_sticky'] );

	return $attributes;
}
add_filter( 'algolia_post_shared_attributes', __NAMESPACE__ . '\algolia_custom_fields', 10, 2 );
add_filter( 'algolia_searchable_post_shared_attributes', __NAMESPACE__ . '\algolia_custom_fields', 10, 2 );

function posts_index_settings( $settings ) {

	$settings['searchableAttributes'] = [
		'unordered(pbc_book_authors)',
		'unordered(post_title)',
		'unordered(taxonomies)',
		'unordered(content)',
	];

	$settings['attributesForFaceting'] = [
		'searchable(pbc_book_authors)',
		'taxonomies',
		'taxonomies_hierarchical',
	];

	return $settings;
}
add_filter( 'algolia_posts_index_settings', __NAMESPACE__ . '\posts_index_settings' );
add_filter( 'algolia_posts_books_index_settings', __NAMESPACE__ . '\posts_index_settings' );
add_filter( 'algolia_searchable_posts_index_settings', __NAMESPACE__ . '\posts_index_settings', 10, 2 );

function posts_thumbnail_sizes( $sizes ) {
	$sizes[] = 'medium';

	return $sizes;
}
add_filter( 'algolia_post_images_sizes', __NAMESPACE__ . '\posts_thumbnail_sizes' );