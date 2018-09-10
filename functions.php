<?php
/**
 * Functions
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * BMM Nav Menu.
 *
 * @access public
 * @param array $args (default: array()).
 * @return html
 */
function bmm_nav_menu( $args = array() ) {
        $default_args = array(
            'theme_location' => '',
            'echo' => true,
        );

        $args = wp_parse_args( $args, $default_args );
        $bmm_nav = new BMM_Nav( $args );

    if ( $args['echo'] ) :
        echo $bmm_nav->menu();
        else :
            return esc_attr( $bmm_nav->menu() );
        endif;
}
