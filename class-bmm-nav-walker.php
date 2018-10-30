<?php
/**
 * Walker class
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * BMM_Nav_Walker class.
 */
class BMM_Nav_Walker extends Walker_Nav_Menu {

    /**
     * Starts the list before the elements are added.
     *
     * @since WP 3.0.0
     *
     * @see Walker_Nav_Menu::start_lvl()
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );

        // Default class to add to the file.
        $classes = array( 'bmm-sub-menu' );

        if ( 0 === $depth ) :
            $classes[] = 'bmm-top-level-sub-menu';
        endif;

        if ( 3 == $depth ) :
            $classes[] = 'bmm-tabpane';
        endif;

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @since WP 4.8.0
         *
         * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args    An object of `wp_nav_menu()` arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= "{$n}{$indent}<ul$class_names>{$n}";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::end_lvl()
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );
        $output .= "$indent</ul>{$n}";
    }

    /**
     * Starts the element output.
     *
     * @since WP 3.0.0
     * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
     *
     * @see Walker_Nav_Menu::start_el()
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        // Initialize some holder variables to store specially handled item wrappers and icons.
        $linkmod_classes = array();
        $icon_classes = array();

        /**
         * Get an updated $classes array without linkmod or icon classes.
         *
         * NOTE: linkmod and icon class arrays are passed by reference and
         * are maybe modified before being used later in this function.
         */
        $classes = $this->separate_linkmods_and_icons_from_classes( $classes, $linkmod_classes, $icon_classes, $depth );

        // Join any icon classes plucked from $classes into a string.
        $icon_class_string = join( ' ', $icon_classes );

        /**
         * Filters the arguments for a single nav menu item.
         *
         *  WP 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        // Add .active classes when needed.
        if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
            $classes[] = 'active';
        }

        // Add some additional default classes to the item.
        $classes[] = 'menu-item-' . $item->ID;

        // add class to primary nav.
        if ( 0 === $depth ) :
            $classes[] = 'bmm-primary-nav-item';
        endif;

        // add class if has description.
        if ( ! empty( $item->description ) ) :
            $classes[] = 'has-description';
        endif;

        // Setup columns if need be.
        $classes = $this->setup_column_classes( $classes, $item, $args, $depth );

        // Allow filtering the classes.
        $classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

        // Form a string of classes in format: class="class_names".
        $class_names = join( ' ', $classes );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since WP 3.0.1
         * @since WP 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        // initialize array for holding the $atts for the link item.
        $atts = array();

        // Set title from item to the $atts array - if title is empty then default to item title.
        if ( empty( $item->attr_title ) ) {
            $atts['title'] = ! empty( $item->title ) ? strip_tags( $item->title ) : '';
        } else {
            $atts['title'] = $item->attr_title;
        }

        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        $atts['class'] = 'bmm-menu-link'; // set class for link. -- CHECK when adding icons.

        // update atts of this item based on any custom linkmod classes.
        $atts = $this->update_atts_for_linkmod_type( $atts, $linkmod_classes );

        // Allow filtering of the $atts array before using it.
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        // Build a string of html containing all the atts for the item.
        $attributes = '';

        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /**
         * Set a typeflag to easily test if this is a linkmod or not.
         */
        $linkmod_type = $this->get_linkmod_type( $linkmod_classes );

        /**
         * Set a typeflag to easily test if this is a column or not.
         */
        $is_column = $this->is_column( $classes );

        /**
         * Set a typeflag to easily test if this is a row or not.
         */
        $is_row = $this->is_row( $classes );

        /**
         * START appending the internal item contents to the output.
         */
        $item_output = isset( $args->before ) ? $args->before : '';

        /**
         * This is the start of the internal nav item. Depending on what kind of linkmod we have we may need different wrapper elements.
         */
        if ( $is_column || $is_row ) {
            $item_output .= '';
        } else {
            // With no link mod type set this must be a standard <a> tag.
            $item_output .= '<a' . $attributes . '>';
        }

        /**
         * Initiate empty icon var, then if we have a string containing any
         * icon classes form the icon markup with an <i> element. This is
         * output inside of the item before the $title (the link text).
         */
        $icon_html = '';

