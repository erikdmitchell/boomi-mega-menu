<?php
/**
 * Plugin Name:     Boomi Mega Menu
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     boomi-mega-menu
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Boomi_Mega_Menu
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
// Define BMM_PLUGIN_FILE.
if ( ! defined( 'BMM_PLUGIN_FILE' ) ) {
    define( 'BMM_PLUGIN_FILE', __FILE__ );
}
// Include the main PickleCalendar class.
if ( ! class_exists( 'BMM' ) ) {
    include_once dirname( __FILE__ ) . '/class-bmm.php';
}
