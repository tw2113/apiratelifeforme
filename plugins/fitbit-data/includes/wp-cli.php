<?php

namespace tw2113\fitbit;

class ProcessFitBitData extends \WP_CLI_Command {

	public $args;

	public $assoc_args;

	public $type;

	public $data = array();

	private $path;

	private $wpdb;

	public function __construct() {
		$this->path = WP_CONTENT_DIR . '/plugins/fitbit-data/';

		global $wpdb;
		$this->wpdb = $wpdb;
	}


	public function run( $args, $assoc_args ) {
		$this->args       = $args;
		$this->assoc_args = $assoc_args;


		$files = $this->get_files_by_data_type( $this->assoc_args['data_type'] );

		$progress_bar = \WP_CLI\Utils\make_progress_bar(
			'Processing ' . count( $files ) . ' files',
			count( $files )
		);
		foreach ( $this->file_generator( $files ) as $f ) {
			$fileparts = explode( '/', $f );

			$file = $this->parse_data_file( $f );

			foreach( $this->json_data_generator( $file ) as $item ) {
				$this->insert( $item, $this->assoc_args['data_type'] );
			}
			$progress_bar->tick();
		}
		$progress_bar->finish();
	}

	public function exercise_run( $args, $assoc_args ) {
		$this->args = $args;
		$this->assoc_args = $assoc_args;

		$files = $this->get_files_by_data_type( $this->assoc_args['data_type'] );

		$progress_bar = \WP_CLI\Utils\make_progress_bar(
			'Processing ' . count( $files ) . ' files',
			count( $files )
		);

		foreach ( $this->file_generator( $files ) as $f ) {
			$file = $this->parse_data_file( $f );

			foreach( $this->json_data_generator( $file ) as $item ) {
				$this->exercise_insert( $item, $this->assoc_args['data_type'] );
			}
			$progress_bar->tick();
		}
		$progress_bar->finish();
	}

	private function get_files_by_data_type( $data_type = '' ) : array {
		$paths = glob( $this->path . "data/user-site-export/$data_type-*.json" );
		return $paths;
	}

	private function file_generator( $files ) {
		foreach ( $files as $file ) {
			yield $file;
		}
	}

	private function parse_data_file( $file ) {
		$data = json_decode( file_get_contents( $file ) );
		\WP_CLI::LOG( "\nFound " . count( $data ) . ' records to insert' );

		return $data;
	}

	private function json_data_generator( $data ) {
		foreach ( $data as $datum ) {
			yield $datum;
		}
	}

	private function insert( $item, $data_type ) {

		if ( '0' === $item->value ) {
			return;
		}

		$formatted = date( 'Y-m-d H:i:s', strtotime( $item->dateTime ) );
		$this->wpdb->insert(
			$this->wpdb->prefix . 'fitbit_' . $data_type,
			[
				'dateTime' => $formatted,
				$data_type => $item->value,
			],
			[
				'%s',
				'%s'
			]
		);
	}

	private function exercise_insert( $item, $data_type ) {
		$formatted_date = date( 'Y-m-d H:i:s', strtotime( $item->startTime ) );

		$this->wpdb->insert(
			$this->wpdb->prefix . 'fitbit_' . $data_type,
			[
				'logId' => $item->logId,
				'activityName' => $item->activityName,
				'activityTypeId' => $item->activityTypeId,
				'averageHeartRate' => $item->averageHeartRate,
				'calories' => $item->calories,
				'distance' => $item->distance,
				'duration' => $item->duration,
				'activeDuration' => $item->activeDuration,
				'steps' => $item->steps,
				'startTime' => $formatted_date
			],
			[
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s'
			]
		);
	}
}
\WP_CLI::add_command( 'fitbit', __NAMESPACE__ . '\ProcessFitBitData' );
