(function($){

    var urlHash = location.hash;

    if(urlHash) {
        $('body,html').stop().scrollTop(0);
        setTimeout(function(){
            var target = $(urlHash);
            var position = target.offset().top;
            $('body,html').stop().animate( { scrollTop : position }, 500 );
            return false;
        }, 100);
    }

    $('a[href^="#"]').click(function() {
         var href = $(this).attr("href");
         var target = $( href == "#" || href == "" ? 'html' : href );
         var position = target.offset().top;
         $('body,html').stop().animate({ scrollTop:position}, 500);
         return false;
    });

})(jQuery);
