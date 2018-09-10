<?php
/**
 * Mani nav class
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * BMM_Nav class.
 */
class BMM_Nav {

    /**
     * Args
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $args = '';

    /**
     * Menu
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $menu = '';

    /**
     * Menu items
     *
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $menu_items = '';

    /**
     * __construct function.
     *
     * @access public
     * @param array $args (default: array()).
     * @return void
     */
    public function __construct( $args = array() ) {
        $this->args = $args;

        $locations = get_nav_menu_locations();

        $this->menu = wp_get_nav_menu_object( $locations[ $args['theme_location'] ] );
        $this->menu_items = wp_get_nav_menu_items( $this->menu->term_id );

        $this->menu();
    }

    /**
     * Menu.
     *
     * @access public
     * @param string $output (default: '').
     * @return output
     */
    public function menu( $output = '' ) {

        $output .= '<nav id="" class="">';

        foreach ( $this->menu_items as $menu_item ) :
            $output .= '<ul class="bmm-menu">';

            if ( $this->bmm_is_column( $menu_item->post_name ) ) :
                $output .= '<div class="bmm-column"></div>';
                else :
                    $output .= '<li><a href="' . get_permalink( $menu_item->ID ) . '" class="bmm-nav-menu-link">' . $menu_item->title . '</a></li>';
                endif;

                $output .= '</ul>';
            endforeach;
        $output .= '</nav>';

        return $output;
    }

    /**
     * Is Column.
     *
     * @access protected
     * @param string $string (default: '').
     * @return boolean
     */
    protected function bmm_is_column( $string = '' ) {
        $regex = '/column.*/m';

        if ( preg_match( $regex, $string ) ) {
            return true;
        }

        return false;
    }
}
