jQuery(function ($) {

    // setup tabpanes classes
/*
    $('.pmm-mega-sub-menu-tabpane').each(function () {        
        $(this).parent().addClass('has-tabpane');
    });
*/
    
    // run our functions.
    jQuery(document).ready(function() {
        sizeTabpanes();
        positionSubmenus();        
    });
    
    // on window resize, run functions.
    $(window).on('resize', function() {
        sizeTabpanes(); 
        //positionSubmenus();
    });
    
    // sets the width and position of the tabpanes.
    function sizeTabpanes() {
        $('.bmm-sub-menu.bmm-tabpane').each(function () {
            var $menu = $(this).parents('.bmm-sub-menu.bmm-top-level-sub-menu');
            var menuWidth = $menu.width();
            var left = ($(this).offset().left - $menu.offset().left) * -1;
    
            $(this).width(menuWidth);
            $(this).css('left', left);
        });        
    }
    
    // positions the submenus.
    function positionSubmenus() {
        $('.pmm-mega-menu-primary-nav-item.pmm-mega-menu-item-has-children').each(function () {
            var maxMenuLinkWidth = 0;
            var $menu = $('.pmm-mega-menu.nav-menu');
            var $submenu = $(this).children('.pmm-mega-sub-menu');
            var columns = $submenu.children('.pmm-mega-menu-item').data('pmmcolumns');
    
            // add offset of not 100% width.
            if ($menu.width() != $submenu.outerWidth()) {
                var left = $(this).position().left;
                var totalWidth = $submenu.outerWidth() + Math.ceil($(this).position().left);
    
                // if the submenu would be pushed to wide, we move it back
                if (totalWidth > $menu.width()) {
                    left = left - (totalWidth - $menu.width());
                }
    
                $submenu.css('left', left);
            }
        });        
    }
    
    // removes active class (set for onload) from a column.
/*
    $('.pmm-mega-menu-column').on('mouseleave', function() {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');   
        }        
    });
*/

});