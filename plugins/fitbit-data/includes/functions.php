<?php

namespace tw2113\fitbit;

function fitbit_exercise_receipts() {

	#global $wpdb;
	#$receipts = new exerciseReceipts( $wpdb );

	#$years = wp_list_pluck( $receipts->getYears(), 'year' );

	return '';
}
add_shortcode( 'exercise_receipts', __NAMESPACE__ . '\fitbit_exercise_receipts' );
