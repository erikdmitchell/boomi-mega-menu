<?php
/**
 * Plugin Name:     Boomi Mega Menu
 * Plugin URI:      https://boomi.com
 * Description:     Allows creation of a mega menu using the built in menu builder.
 * Author:          Erik Mitchell
 * Author URI:      http://erikmitchell.net
 * Text Domain:     boomi-mega-menu
 * Domain Path:     /languages
 * Version:         0.1.1
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
