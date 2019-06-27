jQuery(function($) {

    // run our functions.
    $(document).ready(function() {
        //console.log('doc');
        sizeTabpanes();
        positionSubmenus();
    });

    // on window resize, run functions.
    $(window).on('resize', function() {
        //console.log('resize');
        sizeTabpanes();
        positionSubmenus();
    });

    // sets the width and position of the tabpanes.
    function sizeTabpanes() {
        //console.log('sizeTabpanes');                 
        $('.bmm-sub-menu.bmm-tabpane').each(function() {
            //console.log('------------------');            
            var $menu = $(this).parents('.bmm-sub-menu.bmm-top-level-sub-menu');
            var menuWidth = $menu.outerWidth();
            var menuHeight = $menu.outerHeight();
            var menuPadding = parseInt($menu.css('padding'), 10);
            var tabpaneOffsetLeft = $(this).offset().left;
            var menuOffsetLeft = $menu.offset().left;
            var top = menuHeight - menuPadding;
            var left = (tabpaneOffsetLeft - menuOffsetLeft);

            // fixes potential positive left (do we need?).            
            if (tabpaneOffsetLeft > menuOffsetLeft) {
                left = left * -1;
            }

            //console.log(left);

            // prevents setting left to 0 on weird resize issue.
            if ((tabpaneOffsetLeft === menuOffsetLeft) && (tabpaneOffsetLeft !== 0 && menuOffsetLeft !== 0)) {
                return;
            }
            //console.log($menu);
            //console.log('menu w: ' + menuWidth + ' menu h: ' + menuHeight + ' menu pad: ' + menuPadding);
            //console.log('menu w: ' + menuWidth);
            //console.log('tabpane offset: ' + tabpaneOffsetLeft + ' menu offset: ' + menuOffsetLeft);
            //console.log('top: ' + top + ' left: ' + left);
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