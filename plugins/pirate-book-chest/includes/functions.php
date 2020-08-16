<?php

namespace tw2113\pbc;

function get_total_to_read_pages() {
	global $wpdb;

	$args = [
		'post_type'      => 'books',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'tax_query'      => [
			[
				'taxonomy' => 'book_status',
				'field'    => 'slug',
				'terms'    => 'to-read',
			]
		],
		'fields'         => 'ids',
	];

	$books = new \WP_Query( $args );
	if ( ! $books->have_posts() ) {
		return '0';
	}

	$book_ids = str_replace( "''", "'", implode( "','", $books->posts ) );
	$book_ids = "'{$book_ids}'";

	$rs = $wpdb->get_results(
		"SELECT sum( meta_value ) as pages from wp_postmeta where meta_key = 'pbc_total_pages' and post_id in ( {$book_ids} )"
	);

	if ( empty( $rs ) ) {
		return '0';
	}

	return number_format( $rs[0]->pages );
}

function the_total_to_read_pages() {
	echo get_total_to_read_pages();
}