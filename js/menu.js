(function($){

    var w = $(window);

    var hamburger_btn  = $('.mmm-hamburger');
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

    w.on('load resize', function(){

        if(timer !== false) clearTimeout(timer);

        timer = setTimeout(function() {

            submenu_open_area_by_mouseover.off('mouseover');
            submenu_close_area_by_mouseleave.off('mouseleave');
            submenu_open_area_by_click.off('click');

            submenu_open_area_by_click.on('click',function(e){
                e.preventDefault();
                var submenu =  $(this).parent().next('.mmm-submenu');
                $(this).toggleClass('active');
                SlideToggle( submenu );
            });

            if( window.innerWidth > 768 && window.innerWidth <= 768 ){

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

    w.on('scroll', function(){

        if(timer !== false) clearTimeout(timer);

        timer = setTimeout(function() {

            if(w.scrollTop() > 50){
                hamburger_btn.show();
            }else{
                hamburger_btn.hide();
            }

        }, 200);

    });

    function SlideDown(submenu){
        submenu.show();
    };

    function SlideUp(submenu){
        submenu.hide();
    };

    function SlideToggle(submenu){
        if (submenu.is(':visible')) {
            submenu.hide();
        } else {
            submenu.show();
        }
    };

})(jQuery);
