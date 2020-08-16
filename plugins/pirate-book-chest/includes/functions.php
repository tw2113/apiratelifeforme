<?php

namespace tw2113\pbc;

function get_total_to_read_pages() {
	//select sum( meta_value ) from wp_postmeta where meta_key = 'pbc_total_pages' and post_id in ( SELECT ID from wp_posts where post_type = 'books' )
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

	return $books->found_posts;
}

function the_total_to_read_pages() {
	echo get_total_to_read_pages();
}