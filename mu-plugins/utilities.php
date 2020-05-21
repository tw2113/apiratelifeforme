<?php
/**
 * Plugin Name: Michaelbox Utilities.
 * Plugin URI: http://michaelbox.net
 * Author: Michael Beckwith
 * Version: 2.113
 */

namespace tw2113;

add_action('init', function(){
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_generator');
});

add_action( 'wp_head', function() {
?>
	<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>☠️</text></svg>">
<?php
});

//Remove <p> tags from images
function filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', __NAMESPACE__ . '\filter_ptags_on_images');

// unset some of the default dashboard widgets that are never needed for clients
function remove_dashboard_widgets(){
	global$wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); //non-installed plugin information
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']); //WordPress Blog
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']); //Other WordPress News
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\remove_dashboard_widgets' );

function add_toolbar_items($admin_bar){
	if ( is_admin() ) {
		return;
	}

	$admin_bar->add_menu( [
		'id' => 'plugins',
		'parent' => 'site-name',
		'title' => 'Plugins',
		'href' => admin_url('plugins.php'),
		'meta' => [
			'title' => __('Plugins Admin'),
			'class' => 'plugins_class'
		],
	]);
}
add_action('admin_bar_menu', __NAMESPACE__ . '\add_toolbar_items', 100);

function remove_some_wp_widgets(){
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
}
add_action( 'widgets_init', __NAMESPACE__ . '\remove_some_wp_widgets', 1 );

function security_remove_emails($content) {
	$pattern = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})/i';
	$fix = preg_replace_callback( $pattern, __NAMESPACE__ . '\security_remove_emails_logic', $content );

	return $fix;
}
add_filter( 'the_content', __NAMESPACE__ . '\security_remove_emails', 20 );
add_filter( 'widget_text', __NAMESPACE__ . '\security_remove_emails', 20 );

function security_remove_emails_logic( $result ) {
	return antispambot( $result[1] );
}

function opengraph_on_single() {
	if ( is_single() ) {
		global $post;
		setup_postdata( $post );
		echo
			'<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@tw2113">
<meta name="twitter:creator" content="@tw2113">
<meta name="twitter:url" content="' . get_permalink() . '">
<meta name="twitter:title" content="' . esc_html( get_the_title() ) . '">
<meta name="twitter:description" content="' . wp_trim_words( get_the_content(), 30 ) . '">';

		echo
			'<meta property="og:type" content="article" />
<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />
<meta property="og:url" content="' . get_permalink() . '" />';

		if ( has_post_thumbnail() ) {
			$imgsrc = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			echo '<meta property="og:image" content="' . $imgsrc[0] . '" />';
		}
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\opengraph_on_single' );

function atom_links() {
    $tmpl = '<link rel="%s" type="%s" title="%s" href="%s" />';

    printf(
        $tmpl,
        esc_attr( 'alternate' ),
        esc_attr( 'application/atom+xml' ),
        esc_attr( get_bloginfo( 'name' ) . '&raquo; Atom Feed link'  ),
		get_bloginfo( 'atom_url' )
    );
}
add_action( 'wp_head', __NAMESPACE__ . '\atom_links' );

function add_atom_mime_support( $mimes ) {
	$mimes = array_merge(
		$mimes,
		[
			'atom' => 'application/atom+xml',
		]
	);

	return $mimes;
}
add_filter( 'mime_types', __NAMESPACE__ . '\add_atom_mime_support' );

remove_filter('pre_user_description', 'wp_filter_kses');
add_filter( 'pre_user_description', 'wp_filter_post_kses');

/**
 * Show the updated time for a given post.
 *
 * @param int $post_id Post ID, Optional.
 *
 * @return string
 */
function updated_date( $post_id = 0 ) {
    if ( empty( $post_id ) ) {
        $post_id = get_the_ID();
    }

    $content         = '';
    $u_time          = get_the_time( 'U', $post_id );
    $u_modified_time = get_the_modified_time( 'U', $post_id );
    $u_datetime      = get_the_modified_date( 'c', $post_id );
    if ( $u_modified_time >= $u_time + 86400 ) {
        $updated_date = get_the_modified_time('F jS, Y');
        $updated_time = get_the_modified_time('h:i a');
        $content     .= '<time class="entry-date updated dt-updated" datetime="' . $u_datetime . '">Last updated on '. $updated_date . ' at '. $updated_time .'</time>';
    }

    return $content;
}

function custom_coffee_checkin_counts( $data = [] ) {
    $coffee_count = wp_count_posts( 'coffee_checkins' );
    $url          = get_bloginfo( 'url' ) . '/coffee/';
    $data[]       = "<a href='{$url}'>Coffee checkins: {$coffee_count->publish}</a>";

    return $data;
}
add_filter( 'dashboard_glance_items', __NAMESPACE__ . '\custom_coffee_checkin_counts' );

function custom_bookmarks_counts( $data = [] ) {
	$bookmarks_count = wp_count_posts( 'bookmarks' );
	$url             = get_bloginfo( 'url' ) . '/bookmarks/';
	$data[]          = "<a href='{$url}'>Bookmarks: {$bookmarks_count->publish}</a>";

	return $data;
}
add_filter( 'dashboard_glance_items', __NAMESPACE__ . '\custom_bookmarks_counts' );

function custom_videos_counts( $data = [] ) {
	$video_count = wp_count_posts( 'music_video' );
	$url         = get_bloginfo( 'url' ) . '/video/';
	$data[]      = "<a href='{$url}'>Music videos: {$video_count->publish}</a>";

	return $data;
}
add_filter( 'dashboard_glance_items', __NAMESPACE__ . '\custom_videos_counts' );

function remove_webmentions_from_main_feed( $query ) {
    if ( ! $query->is_main_query() ) {
		return $query;
	}

    if ( ! $query->is_home() ) {
        return $query;
    }

	$query->set( 'tag', '-75' );
}
add_filter( 'pre_get_posts', __NAMESPACE__ . 'remove_webmentions_from_main_feed' );