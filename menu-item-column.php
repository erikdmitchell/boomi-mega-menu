<?php
/**
 * Menu Item column
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * Add menu metabox column.
 *
 * @access public
 * @param mixed $object object.
 * @return void
 */
function boomi_add_menu_meta_box_column( $object ) {
    add_meta_box( 'boomi-menu-metabox-column', __( 'Columns', 'boomi-mega-menu' ), 'boomi_menu_meta_box_column', 'nav-menus', 'side', 'default' );
}
add_filter( 'admin_head-nav-menus.php', 'boomi_add_menu_meta_box_column', 10, 1 );

/**
 * Menu metabox column.
 *
 * @access public
 * @return void
 */
function boomi_menu_meta_box_column() {
    global $_nav_menu_placeholder, $nav_menu_selected_id;

    $_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

    $max_cols = 6;

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
    for ( $col_num = 1; $col_num <= $max_cols; $col_num++ ) :
        $column = new stdClass();
        $column->type = 'custom';
        $column->title = "Column $col_num";
        $column->object = 'custom';
        $column->url = '#';
        $column->object_id = -1;

        $columns[] = $column;
    endfor;
    ?>
    <div id="menu-col-item" class="posttypediv">
        <div id="tabs-panel-menu-columns" class="tabs-panel tabs-panel-active">
            <ul id ="col-item-checklist" class="categorychecklist form-no-clear">
                <?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $columns ), 0, (object) array( 'walker' => $walker ) ); ?>                     
            </ul>
        </div>

        <p class="button-controls">
            <span class="list-controls">
                <a href="/wordpress/wp-admin/nav-menus.php?page-tab=all&amp;selectall=1#menu-col-item" class="select-all">Select All</a>
            </span>             

            <span class="add-to-menu">
                <input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-menu-col-item">
                <span class="spinner"></span>
            </span>
        </p>
    </div>

    <?php
}
