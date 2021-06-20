<?php

namespace tw2113\pbc;

function meta_boxes() {
	$prefix = 'pbc';

	/**
	 * My current status details.
	 */
	$cmbpbc_reading = new_cmb2_box( [
		'id'           => $prefix . '_book_reading_metabox',
		'title'        => esc_html__( 'Book Reading Details', 'pirate-book-chest' ),
		'object_types' => [ 'books' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$cmbpbc_reading->add_field( [
		'name'       => esc_html__( 'Current page', 'pirate-book-chest' ),
		'desc'       => esc_html__( 'What page are you on?', 'pirate-book-chest' ),
		'id'         => $prefix . '_current_page',
		'type'       => 'text_small',
	] );

	$cmbpbc_reading->add_field( [
		'name'       => esc_html__( 'Total pages', 'pirate-book-chest' ),
		'desc'       => esc_html__( 'How many pages does the book have?', 'pirate-book-chest' ),
		'id'         => $prefix . '_total_pages',
		'type'       => 'text_small',
	] );

	$cmbpbc_reading->add_field( [
		'name'        => 'Started date',
		'id'          => $prefix . '_start_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'Y.m.d',
	] );

	$cmbpbc_reading->add_field( [
		'name'        => 'Finished date',
		'id'          => $prefix . '_finished_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'Y.m.d',
	] );

	$cmbpbc_reading->add_field( [
		'name'             => 'Reading status',
		'desc'             => 'What is the status?',
		'id'               => $prefix . '_reading_status',
		'taxonomy'         => 'book_status',
		'type'             => 'taxonomy_radio',
		'remove_default'   => 'true',
		'show_option_none' => false,
	] );

	$cmbpbc_reading->add_field( [
		'name'           => 'Current book chests',
		'desc'           => 'Which chests do this book belong to?',
		'id'             => $prefix . '_book_chest',
		'taxonomy'       => 'book_chest',
		'type'           => 'taxonomy_multicheck',
		'remove_default' => 'true',
	] );

	$cmbpbc_reread = $cmbpbc_reading->add_field( [
		'id'          => $prefix . '_rereads',
		'type'        => 'group',
		'description' => 'Re-reads',
		'repeatable'  => true,
		'options'     => [
			'group_title'       => 'Re-read {#}',
			'add_button'        => 'Add new re-read dates',
			'remove_button'     => 'Remove re-read',
		],
	] );

	$cmbpbc_reading->add_group_field( $cmbpbc_reread, [
		'name'        => 'Re-read start date',
		'id'          => $prefix . '_reread_start_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'Y.m.d',
	] );

	$cmbpbc_reading->add_group_field( $cmbpbc_reread, [
		'name'        => 'Re-read end date',
		'id'          => $prefix . '_reread_end_date',
		'type'        => 'text_date_timestamp',
		'date_format' => 'Y.m.d',
	] );

	/**
	 * Book review and notes
	 */
	$cmbpbc_reviews = new_cmb2_box( [
		'id'           => $prefix . '_book_reviews_metabox',
		'title'        => esc_html__( 'Book Review Details', 'pirate-book-chest' ),
		'object_types' => [ 'books' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$cmbpbc_reviews->add_field( [
		'name'    => 'Rating',
		'id'      => $prefix . '_rating',
		'type'    => 'select',
		'options' => [
			'rating0' => 'None',
			'rating1' => '1 star',
			'rating2' => '2 stars',
			'rating3' => '3 stars',
			'rating4' => '4 stars',
			'rating5' => '5 stars',
		],
	] );

	$cmbpbc_reviews->add_field( [
		'name'           => 'Book Review',
		'id'             => $prefix . '_book_review',
		'type'           => 'book_review',
	] );

	$cmbpbc_reviews->add_field( [
		'name'           => 'Annotations',
		'id'             => $prefix . '_annotation',
		'type'           => 'annotations',
	] );

	/**
	 * Book details.
	 */
	$cmbpbc_details = new_cmb2_box( [
		'id'           => $prefix . '_book_details_metabox',
		'title'        => esc_html__( 'Book Information', 'pirate-book-chest' ),
		'object_types' => [ 'books' ],
		'context'      => 'side',
		'priority'     => 'default',
	] );

	$cmbpbc_details->add_field( [
		'name'           => 'Genre',
		'id'             => $prefix . '_book_genre',
		'taxonomy'       => 'genre',
		'type'           => 'taxonomy_multicheck_hierarchical',
		'remove_default' => 'true',
	] );

	$cmbpbc_details->add_field( [
		'name'           => 'ISBN 13',
		'id'             => $prefix . '_book_isbn',
		'type'           => 'text_medium',
	] );

	$cmbpbc_details->add_field( [
		'name'           => 'Author(s)',
		'id'             => $prefix . '_book_authors',
		'type'           => 'text_medium',
	] );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\meta_boxes' );

function challenge_meta_boxes() {
	$prefix = 'pbc';

	/**
	 * My current challenge status details.
	 */
	$cmbpbc_challenge = new_cmb2_box( [
		'id'           => $prefix . '_book_reading_challenge_metabox',
		'title'        => esc_html__( 'Book Reading Challenge Details', 'pirate-book-chest' ),
		'object_types' => [ 'book-challenges' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$cmbpbc_challenge->add_field( [
		'name'       => esc_html__( 'Total Book Goal', 'pirate-book-chest' ),
		'desc'       => esc_html__( 'How many books do you want to read?', 'pirate-book-chest' ),
		'id'         => $prefix . '_total_goal',
		'type'       => 'text_small',
		'attributes' => [
			'type' => 'number'
		]
	] );

	$cmbpbc_challenge->add_field( [
		'name'    => __( 'Achieved books', 'pirate-book-chest' ),
		'desc'    => __( 'Books you successfully read for this challenge', 'pirate-book-chest' ),
		'id'      => $prefix . '_read_books',
		'type'    => 'custom_attached_posts',
		'column'  => false, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => [
			'show_thumbnails' => false, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => [
				'posts_per_page' => -1,
				'post_type'      => 'books',
				'tax_query'      => [
					[
						'taxonomy' => 'book_status',
						'field'    => 'slug',
						'terms'    => 'read',
					]
				]
			], // override the get_posts args
		],
	] );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\challenge_meta_boxes' );

function collection_meta_boxes() {
	$prefix = 'pbc';

	/**
	 * My current challenge status details.
	 */
	$cmbpbc_collection = new_cmb2_box( [
		'id'           => $prefix . '_book_reading_collection_metabox',
		'title'        => esc_html__( 'Book collection Details', 'pirate-book-chest' ),
		'object_types' => [ 'book-collections' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$cmbpbc_collection->add_field( [
		'name'    => __( 'Collection', 'pirate-book-chest' ),
		'desc'    => __( 'Books for this collection', 'pirate-book-chest' ),
		'id'      => $prefix . '_collection',
		'type'    => 'custom_attached_posts',
		'column'  => false, // Output in the admin post-listing as a custom column. https://github.com/CMB2/CMB2/wiki/Field-Parameters#column
		'options' => [
			'show_thumbnails' => false, // Show thumbnails on the left
			'filter_boxes'    => true, // Show a text box for filtering the results
			'query_args'      => [
				'posts_per_page' => -1,
				'post_type'      => 'books',
				'orderby'        => 'title',
				'order'          => 'ASC',
			], // override the get_posts args
		],
	] );
}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\collection_meta_boxes' );


function cmb2_render_book_review( $field, $escaped_value, $object_id,
								 $object_type, $field_type_object ) {

	$reviews = get_comments(
		[
			'post_id' => $object_id,
			'type' => 'BookReview',
		]
	);

	if ( empty( $reviews ) ) {
		echo 'No reviews yet';
		return;
	}

	foreach ( $reviews as $review ) {
		printf( '<h3>Rating: %s star</h3>', get_comment_meta( $review->comment_ID, 'pbc_rating', true ) );

		echo wpautop( $review->comment_content );
	}


}
add_action( 'cmb2_render_book_review', __NAMESPACE__ . '\cmb2_render_book_review', 10, 5 );

function cmb2_render_annotations( $field, $escaped_value, $object_id,
								  $object_type, $field_type_object ) {

	$annotations = get_comments(
		[
			'post_id' => $object_id,
			'type' => 'Annotation',
		]
	);

	if ( empty( $annotations ) ) {
		echo 'No annotations yet';
		return;
	}

	foreach ( $annotations as $annotation ) {
		echo wpautop( $annotation->comment_content );
	}


}
add_action( 'cmb2_render_annotations', __NAMESPACE__ . '\cmb2_render_annotations', 10, 5 );