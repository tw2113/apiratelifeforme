<?php

namespace tw2113;

function scripts_styles() {
	wp_enqueue_style( 'autonomie-parent', get_template_directory_uri() . '/style.css', [ 'dashicons' ] );
	wp_enqueue_style( 'autonomie-child', get_stylesheet_directory_uri() . '/style.css', [ 'dashicons', 'autonomie-parent' ] );

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
	echo wpautop( 'We are wolves of the sea.' );
}
add_action( 'autonomie_credits', __NAMESPACE__ . '\credits' );
