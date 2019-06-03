jQuery(function($) {

    // run our functions.
    jQuery(document).ready(function() {
        sizeTabpanes();
        positionSubmenus();
    });

    // on window resize, run functions.
    $(window).on('resize', function() {
        sizeTabpanes();
        positionSubmenus();
    });

    // sets the width and position of the tabpanes.
    function sizeTabpanes() {
        $('.bmm-sub-menu.bmm-tabpane').each(function() {
            var $menu = $(this).parents('.bmm-sub-menu.bmm-top-level-sub-menu');
            var menuWidth = $menu.outerWidth();
            var menuHeight = $menu.outerHeight();
            var menuPadding = parseInt($menu.css('padding'), 10);
            var top = menuHeight - menuPadding;
            var left = ($(this).offset().left - $menu.offset().left) * -1;

            $(this).css({
                'left': left,
                'top': top,
                'width': menuWidth
            });
        });
    }

    // positions the submenus.
    function positionSubmenus() {
        $('.bmm-primary-nav-item.menu-item-has-children').each(function() {
            //var maxMenuLinkWidth = 0;
            var $menu = $('.bmm-menu.nav-menu');
            var $submenu = $(this).children('.bmm-sub-menu');
            //var columns = $submenu.children('.pmm-mega-menu-item').data('pmmcolumns');

            // add offset of not 100% width.
            if ($menu.width() !== $submenu.outerWidth()) {
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

});