        if ( ! empty( $icon_class_string ) ) {
            // append an <i> with the icon classes to what is output before links.
            $icon_before = '';
            $icon_after = '';

            if ( 'icon-wrapper' === $linkmod_type ) :
                $icon_before = '<div class="bmm-icon-wrapper">';
                $icon_after = '</div>';
            endif;

            $icon_html = $icon_before . '<i class="' . esc_attr( $icon_class_string ) . ' bmm-icon" aria-hidden="true"></i>' . $icon_after;

            $icon_html = apply_filters( 'bmm_icon_class_html', $icon_html, $item, $icon_before, $icon_after, $icon_class_string );
        } elseif ( 'grid-icon' === $linkmod_type ) {
            $icon_classes = array( 'grid-icon-image' );
            $style = '';

            if ( has_post_thumbnail( $item->object_id ) ) :
                $style = 'style="background-image:url(' . get_the_post_thumbnail_url( $item->object_id ) . ')"';
            else :
                $icon_classes[] = 'empty';
            endif;

            $icon_html = '<div class="' . implode( ' ', $icon_classes ) . '" ' . $style . '></div>';

            $icon_html = apply_filters( 'bmm_grid_icon_linkmod_html', $icon_html, $item, $icon_classes, $style );
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since WP 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        // If col or row, no title/output.
        if ( $is_column || $is_row ) :
            $title = '';
        endif;

        // tweak for grid icons.
        if ( 'grid-icon' === $linkmod_type ) {
            $title = '<div class="grid-icon-title">' . $title . '</div>';
        }

        // description setup.
        if ( empty( $item->description ) ) :
            $description = '';
        else :
            $description = '<div class="menu-item-description">' . $item->description . '</div>';
        endif;

        // Put the item contents into $output.
        $item_output .= isset( $args->link_before ) ? $args->link_before . $icon_html . $title . $description . $args->link_after : '';

        /**
         * This is the end of the internal nav item. We need to close the correct element depending on the type of link or link mod.
         */
        if ( $is_column || $is_row ) {
            $item_output .= '';
        } else {
            // With no link mod type set this must be a standard <a> tag.
            $item_output .= '</a>';
        }
        /**
         * END appending the internal item contents to the output.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

    }

    /**
     * Find any custom linkmod or icon classes and store in their holder arrays then remove them from the main classes array.
     *
     * Supported linkmods: .icon-wrapper
     * Supported iconsets: Font Awesome 4/5, Glypicons, Font Boomi
     *
     * NOTE: This accepts the linkmod and icon arrays by reference.
     *
     * @since 4.0.0
     *
     * @param array   $classes         an array of classes currently assigned to the item.
     * @param array   $linkmod_classes an array to hold linkmod classes.
     * @param array   $icon_classes    an array to hold icon classes.
     * @param integer $depth           an integer holding current depth level.
     *
     * @return array  $classes         a maybe modified array of classnames.
     */
    private function separate_linkmods_and_icons_from_classes( $classes, &$linkmod_classes, &$icon_classes, $depth ) {
        // Loop through $classes array to find linkmod or icon classes.
        foreach ( $classes as $key => $class ) {
            // If any special classes are found, store the class in it's
            // holder array and and unset the item from $classes.
            if ( preg_match( '/^icon-wrapper/i', $class ) ) {
                // Test for .icon-wrapper.
                $linkmod_classes[] = $class;
                unset( $classes[ $key ] );
            } elseif ( preg_match( '/^grid-icon/i', $class ) ) {
                // Test for .grid-icon.
                $linkmod_classes[] = $class;
                // unset( $classes[ $key ] );.
            } elseif ( preg_match( '/^fa-(\S*)?|^fa(s|r|l|b)?(\s?)?$/i', $class ) ) {
                // Font Awesome.
                $icon_classes[] = $class;
                unset( $classes[ $key ] );
            } elseif ( preg_match( '/^glyphicon-(\S*)?|^glyphicon(\s?)$/i', $class ) ) {
                // Glyphicons.
                $icon_classes[] = $class;
                unset( $classes[ $key ] );
            } elseif ( preg_match( '/^fb-(\S*)?|^fb(s|r|l|b)?(\s?)?$/i', $class ) ) {
                // Font boomi.
                $icon_classes[] = $class;
                unset( $classes[ $key ] );
            }
        }

        return $classes;
    }

    /**
     * Return a string containing a linkmod type and update $atts array
     * accordingly depending on the decided.
     *
     * @since 4.0.0
     *
     * @param array $linkmod_classes array of any link modifier classes.
     *
     * @return string empty for default, a linkmod type string otherwise.
     */
    private function get_linkmod_type( $linkmod_classes = array() ) {
        $linkmod_type = '';

        // Loop through array of linkmod classes to handle their $atts.
        if ( ! empty( $linkmod_classes ) ) {
            foreach ( $linkmod_classes as $link_class ) {
                if ( ! empty( $link_class ) ) {
                    // check for special class types and set a flag for them.
                    if ( 'icon-wrapper' === $link_class || 'grid-icon' === $link_class ) {
                        $linkmod_type = $link_class;
                    }
                }
            }
        }
        return $linkmod_type;
    }

    /**
     * Update the attributes of a nav item depending on the limkmod classes.
     *
     * @since 4.0.0
     *
     * @param array $atts            array of atts for the current link in nav item.
     * @param array $linkmod_classes an array of classes that modify link or nav item behaviors or displays.
     *
     * @return array                 maybe updated array of attributes for item.
     */
    private function update_atts_for_linkmod_type( $atts = array(), $linkmod_classes = array() ) {
        if ( ! empty( $linkmod_classes ) ) {
            foreach ( $linkmod_classes as $link_class ) {
                if ( ! empty( $link_class ) ) {
                    if ( 'icon-wrapper' === $link_class ) {
                        return $atts;
                        // $atts['class'] .= ' has-icon-wrapper';.
                    }
                }
            }
        }

        return $atts;
    }

    /**
     * Returns the correct opening element and attributes for a linkmod.
     *
     * @since 4.0.0
     *
     * @param string $linkmod_type a sting containing a linkmod type flag.
     * @param string $attributes   a string of attributes to add to the element.
     *
     * @return string              a string with the openign tag for the element with attribibutes added.
     */
    private function linkmod_element_open( $linkmod_type, $attributes = '' ) {
        $output = '';

        if ( 'icon-wrapper' === $linkmod_type ) {
            $output .= '<span class="icon-wrapper"' . $attributes . '>';
        }

        return $output;
    }

    /**
     * Return the correct closing tag for the linkmod element.
     *
     * @since 4.0.0
     *
     * @param string $linkmod_type a string containing a special linkmod type.
     *
     * @return string              a string with the closing tag for this linkmod type.
     */
    private function linkmod_element_close( $linkmod_type ) {
        $output = '';

        if ( 'icon-wrapper' === $linkmod_type ) {
            // For a header use a span with the .h6 class instead of a real
            // header tag so that it doesn't confuse screen readers.
            $output .= '</span>';
        }

        return $output;
    }

    /**
     * Setup column classes.
     *
     * @access private
     * @param mixed    $classes an array of classes currently assigned to the item.
     * @param WP_Post  $item   Menu item data object.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @return array
     */
    private function setup_column_classes( $classes, $item, $args, $depth ) {
        if ( ! empty( $classes ) ) {
            foreach ( $classes as $link_class ) {
                if ( ! empty( $link_class ) ) {
                    // check for special class types and set a flag for them.
                    if ( 'column-menu-item' === $link_class ) {
                        $classes[] = 'total-columns-' . $this->get_total_columns( $args, $item );
                    }
                }
            }
        }

        return $classes;
    }

    /**
     * Get total columns.
     *
     * @access private
     * @param mixed $args An object of wp_nav_menu() arguments.
     * @param mixed $item Menu item data object.
     * @return int
     */
    private function get_total_columns( $args, $item ) {
        $item_parent_id = $item->menu_item_parent;
        $menu_items = wp_get_nav_menu_items( $args->menu->term_id );
        $sub_menu_items = array();
        $columns = array();

        // get sub nav items.
        foreach ( $menu_items as $menu_item ) :
            if ( $menu_item->menu_item_parent == $item_parent_id ) :
                $sub_menu_items[] = $menu_item;
            endif;
        endforeach;

        if ( empty( $sub_menu_items ) ) {
            return 0;
        }

        // get columns.
        foreach ( $sub_menu_items as $item ) :
            $columns[] = $item->ID;
        endforeach;

        $columns = array_unique( $columns );

        return count( $columns );
    }

    /**
     * Is column.
     *
     * @access private
     * @param mixed $classes an array of classes currently assigned to the item.
     * @return boolean
     */
    private function is_column( $classes ) {
        if ( ! empty( $classes ) ) {
            foreach ( $classes as $link_class ) {
                if ( ! empty( $link_class ) ) {
                    // check for special class types and set a flag for them.
                    if ( 'column-menu-item' === $link_class ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Is Row.
     *
     * @access private
     * @param mixed $classes an array of classes currently assigned to the item.
     * @return boolean
     */
    private function is_row( $classes ) {
        if ( ! empty( $classes ) ) {
            foreach ( $classes as $link_class ) {
                if ( ! empty( $link_class ) ) {
                    // check for special class types and set a flag for them.
                    if ( 'row-menu-item' === $link_class ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
