<?php
/**
 * Autonomie functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package Autonomie
 * @since Autonomie 1.0.0
 */

if ( ! function_exists( 'autonomie_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which runs
	 * before the init hook. The init hook is too late for some features, such as indicating
	 * support post thumbnails.
	 *
	 * To override autonomie_setup() in a child theme, add your own autonomie_setup to your child theme's
	 * functions.php file.
	 */
	function autonomie_setup() {
		$content_width = 900;

		/**
		 * Make theme available for translation
		 * Translations can be filed in the /languages/ directory
		 * If you're building a theme based on autonomie, use a find and replace
		 * to change 'autonomie' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'autonomie', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// This theme uses post thumbnails
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( $content_width, 9999 ); // Unlimited height, soft crop

		// Register custom image size for image post formats.
		add_image_size( 'autonomie-image-post', $content_width, 1250 );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			)
		);

		add_theme_support( 'align-wide' );

		add_theme_support( 'editor-color-palette', array(
			array(
				'name'  => __( 'Blue', 'autonomie' ),
				'slug'  => 'blue',
				'color' => '#0073aa',
			),
			array(
				'name'  => __( 'Lighter blue', 'autonomie' ),
				'slug'  => 'lighter-blue',
				'color' => '#229fd8',
			),
			array(
				'name'  => __( 'Blue jeans', 'autonomie' ),
				'slug'  => 'blue-jeans',
				'color' => '#5bc0eb',
			),
			array(
				'name'  => __( 'Orioles orange', 'autonomie' ),
				'slug'  => 'orioles-orange',
				'color' => '#fa5b0f',
			),
			array(
				'name'  => __( 'USC gold', 'autonomie' ),
				'slug'  => 'usc-gold',
				'color' => '#ffcc00',
			),
			array(
				'name'  => __( 'Gargoyle gas', 'autonomie' ),
				'slug'  => 'gargoyle-gas',
				'color' => '#fde74c',
			),
			array(
				'name'  => __( 'Yellow', 'autonomie' ),
				'slug'  => 'yellow',
				'color' => '#fff9c0',
			),
			array(
				'name'  => __( 'Android green', 'autonomie' ),
				'slug'  => 'android-green',
				'color' => '#9bc53d',
			),
			array(
				'name'  => __( 'White', 'autonomie' ),
				'slug'  => 'white',
				'color' => '#fff',
			),
			array(
				'name'  => __( 'Very light gray', 'autonomie' ),
				'slug'  => 'very-light-gray',
				'color' => '#eee',
			),
			array(
				'name'  => __( 'Very dark gray', 'autonomie' ),
				'slug'  => 'very-dark-gray',
				'color' => '#444',
			)
		) );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => __( 'Primary Menu', 'autonomie' ),
		) );

		// Add support for the Aside, Gallery Post Formats...
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'gallery',
				'link',
				'status',
				'image',
				'video',
				'audio',
				'quote',
			)
		);

		// Nicer WYSIWYG editor
		add_theme_support( 'editor-styles' );
		add_editor_style( 'css/editor-style.css' );

		add_theme_support( 'responsive-embeds' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		// custom logo support
		add_theme_support(
			'custom-logo',
			array(
				'height' => 30,
				'width'  => 30,
			)
		);

		// This theme supports a custom header
		$custom_header_args = array(
			'width'       => 1250,
			'height'      => 600,
			'header-text' => true,
		);
		add_theme_support( 'custom-header', $custom_header_args );

		/**
		 * Draw attention to supported WebSemantics
		 */
		add_theme_support( 'microformats2' );
		add_theme_support( 'microformats' );
		add_theme_support( 'microdata' );
		add_theme_support( 'indieweb' );

		//add_theme_support( 'amp' );
	}
endif; // autonomie_setup

/**
 * Tell WordPress to run autonomie_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'autonomie_setup' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function autonomie_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%1$s" />', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'autonomie_pingback_header' );

/**
 * Adds a rel-feed if the main page is not a list of posts
 */
function autonomie_publisher_feed_header() {
	if ( is_front_page() && 0 !== (int) get_option( 'page_for_posts', 0 ) ) {
		printf( PHP_EOL . '<link rel="feed" type="text/html" href="%1$s" title="%2$s" />' . PHP_EOL, esc_url( get_post_type_archive_link( 'post' ) ), __( 'POSH Feed', 'autonomie' ) );
	}
}
add_action( 'wp_head', 'autonomie_publisher_feed_header' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function autonomie_content_width() {
	$content_width = 900;

	$GLOBALS['content_width'] = apply_filters( 'autonomie_content_width', $content_width );
}
add_action( 'after_setup_theme', 'autonomie_content_width', 0 );

/**
 * Set the default maxwith for the embeds
 */
function autonomie_embed_defaults() {
	return array(
		'width'  => 900,
		'height' => 600,
	);
}
add_filter( 'embed_defaults', 'autonomie_embed_defaults' );

/**
 * Set the default with for the embeds
 * Fixes issues with Vimeo
 */
function autonomie_oembed_fetch_url( $provider ) {
	$provider = add_query_arg( 'width', 900, $provider );
	$provider = add_query_arg( 'height', 600, $provider );

	return $provider;
}
add_filter( 'oembed_fetch_url', 'autonomie_oembed_fetch_url', 99 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function autonomie_page_menu_args( $args ) {
	$args['show_home'] = true;

	return $args;
}
add_filter( 'wp_page_menu_args', 'autonomie_page_menu_args' );

if ( ! function_exists( 'autonomie_enqueue_scripts' ) ) :
	/**
	 * Enqueue theme scripts
	 *
	 * @uses wp_enqueue_scripts() To enqueue scripts
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_enqueue_scripts() {
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

		wp_enqueue_script( 'autonomie-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );
		wp_enqueue_script( 'autonomie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1.0.0', true );

		wp_enqueue_style( 'dashicons' );

		// Loads our main stylesheet.
		wp_enqueue_style( 'autonomie-style', get_stylesheet_uri(), array( 'dashicons' ) );
		wp_enqueue_style( 'autonomie-print-style', get_stylesheet_directory_uri() . '/css/print.css', array( 'autonomie-style' ), '1.0.0', 'print' );
		wp_enqueue_style( 'autonomie-narrow-style', get_stylesheet_directory_uri() . '/css/narrow-width.css', array( 'autonomie-style' ), '1.0.0', '(max-width: 800px)' );
		wp_enqueue_style( 'autonomie-default-style', get_stylesheet_directory_uri() . '/css/default-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 800px)' );
		wp_enqueue_style( 'autonomie-wide-style', get_stylesheet_directory_uri() . '/css/wide-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 1000px)' );

		wp_localize_script(
			'autonomie',
			'vars',
			array(
				'template_url' => get_template_directory_uri(),
			)
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

			wp_add_inline_style( 'autonomie-style', $css );
		}
	}
endif;

add_action( 'wp_enqueue_scripts', 'autonomie_enqueue_scripts' );

if ( ! function_exists( 'autonomie_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own autonomie_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;

		switch ( $comment->comment_type ):
			case 'pingback':
			case 'trackback':
			case 'webmention':
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
				<div class="edit-link"><?php edit_comment_link( __( 'Edit', 'autonomie' ), ' ' ); ?></div>
				<div class="comment-content p-summary p-name" itemprop="text name description"><?php comment_text(); ?></div>
				<footer class="comment-meta commentmetadata">
					<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
						<?php printf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
					</address>
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
						<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'autonomie' ), get_comment_date(), get_comment_time() );
						?>
					</time></a>
				</footer>
			</article>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<article id="comment-<?php comment_ID(); ?>" class="comment <?php $comment->comment_type; ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
				<div class="edit-link"><?php edit_comment_link( __( 'Edit', 'autonomie' ), ' ' ); ?></div>
				<footer class="comment-meta commentmetadata">
					<address class="comment-author p-author author vcard hcard h-card" itemprop="creator" itemscope itemtype="http://schema.org/Person">
						<?php echo get_avatar( $comment, 40 ); ?>
						<?php printf( '<cite class="fn p-name" itemprop="name">%s</cite>', get_comment_author_link() ); ?>
					</address><!-- .comment-author .vcard -->
					<?php if ( '0' == $comment->comment_approved ) : ?>
						<em><?php _e( 'Your comment is awaiting moderation.', 'autonomie' ); ?></em>
					<?php endif; ?>

					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time class="updated published dt-updated dt-published" datetime="<?php comment_time( 'c' ); ?>" itemprop="dateCreated">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'autonomie' ), get_comment_date(), get_comment_time() );
					?>
					</time></a>
				</footer>

				<div class="comment-content e-content p-summary p-name" itemprop="text name description"><?php comment_text(); ?></div>

				<div class="reply">
					<?php
					comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
					?>
				</div><!-- .reply -->
			</article><!-- #comment-## -->
		<?php
				break;
		endswitch;
	}
endif; // ends check for autonomie_comment()

/**
 * All template functions
 */
require( get_template_directory() . '/includes/template-functions.php' );

/**
 * Widget handling
 */
require( get_template_directory() . '/includes/widgets.php' );

/**
 * Adds the featured image functionality
 */
require( get_template_directory() . '/includes/featured-image.php' );

/**
 * Adds some awesome websemantics like microformats(2) and microdata
 */
require( get_template_directory() . '/includes/semantics.php' );

/**
 * Adds back compat handling for older WP versions
 */
require( get_template_directory() . '/includes/compat.php' );

/**
 * Compatibility with other plugins, mostly IndieWeb related
 */

if ( defined( 'SYNDICATION_LINKS_VERSION' ) ) {
	/**
	 * Adds Indieweb Syndcation Links
	 * if github.com/dshanske/syndication-links is activated
	 */
	require( get_template_directory() . '/integrations/syndication-links.php' );
}

if ( class_exists('Post_Kinds_Plugin') ) {
	require( get_template_directory() . '/integrations/post-kinds.php' );
}

if ( class_exists('\Activitypub\Activitypub') ) {
	require( get_template_directory() . '/integrations/activitypub.php' );
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and Autonomie.
 */
