<?php
/**
 * General functions
 *
 * @package Boomi_Mega_Menu
 * @since   0.4.0
 */

/**
 * Sanitize array.
 *
 * @access public
 * @param array $data (default: array()).
 * @return array
 */
function boomi_sanitize_array( $data = array() ) {
    if ( ! is_array( $data ) || ! count( $data ) ) {
        return array();
    }

    foreach ( $data as $k => $v ) {
        if ( ! is_array( $v ) && ! is_object( $v ) ) {
            $data[ $k ] = sanitize_text_field( $v );
        }

        if ( is_array( $v ) ) {
            $data[ $k ] = boomi_sanitize_array( $v );
        }
    }

    return $data;
}
