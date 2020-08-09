<?php
/**
 * OAuth version 2 implementation for interacting with Fitbit API.
 * @package API
 * @since   1.0.0
 */

namespace tw2113\fitbit\API;

class API {

	/**
	 * Authorization URI
	 * @since 1.0.0
	 */
	const AUTH_URI = 'https://www.fitbit.com/oauth2/authorize';

	/**
	 * Token URI
	 * @since 1.0.0
	 */
	const TOKEN_URI = 'https://api.fitbit.com/oauth2/token';

	/**
	 * App client ID.
	 * @var string
	 * @since 1.0.0
	 */
	private $client_id = '';

	/**
	 * App client secret ID.
	 * @var string
	 * @since 1.0.0
	 */
	private $client_secret = '';

	/**
	 * URI to redirect to, after authorization.
	 * @var string
	 * @since 1.0.0
	 */
	private $redirect_uri = '';

	private $response_type = '';

	private $scope = '';

	private $expires_in = '';

	private $auth_code = '';

	/**
	 * Returned access token.
	 * @var string
	 * @since 1.0.0
	 */
	protected $access_token = '';

	/**
	 * Token to use to refresh access without re-authorization.
	 * @var string
	 * @since 1.0.0
	 */
	private $refresh_token = '';

	private $errors = [];

	/**
	 * API constructor.
	 *
	 * @param array $args Array of arguments for instance.
	 */
	public function __construct( $args = [] ) {
		$this->client_id     = isset( $args['client_id'] )     ? $args['client_id']     : '';
		$this->client_secret = isset( $args['client_secret'] ) ? $args['client_secret'] : '';
		$this->redirect_uri  = isset( $args['redirect_uri'] )  ? $args['redirect_uri']  : '';
		$this->response_type = isset( $args['response_type'] ) ? $args['response_type'] : '';
		$this->scope         = isset( $args['scope'] )         ? $args['scope']         : '';
		$this->expires_in    = isset( $args['expires_in'] )    ? $args['expires_in']    : '';

		$this->auth_code    = $this->set_authentication_code();
		$this->access_token = $this->get_access_token();
		#$this->refresh_token = $this->get_refresh_token();
	}

	/**
	 * Returns a link to use to request authorization tokens.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_authentication_link() {
		$link = add_query_arg(
			[
				'client_id'     => $this->client_id,
				'redirect_uri'  => rawurlencode( $this->redirect_uri ),
				'response_type' => 'code',
				'scope'         => rawurlencode( $this->scope ),
			],
			self::AUTH_URI
		);

		return esc_url( $link );
	}

	/**
	 * Returns a button to use for authentication.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_authentication_button() {
		return sprintf(
			'<div class="button"><p class="submit"><a class="wp-ui-highlight" href="%s">%s</a></div>',
			$this->get_authentication_link(),
			esc_html__( 'Authenticate with Fitbit API', 'fitbit-api' )
		);
	}

	private function set_authentication_code() {
		return get_option( 'fitbit_oauth2_code', '' );
	}

	/**
	 * Returns a button to force refresh tokens.
	 *
	 * Not presently used.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_refresh_token_button() {
		return sprintf(
			'<div class="button"><p class="submit"><a class="wp-ui-highlight" href="%s">%s</a></div>',
			add_query_arg( [ 'refresh_formstack_token' => 'true' ], admin_url() ),
			esc_html__( 'Force API token refresh', 'fitbit-api' )
		);
	}

	/**
	 * Retrieve an access token to use with future requests.
	 *
	 * We will store current tokens and refresh tokens into options.
	 * Access tokens are good for 3600 seconds. Once time has elapsed, we
	 * need to request a new one using the refresh token.
	 *
	 * @since 1.0.0
	 */
	public function get_fresh_token() {
		if ( $this->is_token_expired() && $this->refresh_token ) {
			$this->refresh_token();
		}

		if ( empty( $this->access_token ) ) {

			$result = wp_remote_post( self::TOKEN_URI,
				[
					'body' => [
						'grant_type'    => 'authorization_code',
						'client_id'     => $this->client_id,
						'redirect_uri'  => $this->redirect_uri,
						'code'          => $this->auth_code,
					],
					'headers' => [
						'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ),
						'Content-Type'  => 'application/x-www-form-urlencoded',
					]
				]
			);

			if ( is_wp_error( $result ) ) {
				$error = sprintf(
					__( 'WordPress error: %s', 'fitbit-api' ),
					$result->get_error_message()
				);

				$this->add_error( $error );
				return;
			}

			$response = json_decode( wp_remote_retrieve_body( $result ) );
			if ( 200 !== wp_remote_retrieve_response_code( $result ) ) {
				if ( $response->error_description ) {
					$error = sprintf(
						__( 'Authentication error: %s', 'fitbit-api' ),
						$response->error_description
					);
					$this->add_error( $error );
				}
				return;
			}

			$this->set_access_token( $response );
			$this->set_refresh_token( $response );
			#$this->get_data();
		}
	}

	/**
	 * Sets our access token from our API response.
	 *
	 * @since 1.0.0
	 *
	 * @param object $response
	 */
	public function set_access_token( $response ) {
		update_option( 'fitbit_access_token', $response->access_token );
		$this->set_token_expiration( $response->expires_in );
		$this->access_token = $response->access_token;
	}

	/**
	 * Return our current token.
	 *
	 * @since 1.0.0
	 */
	public function get_access_token() {
		return get_option( 'fitbit_access_token', '' );
	}

	/**
	 * Sets our refresh token from our API response.
	 *
	 * @since 1.0.0
	 *
	 * @param object $response
	 */
	public function set_refresh_token( $response ) {
		update_option( 'fitbit_refresh_token', $response->refresh_token );
		$this->refresh_token = $response->refresh_token;
	}

	/**
	 * Return our current refresh token.
	 *
	 * @since 1.0.0
	 */
	public function get_refresh_token() {
		return get_option( 'fitbit_refresh_token', '' );
	}

	/**
	 * Check whether or not we have our tokens set.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function has_tokens() {
		$has = false;
		if ( ! empty( $this->access_token ) ) {
			$has = true;
		}
		return $has;
	}

	/**
	 * Set an expiration time option so we can check if we should refresh.
	 *
	 * @since 1.0.0
	 *
	 * @param string $duration Time the token persists.
	 */
	public function set_token_expiration( $duration ) {
		update_option( 'fitbit_token_expiration', time() + $duration );
	}

	/**
	 * Check whether or not we need to renew tokens.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed
	 */
	public function is_token_expired() {
		$expires = get_option( 'fitbit_token_expiration', '' );

		// Returning the default string instead of bool
		// for the sake of providing a way to display "none set".
		if ( empty( $expires ) ) {
			return $expires;
		}

		if ( time() < $expires ) {
			return false;
		}

		return true;
	}

	/**
	 * Requests some new tokens, using our current refresh token.
	 *
	 * @since 1.0.0
	 */
	public function refresh_token() {
		$args = [
			'body' => [
				'grant_type'    => 'refresh_token',
				'refresh_token' => $this->refresh_token,
				'client_id'     => $this->client_id,
				'client_secret' => $this->client_secret,
			]
		];
		$result = wp_remote_post( self::TOKEN_URI, $args );
		if ( is_wp_error( $result ) ) {
			return;
		}

		$response = json_decode( wp_remote_retrieve_body( $result ) );
		if ( 200 === wp_remote_retrieve_response_code( $result ) ) {
			$this->set_access_token( $response );
			$this->set_refresh_token( $response );
		}
	}
}