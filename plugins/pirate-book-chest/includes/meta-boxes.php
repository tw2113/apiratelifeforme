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
		'type'           => 'taxonomy_multicheck',
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