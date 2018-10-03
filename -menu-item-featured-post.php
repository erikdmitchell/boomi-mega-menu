<?php
/**
 * Menu item featured posts
 *
 * @package Boomi_Mega_Menu
 * @since   0.1.0
 */

/**
 * Add featured post meta box.
 *
 * @access public
 * @param mixed $object (object).
 * @return void
 */
function boomi_add_menu_meta_box_featured_post( $object ) {
    add_meta_box( 'boomi-menu-metabox-featured-post', __( 'Featured Post/Page', 'boomi-mega-menu' ), 'boomi_menu_meta_box_featured_post', 'nav-menus', 'side', 'default' );
}
add_filter( 'admin_head-nav-menus.php', 'boomi_add_menu_meta_box_featured_post', 10, 1 );

/**
 * Feature post meta box.
 *
 * @access public
 * @return void
 */
function boomi_menu_meta_box_featured_post() {
    global $_nav_menu_placeholder, $nav_menu_selected_id;

    $_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

    $post_types = array( 'events', 'solution' );
    $events = array();
    $solutions = array();

    // set tab.
    $current_tab = 'all';

    if ( isset( $_REQUEST['featured-item-tab'] ) && 'events' == $_REQUEST['featured-item-tab'] ) :
        $current_tab = 'events';
    elseif ( isset( $_REQUEST['featured-item-tab'] ) && 'solution' == $_REQUEST['featured-item-tab'] ) :
        $current_tab = 'solution';
    elseif ( isset( $_REQUEST['featured-item-tab'] ) && 'all' == $_REQUEST['featured-item-tab'] ) :
        $current_tab = 'all';
    endif;

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

    $posts = get_posts(
        array(
            'posts_per_page' => -1,
            'post_type' => $post_types,
        )
    );

    // setup posts.
    foreach ( $posts as $post ) :
        $post->classes = array( 'featured-menu-item' );
        $post->type = 'custom';
        $post->object_id = $post->ID;
        $post->title = $post->post_title;
        $post->object = 'custom';
        $post->url = get_permalink( $post->ID );
        $post->attr_title = $post->post_name;

        // we need to reomve the post type to keep the menu type as "custom".
        $post_type = $post->post_type;

        unset( $post->post_type );

        // split up posts.
        switch ( $post->post_type ) :
            case 'events':
                $events[] = $post;
                break;
            case 'solution':
                $solutions[] = $post;
                break;
        endswitch;
    endforeach;

    $removed_args = array( 'action', 'customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab', '_wpnonce' );
    ?>
    
    <div id="posttype-featured-item" class="posttypediv">
        
        <ul id="featured-item-tabs" class="featured-item-tabs add-menu-item-tabs">
            <li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>>
                <a class="nav-tab-link" data-type="tabs-panel-featured-item-all" href="
                <?php
                if ( $nav_menu_selected_id ) {
                    echo esc_url( add_query_arg( 'featured-item-tab', 'all', remove_query_arg( $removed_args ) ) );}
                ?>
#tabs-panel-authorarchive-all">
                    <?php esc_attr_e( 'View All', 'boomi-mega-menu' ); ?>
                </a>
            </li><!-- /.tabs -->

            <li <?php echo ( 'events' == $current_tab ? ' class="tabs"' : '' ); ?>>
                <a class="nav-tab-link" data-type="tabs-panel-featured-item-events" href="
                <?php
                if ( $nav_menu_selected_id ) {
                    echo esc_url( add_query_arg( 'featured-item-tab', 'events', remove_query_arg( $removed_args ) ) );}
                ?>
#tabs-panel-featured-item-events">
                    <?php esc_attr_e( 'Events', 'boomi-mega-menu' ); ?>
                </a>
            </li><!-- /.tabs -->

            <li <?php echo ( 'solutions' == $current_tab ? ' class="tabs"' : '' ); ?>>
                <a class="nav-tab-link" data-type="tabs-panel-featured-item-solutions" href="
                <?php
                if ( $nav_menu_selected_id ) {
                    echo esc_url( add_query_arg( 'featured-item-tab', 'admins', remove_query_arg( $removed_args ) ) );}
                ?>
#tabs-panel-featured-item-solutions">
                    <?php esc_attr_e( 'Solutions', 'boomi-mega-menu' ); ?>
                </a>
            </li><!-- /.tabs -->
        </ul>
    
        <div id="tabs-panel-featured-item-all" class="tabs-panel tabs-panel-view-all <?php echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
            <ul id="featured-item-checklist-all" class="categorychecklist form-no-clear">
            <?php
                echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $posts ), 0, (object) array( 'walker' => $walker ) );
            ?>
            </ul>
        </div><!-- /.tabs-panel -->
    
        <div id="tabs-panel-featured-item-events" class="tabs-panel tabs-panel-view-events <?php echo ( 'admins' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
            <ul id="featured-item-checklist-events" class="categorychecklist form-no-clear">
            <?php
                echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $events ), 0, (object) array( 'walker' => $walker ) );
            ?>
            </ul>
        </div><!-- /.tabs-panel -->  
    
        <div id="tabs-panel-featured-item-solutions" class="tabs-panel tabs-panel-view-solutions <?php echo ( 'admins' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
            <ul id="featured-item-checklist-solutions" class="categorychecklist form-no-clear">
            <?php
                echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $solutions ), 0, (object) array( 'walker' => $walker ) );
            ?>
            </ul>
        </div><!-- /.tabs-panel -->         
        
        <p class="button-controls">
            <span class="list-controls">
                <a href="/wp-admin/nav-menus.php?menu=<?php echo esc_attr($nav_menu_selected_id); ?>&selectall=1#posttype-featured-item" class="select-all">Select All</a>
            </span>
            <span class="add-to-menu">
                <input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'boomi-mega-menu' ); ?>" name="add-post-type-menu-item" id="submit-posttype-featured-item" />
                <span class="spinner"></span>
            </span>
        </p>
    </div>
    <?php
}
