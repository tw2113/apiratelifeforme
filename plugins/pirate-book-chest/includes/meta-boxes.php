<?php

namespace tw2113\pbc;

function meta_boxes() {
	$prefix = 'pbc';
	$cmbpbc = new_cmb2_box( [
		'id'           => $prefix . '_book_metabox',
		'title'        => esc_html__( 'Book Details', 'cmb2' ),
		'object_types' => [ 'books' ],
		'context'      => 'normal',
		'priority'     => 'high',
	] );

	$cmbpbc->add_field( [
		'name'       => esc_html__( 'Current page', 'cmb2' ),
		'desc'       => esc_html__( 'What page are you on?', 'cmb2' ),
		'id'         => $prefix . '_current_page',
		'type'       => 'text_small',
	] );

	$cmbpbc->add_field( [
		'name'       => esc_html__( 'Total page', 'cmb2' ),
		'desc'       => esc_html__( 'How many pages does the book have?', 'cmb2' ),
		'id'         => $prefix . '_total_pages',
		'type'       => 'text_small',
	] );

}
add_action( 'cmb2_admin_init', __NAMESPACE__ . '\meta_boxes' );
