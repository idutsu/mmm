(function($){

    var hamburger_btn  = $('.hamburger');
    var hamburger_menu = $('.mmm-menu.mmm-menu--global');

    hamburger_btn.on('click', function() {
        $(this).toggleClass('active');
        if (hamburger_menu.is(':visible')) {
            hamburger_menu.slideUp('1500');
        } else {
            hamburger_menu.slideDown('1500');
        }
    });

    var submenu_open_area_by_mouseover   = $('.mmm-submenu-hover');
    var submenu_close_area_by_mouseleave = $('.mmm-menu');
    var submenu_open_area_by_click       = $('.mmm-submenu-click');

    var timer = false;

    $(window).on('load resize', function(){

        if (timer !== false) clearTimeout(timer);

        timer = setTimeout(function() {

            submenu_open_area_by_mouseover.off('mouseover');
            submenu_close_area_by_mouseleave.off('mouseleave');
            submenu_open_area_by_click.off('click');

            submenu_open_area_by_click.on('click',function(e){
                e.preventDefault();
                var submenu =  $(this).parent().next('.mmm-submenu');
                SlideToggle( submenu );
            });

            if( window.innerWidth > 768 ){

                submenu_open_area_by_mouseover.on('mouseover', function(){
                    var submenu = $(this).next('.mmm-submenu');
                    SlideDown( submenu );
                });

                submenu_close_area_by_mouseleave.on('mouseleave',function(){
                    var submenu = $(this).find('.mmm-submenu');
                    SlideUp( submenu );
                });


            }

        }, 200);

    });

    function SlideDown(submenu){
        submenu.slideDown('fast');
    };

    function SlideUp(submenu){
        submenu.slideUp('fast');
    };

    function SlideToggle(submenu){
        submenu.slideToggle('fast');
    };

})(jQuery);
