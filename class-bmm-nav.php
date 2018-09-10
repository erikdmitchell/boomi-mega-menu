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
    
    protected $menu_item_parent = '';

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
        
        $this->setup_menu_items();
        
        $this->menu();
        
        add_action('wp_get_nav_menu_items', array($this, 'wp_get_nav_menu_items'), 10, 3);
    }
    
    public function setup_menu_items() {
        foreach ($this->menu_items as $key => $item) :
            // setup classes inc has children
        endforeach;
    }

    /**
     * Menu.
     *
     * @access public
     * @param string $output (default: '').
     * @return output
     */
    public function menu( $output = '' ) {
        $this->menu_item_parent = 0;
/*
  start level
  display el
  end el
  end level
*/        
        $output .= '<nav id="bmm-nav-menu" class="bmm-nav-menu">';
            $output .= '<ul class="bmm-menu">';
                foreach ( $this->menu_items as $menu_item ) :
print_r($menu_item);                
                    if ( $this->bmm_is_column( $menu_item->post_name ) ) :
                        $output .= '<div id="bmm-column-'.$menu_item->ID.'" class="bmm-column parent-'.$menu_item->menu_item_parent.'">';
                            $output .= '<!-- ul, then lis, then end col -->';
                        $output .= '</div>';
                    else :
                        $output .= '<li id="bmm-nav-menu-item-'.$menu_item->ID.'" class="bmm-nav-menu-item parent-'.$menu_item->menu_item_parent.'"><a href="' . $menu_item->url . '" class="bmm-nav-menu-link">' . $menu_item->title . '</a>';
                            $output .= '<!-- submenu check -->';
                        $output .= '</li>';
                    endif;
    
                    
                endforeach;
            $output .= '</ul>';
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
