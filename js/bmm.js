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
    });
    
    // on window resize, run functions.
    $(window).on('resize', function() {
        sizeTabpanes(); 
    });
    
    // sets the width and position of the tabpanes.
    function sizeTabpanes() {
        $('.bmm-sub-menu.bmm-tabpane').each(function () {
            var $menu = $(this).parents('.bmm-sub-menu.bmm-top-level-sub-menu');
            var menuWidth = $menu.width();
            var left = ($(this).offset().left - $menu.offset().left) * -1;

            $(this).css({
                'left': left,
                'width': menuWidth
            });
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