<?php

namespace tw2113\fitbit;

/**
 * Interact with our exercise table.
 *
 * @package tw2113\fitbit
 */
class exerciseReceipts {

	private $wpdb;

	private $dbtable;

	public function __construct( \wpdb $wpdb ) {
		$this->wpdb = $wpdb;
		$this->dbtable = $this->wpdb->prefix . 'fitbit_exercise';
	}

	/**
	 * Get activities done for the selected dates.
	 *
	 * Dates can be by year, by month, by day, or some combination of the three.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed Activities to output.
	 */
	public function getReceipts() {
		return '';
	}

	/**
	 * Get a list of available years based on saved data.
	 *
	 * @return array|object|null
	 */
	public function getYears() {
		$request = "SELECT DISTINCT YEAR(startTime) as year FROM `{$this->dbtable}` ORDER BY year ASC";
		$results = $this->wpdb->get_results( $request, ARRAY_A );

		return $results;
	}

	/**
	 * Get a list of available months based on saved data.
	 *
	 * @return array|object|null
	 */
	public function getMonths() {
		$request = "SELECT DISTINCT MONTH(startTime) as month FROM `{$this->dbtable}` ORDER BY month ASC";
		$results = $this->wpdb->get_results( $request, ARRAY_A );

		return $results;
	}

	/**
	 * Get a list of available days based on saved data.
	 *
	 * @return array|object|null
	 */
	public function getDays() {
		$request = "SELECT DISTINCT DAY(startTime) as day FROM `{$this->dbtable}` ORDER BY day ASC";
		$results = $this->wpdb->get_results( $request, ARRAY_A );

		return $results;
	}
}