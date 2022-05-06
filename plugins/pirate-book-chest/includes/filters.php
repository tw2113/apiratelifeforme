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

	if ( ! is_post_type_archive( 'books' ) && ! is_post_type_archive( 'book-collections' ) && ! is_tax( 'book_status' ) && ! is_tax( 'genre' ) && ! is_tax( 'book_chest' ) ) {
		return;
	}

	$wp_query->set( 'posts_per_page', 30 );
	$wp_query->set( 'orderby', 'modified' );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\books_query_mods' );

function books_chest_archive_title( $title ) {
	if ( is_post_type_archive( 'books' ) ) {
		return 'Pirate Book Chest';
	}
	return $title;
}
add_filter( 'get_the_archive_title', __NAMESPACE__ . '\books_chest_archive_title' );

function book_status_archive_title( $title ) {
	if ( is_tax( 'book_status' ) ) {
		return sprintf(
			'Reading status: "%s", total: %s',
			get_queried_object()->name,
			get_queried_object()->count
		);
	}
	return $title;
}
add_filter( 'get_the_archive_title', __NAMESPACE__ . '\book_status_archive_title' );

function book_status_update( $post_id, $post, $updated ) {
	if ( empty( $_POST ) ) {
		return;
	}

	if ( ! $updated ) {
		return;
	}

	if ( ! function_exists( 'wpt_oauth_connection' ) ) {
		return;
	}

	$orig_page      = get_post_meta( $post_id, 'pbc_current_page', true );
	$new_page       = sanitize_text_field( $_POST['pbc_current_page'] );
	$total_pages    = get_post_meta( $post_id, 'pbc_total_pages', true );
	$author         = get_post_meta( $post_id, 'pbc_book_authors', true );
	$reading_status = get_the_terms( $post_id, 'book_status' );
	$new_status     = sanitize_text_field( $_POST['pbc_reading_status'] );

	$connection                 = wpt_oauth_connection( 1 );
	$api                        = 'https://api.twitter.com/1.1/statuses/update.json';
	$status['include_entities'] = 'true';

	// change of current page and not completing the book.
	if ( $new_page > $orig_page && $new_page < $total_pages ) {
		$status['status'] = sprintf(
			'On page %s of %s on %s, by %s, %s',
			$new_page,
			$total_pages,
			$post->post_title,
			$author,
			get_permalink( $post_id )
		);

		$connection->post( $api, $status );
	}

	if ( $reading_status[0]->slug === 'to-read' && $new_status === 'currently-reading' ) {
		$status['status'] = sprintf(
			'I have started reading %s by %s %s',
			$post->post_title,
			$author,
			get_permalink( $post_id )
		);

		$connection->post( $api, $status );
	}

	if ( $reading_status[0]->slug === 'currently-reading' && $new_status === 'read' ) {
		$status['status'] = sprintf(
			'I have finished reading %s by %s %s',
			$post->post_title,
			$author,
			get_permalink( $post_id )
		);

		$connection->post( $api, $status );
	}
}
add_action( 'save_post_books', __NAMESPACE__ . '\book_status_update', 10, 3 );

function book_meta_description( $description ) {
	if ( ! is_post_type_archive( 'books' ) ) {
		return $description;
	}

	return 'Michael Beckwith\'s reading tracking archive, documenting what he has read, is reading, and wants to read in the future.';
}
add_filter( 'the_seo_framework_pta_description', __NAMESPACE__ . '\book_meta_description' );

function book_meta_title( $description ) {
	if ( ! is_post_type_archive( 'books' ) ) {
		return $description;
	}

	return 'Pirate Book Chest Archive';
}
add_filter( 'the_seo_framework_title_from_generation', __NAMESPACE__ . '\book_meta_title' );

function book_collection_meta_title( $description ) {
	if ( ! is_post_type_archive( 'book-collections' ) ) {
		return $description;
	}

	return 'Pirate Book Chest Collection Archive';
}
add_filter( 'the_seo_framework_title_from_generation', __NAMESPACE__ . '\book_collection_meta_title' );

function book_rest_metadata() {
	\register_rest_field( 'books',
		'pbc_book_isbn',
		[
			'get_callback'    => function( $object, $field_name, $request ) { return get_post_meta( $object[ 'id' ], $field_name ); },
			'update_callback' => null,
			'schema'          => null,
		]
	);

	\register_rest_field( 'books',
		'pbc_total_pages',
		[
			'get_callback'    => function( $object, $field_name, $request ) { return get_post_meta( $object[ 'id' ], $field_name ); },
			'update_callback' => null,
			'schema'          => null,
		]
	);

	\register_rest_field( 'books',
		'pbc_book_authors',
		[
			'get_callback'    => function( $object, $field_name, $request ) { return get_post_meta( $object[ 'id' ], $field_name ); },
			'update_callback' => null,
			'schema'          => null,
		]
	);

	\register_rest_field( 'books',
		'pbc_start_date',
		[
			'get_callback'    => function( $object, $field_name, $request ) { return get_post_meta( $object[ 'id' ], $field_name ); },
			'update_callback' => null,
			'schema'          => null,
		]
	);

	\register_rest_field( 'books',
		'pbc_finished_date',
		[
			'get_callback'    => function( $object, $field_name, $request ) { return get_post_meta( $object[ 'id' ], $field_name ); },
			'update_callback' => null,
			'schema'          => null,
		]
	);
}
add_action( 'rest_api_init', __NAMESPACE__ . '\book_rest_metadata' );

function book_rest_api_orderby_rand( $query_params ) {
	$query_params['orderby']['enum'][] = 'rand';
	$query_params['orderby']['enum'][] = 'pbc_finished_date';

	return $query_params;
}
add_filter( 'rest_books_collection_params', __NAMESPACE__ . '\book_rest_api_orderby_rand' );

function book_rest_orderby_finished_date( $args, $request ) {
    $order_by = $request->get_param( 'orderby' );
    if ( isset( $order_by ) && 'pbc_finished_date' === $order_by ) {
        $args['meta_key'] = $order_by;
        $args['orderby']  = 'meta_value'; // user 'meta_value_num' for numerical fields
    }
    return $args;
}
add_filter( 'rest_books_query', __NAMESPACE__ . '\book_rest_orderby_finished_date', 10, 2 );

function book_image_sizes() {
    add_image_size( 'book_next_thumb', 500 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\book_image_sizes' );
