<?php
/**
 * Plugin Name: YarrRSS
 * Plugin URI: https://apiratelifefor.me
 * Description: Custom RSS feeds
 * Author: tw2113
 * Author URI: https://michaelbox.net
 * Version: 1.0.0
 */

// Autoload things.
use tw2113\YarrRSS\LastFM\LastFM;

$autoloader = dirname( __FILE__ ) . '/vendor/autoload.php';
if ( ! is_readable( $autoloader ) ) {
    // translators: placeholder is the current directory.
    throw new \Exception( sprintf( __( 'Please run `composer install` in the plugin folder "%s" and try activating this plugin again.', 'cc-woo' ), dirname( __FILE__ ) ) );
}
require_once $autoloader;

$lastfm = new LastFM( new WP_Query() );
$lastfm->hooks();


/*function customRSS(){
    add_feed('feedname', 'customRSSFunc');
}

function customRSSFunc(){
    get_template_part('rss', 'feedname');
}*/

/*
 * flush rewrite rules on activation.
 */