</main>
<footer>
    copyright
    <?php //mmm_menu('footer'); ?>
</footer>
<?php wp_footer(); ?>
<script>objectFitImages();</script>
<script>
(function($){
    $('.mmm-slider').slick({
        autoplay:true,
        autoplaySpeed:5000,
        arrows:true,
        prevArrow:"<span class='mmm-slider-arrow mmm-slider-arrow--prev'>",
        nextArrow:"<span class='mmm-slider-arrow mmm-slider-arrow--next'>",
    });
})(jQuery);
</script>
</body>
</html>
