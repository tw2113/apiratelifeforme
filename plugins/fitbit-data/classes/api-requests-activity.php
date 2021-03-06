<?php

namespace tw2113\fitbit\API\Activity;
use tw2113\fitbit\API\API;

class Activity extends API {

	private $endpoint = 'https://api.fitbit.com/1/user/-/activities/date/[date].json';

	private $lifetime_activity_endpoint = 'https://api.fitbit.com/1/user/[user-id]/activities.json';

	# /1/user/[user-id]/[resource-path]/date/[date]/[period].json
	# /1/user/[user-id]/[resource-path]/date/[base-date]/[end-date].json

	/**
	 * API constructor.
	 *
	 * @param array $args Array of arguments for instance.
	 */
	public function __construct( $args = [] ) {
		parent::__construct( $args );
	}

	public function get_day_activities( $date = '' ) {
		if ( empty( $date ) ) {
			$dt = new \DateTime( '-1 day' );
			$date = $dt->format('Y-m-d');
		}

		$endpoint = str_replace( '[date]', $date, $this->endpoint );

		$request_args = [
			'timeout' => 120,
			'headers' => [
				'Authorization' => 'Bearer ' . $this->access_token
			]
		];

		$result = wp_remote_get(
			$endpoint,
			$request_args
		);

		if ( is_wp_error( $result ) ) {

			$error = sprintf(
				__( 'WordPress error: %s', 'fitbit-api' ),
				$result->get_error_message()
			);
			return $error;
		}

		$response = json_decode( wp_remote_retrieve_body( $result ) );
		if ( 200 === wp_remote_retrieve_response_code( $result ) ) {
			return $response;
		}
	}

	public function get_lifetime_activity() {
		$request_args = [
			'timeout' => 120,
			'headers' => [
				'Authorization' => 'Bearer ' . $this->access_token
			]
		];

		$result = wp_remote_get(
			$this->lifetime_activity_endpoint,
			$request_args
		);

		if ( is_wp_error( $result ) ) {

			$error = sprintf(
				__( 'WordPress error: %s', 'fitbit-api' ),
				$result->get_error_message()
			);
			return $error;
		}

		$response = json_decode( wp_remote_retrieve_body( $result ) );
		if ( 200 === wp_remote_retrieve_response_code( $result ) ) {
			return $response;
		}
	}
}