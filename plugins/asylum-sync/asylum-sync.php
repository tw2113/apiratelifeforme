<?php
/**
 * Plugin Name: The Pirate Asylum Data Sync
 * Plugin URI: https://apiratelifefor.me
 * Description: Import video content posted to https://thepirateasylum.wordpress.com
 * Author: tw2113
 * Author URI: https://michaelbox.net
 * Version: 1.0.0
 */

namespace tw2113\asylumSync;

function listener() {
	if ( '/asylum-endpoint/' !== $_SERVER['REQUEST_URI'] ) {
		return;
	}

	if ( empty( $_POST ) ) {
		return;
	}

	$args = [
		'post_type' => 'music_video',
		'post_status' => 'publish',
		'post_content' => wp_kses_post( $_POST['post_content'] ),
		'post_title' => sanitize_text_field( $_POST['post_title'] ),
		'post_author' => 1,
	];

	$id = wp_insert_post( $args );

	update_post_meta( $id, 'post_dates', [ 'date' => sanitize_text_field( $_POST['post_date'] ) ] );
	update_post_meta( $id, 'name', sanitize_text_field( $_POST['post_name'] ) );
	update_post_meta( $id, 'permalink', sanitize_text_field( $_POST['post_url'] ) );
}
add_action( 'init', __NAMESPACE__ . '\listener' );


function import() {
	if ( empty( $_GET ) ) {
		return;
	}

	if ( ! isset( $_GET['do-import'] )|| 'true' !== $_GET['do-import'] ) {
		return;
	}

	set_time_limit( 0 );

	$posts = wp_remote_retrieve_body( wp_remote_get( 'https://public-api.wordpress.com/rest/v1.1/sites/thepirateasylum.wordpress.com/posts/?category=music&status=publish&number=1' ) );

	$data = json_decode( $posts );

	$args = [
		'post_type' => 'music_video',
		'post_status' => 'publish',
	];
	foreach ( $data->posts as $post ) {
		$args['post_content'] = $post->content;
		$args['post_title'] = sanitize_text_field( $post->title );

		$id = wp_insert_post( $args );

		update_post_meta( $id, 'post_dates', [ 'date' => sanitize_text_field( $post->date ) ] );
		update_post_meta( $id, 'name', sanitize_text_field( $post->name ) );
		update_post_meta( $id, 'permalink', sanitize_text_field( $post->URL ) );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\import' );
