<?php

namespace tw2113\fitbit;

function fitbit_exercise_receipts() {

	#global $wpdb;
	#$receipts = new exerciseReceipts( $wpdb );

	#$years = wp_list_pluck( $receipts->getYears(), 'year' );

	return '';
}
add_shortcode( 'exercise_receipts', __NAMESPACE__ . '\fitbit_exercise_receipts' );

function maybe_save_authentication_code() {
	if ( isset( $_GET['code'] ) ) {
		update_option( 'fitbit_oauth2_code', sanitize_text_field( $_GET['code'] ) );
	}
}
add_action( 'init', __NAMESPACE__ . '\maybe_save_authentication_code' );