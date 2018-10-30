<?php
/**
 * Main BBM class
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * Final BMM class.
 *
 * @final
 */
final class BMM {

    /**
     * Version
     *
     * @var string
     * @access public
     */
    public $version = '0.2.1';

    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->init();
    }

    /**
     * Define constants function.
     *
     * @access private
     * @return void
     */
    private function define_constants() {
        $this->define( 'BMM_PATH', plugin_dir_path( __FILE__ ) );
        $this->define( 'BMM_URL', plugin_dir_url( __FILE__ ) );
        $this->define( 'BMM_VERSION', $this->version );
    }

    /**
     * Define function.
     *
     * @access private
     * @param mixed $name (name).
     * @param mixed $value (value).
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Includes function.
     *
     * @access public
     * @return void
     */
    public function includes() {
        include_once( BMM_PATH . 'class-bmm-menu-item-column.php' );
        include_once( BMM_PATH . 'class-bmm-menu-item-row.php' );
        include_once( BMM_PATH . 'class-bmm-nav-walker.php' );
    }

    /**
     * Init hooks function.
     *
     * @access private
     * @return void
     */
    private function init_hooks() {}

    /**
     * Init function.
     *
     * @access public
     * @return void
     */
    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts_styles' ) );
    }

    /**
     * Frontend scripts and styles.
     *
     * @access public
     * @return void
     */
    public function frontend_scripts_styles() {
        wp_enqueue_script( 'bmm-scrupt', BMM_URL . 'js/bmm.min.js', array( 'jquery' ), $this->version, false );

        wp_enqueue_style( 'bmm-style', BMM_URL . 'css/bmm.min.css', '', $this->version );
    }

    /**
     * Parse args function.
     *
     * @access public
     * @param mixed $a (array).
     * @param mixed $b (array).
     * @return array
     */
    public function parse_args( &$a, $b ) {
        $a = (array) $a;
        $b = (array) $b;
        $result = $b;

        foreach ( $a as $k => &$v ) {
            if ( is_array( $v ) && isset( $result[ $k ] ) ) {
                $result[ $k ] = $this->parse_args( $v, $result[ $k ] );
            } else {
                $result[ $k ] = $v;
            }
        }

        return $result;
    }

}

/**
 * Main function.
 *
 * @access public
 * @return class
 */
function bmm() {
    return new BMM();
}

// Global for backwards compatibility.
$GLOBALS['bmm'] = bmm();
