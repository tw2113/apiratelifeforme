<?php
/**
 * Plugin Name: Pirate Book Chest
 * Plugin URI: https://apiratelifefor.me
 * Description: GoodReads, my own version
 * Author: tw2113
 * Author URI: https://michaelbox.net
 * Version: 1.0.0
 */

namespace tw2113\pbc;

function plugins_loaded() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/types-taxes.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/meta-boxes.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/filters.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\plugins_loaded' );
