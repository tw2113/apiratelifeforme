<?php

namespace tw2113;

function scripts_styles() {
	wp_enqueue_style( 'autonomie-parent', get_template_directory_uri() . '/style.css', [ 'dashicons' ] );
	wp_enqueue_style( 'autonomie-child', get_stylesheet_directory_uri() . '/css/styles.css', [ 'dashicons', 'autonomie-parent' ] );

	/*
		 * Adds JavaScript to pages with the comment form to support sites with
		 * threaded comments (when in use).
		 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Add  support to older versions of IE
	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
		 ( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
		 ( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

		wp_enqueue_script( '', get_template_directory_uri() . '/js/html5shiv.min.js', false, '3.7.3' );
	}

	wp_enqueue_script( 'autonomie-navigation', get_template_directory_uri() . '/js/navigation.js', [], '1.0.0', true );
	wp_enqueue_script( 'autonomie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', [], '1.0.0', true );

	wp_enqueue_style( 'dashicons' );

	wp_enqueue_style( 'autonomie-print-style', get_template_directory_uri() . '/css/print.css', [ 'autonomie-parent' ], '1.0.0', 'print' );
	wp_enqueue_style( 'autonomie-narrow-style', get_template_directory_uri() . '/css/narrow-width.css', [ 'autonomie-parent' ], '1.0.0', '(max-width: 800px)' );
	wp_enqueue_style( 'autonomie-default-style', get_template_directory_uri() . '/css/default-width.css', [ 'autonomie-parent' ], '1.0.0', '(min-width: 800px)' );
	wp_enqueue_style( 'autonomie-wide-style', get_template_directory_uri() . '/css/wide-width.css', [ 'autonomie-parent' ], '1.0.0', '(min-width: 1000px)' );

	wp_localize_script(
		'autonomie',
		'vars',
		[ 'template_url' => get_template_directory_uri() ]
	);

	if ( has_header_image() ) {
		if ( is_author() ) {
			$css = '.page-banner {
					background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
				}' . PHP_EOL;
		} else {
			$css = '.page-banner {
					background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
				}' . PHP_EOL;
		}

		wp_add_inline_style( 'autonomie-parent', $css );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts_styles' );

function credits() {
	$quotes = [
		'We are wolves of the sea.',
		'What a Kraken grasps it does not lose, be it a longship or leviathan.'
	];

	$total = count( $quotes );

	$quote = $quotes[ rand( 0, absint( $total - 1 ) ) ];
	echo wpautop( $quote );
	echo '<p><a href="https://xn--sr8hvo.ws/3%EF%B8%8F%E2%83%A3%F0%9F%93%B2%F0%9F%8D%A3/previous">&lt;~</a> An IndieWeb Webring 🕸💍 <a href="https://xn--sr8hvo.ws/3%EF%B8%8F%E2%83%A3%F0%9F%93%B2%F0%9F%8D%A3/next">~&gt;</a></p>';
	echo '<p><a rel="me" href="https://indieweb.social/@tw2113">Indieweb.social Mastodon</a></p>';
	echo '<p class="copy">First set sail in 2019. Free of any analytics tracking.</p>';
}
add_action( 'autonomie_credits', __NAMESPACE__ . '\credits' );

function badges() {
?>
<div class="badges">
	<a href="http://indieweb.org" rel="nofollow" alt="IndieWeb"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/indieweb.svg" alt="IndieWebCamp"></a>
	<a href="http://microformats.org/wiki/get-started"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/microformats.svg" alt="Microformats.org"></a>
	<a title="This site accepts webmentions." href="https://www.w3.org/TR/webmention/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/webmention.svg" alt="Webmention" /></a>
</div>
<?php
}
add_action( 'autonomie_credits', __NAMESPACE__ . '\badges' );

function hide_webmentions() {
	if ( ! is_page( 'bean-me' ) ) {
		return;
	}
	remove_action( 'comment_form_after', 'webmention_comment_form', 11 );
	remove_action( 'comment_form_comments_closed', 'webmention_comment_form' );
}
add_action( 'wp_head', __NAMESPACE__ . '\hide_webmentions' );

function random_video( $wp_query ) {
	if ( is_admin() ) {
		return;
	}
	if ( ! is_post_type_archive( 'music_video' ) ) {
		return;
	}

	$wp_query->set( 'posts_per_page', 1 );
	$wp_query->set( 'orderby', 'rand' );
	$wp_query->set( 'post__not_in', [ 107 ] );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\random_video' );

function bookmarks_posts_per_page( $wp_query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! is_post_type_archive( 'bookmarks' ) ) {
		return;
	}

	$wp_query->set( 'posts_per_page', 15 );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\bookmarks_posts_per_page' );

function coffee_statistics() {

	$coffee_numbers = get_transient( 'coffee_stats' );
	if ( false === $coffee_numbers ) {
		$args = [
			'post_type'      => 'coffee_checkins',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		];

		$coffee_posts   = new \WP_Query($args);
		$coffee_numbers = [];
		$dunn           = 0;
		$dawley         = 0;
		$louise         = 0;
		$queen          = 0;
		$down           = 0;
		$source         = 0;
		$other          = 0;

		if ( $coffee_posts->have_posts() ) {
			while ( $coffee_posts->have_posts() ) {
				$content = get_the_content();

				if ( false !== strpos( $content, 'Dunn Bros' ) ) {
					$dunn++;
				}
				if ( false !== strpos( $content, 'Coffea Dawley Farms' ) ) {
					$dawley++;
				}
				if ( false !== strpos( $content, 'Coffea Louise' ) ) {
					$louise++;
				}
				if ( false !== strpos( $content, 'Queen City Bakery' ) ) {
					$queen++;
				}
				if ( false !== strpos( $content, 'Coffea Downtown' ) ) {
					$down++;
				}
				if ( false !== strpos( $content, 'The Source' ) ) {
					$source++;
				}
				if ( false !== strpos( $content, 'Other' ) ) {
					$other++;
				}
			}
		}
		$coffee_numbers['dunn']   = $dunn;
		$coffee_numbers['dawley'] = $dawley;
		$coffee_numbers['louise'] = $louise;
		$coffee_numbers['queen']  = $queen;
		$coffee_numbers['down']   = $down;
		$coffee_numbers['source'] = $source;
		$coffee_numbers['other']  = $other;

		set_transient( 'coffee_stats', $coffee_numbers, 2 * DAY_IN_SECONDS );
		wp_reset_postdata();
	}

	return $coffee_numbers;
}