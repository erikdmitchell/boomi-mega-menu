<?php
/**
 * Menu Item Row class
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * BMM_Menu_Item_Row class.
 */
class BMM_Menu_Item_Row {

    /**
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct() {
        add_filter( 'admin_head-nav-menus.php', array( $this, 'add_menu_meta_box' ), 10, 1 );
    }

    /**
     * Add menu metabox.
     *
     * @access public
     * @param mixed $object object.
     * @return void
     */
    public function add_menu_meta_box( $object ) {
        add_meta_box( 'boomi-menu-metabox-row', __( 'Row', 'boomi-mega-menu' ), array( $this, 'menu_meta_box' ), 'nav-menus', 'side', 'default' );
    }

    /**
     * Menu meta box.
     *
     * @access public
     * @return void
     */
    public function menu_meta_box() {
        global $_nav_menu_placeholder, $nav_menu_selected_id;

        $_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

        // setup walker.
        $db_fields = false;

        // If your links will be hieararchical, adjust the $db_fields array below.
        if ( false ) {
            $db_fields = array(
                'parent' => 'parent',
                'id' => 'post_parent',
            );
        }

        $walker = new Walker_Nav_Menu_Checklist( $db_fields );

        // setup columns.
        $row = new stdClass();
        $row->type = 'row';
        $row->title = 'Row';
        $row->object = 'custom';
        $row->url = '#';
        $row->object_id = -1;

        $rows[] = $row;
        ?>
        
        <div id="menu-row-item" class="posttypediv">
            <div id="tabs-panel-menu-rows" class="tabs-panel tabs-panel-active">
                <ul id ="col-item-checklist" class="categorychecklist form-no-clear">
                    <?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $rows ), 0, (object) array( 'walker' => $walker ) ); ?>                     
                </ul>
            </div>
    
            <p class="button-controls">
                <span class="list-controls">
                    <a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#menu-row-item" class="select-all">Select All</a>
                </span>             
    
                <span class="add-to-menu">
                    <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-menu-row-item">
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
    
        <?php
    }
}

new BMM_Menu_Item_Row();
