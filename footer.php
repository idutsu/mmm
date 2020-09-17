                </main>
            </div>
        </div>
        <div class="mmm-footer">
            <footer>
                <div class="mmm-footer-menus">
                <?php if(wp_get_nav_menu_items('footer1')){ ?>
                    <div class="mmm-footer-menu">
                        <?php mmm_menu('footer1','フッターメニュー１'); ?>
                    </div>
                <?php } ?>
                <?php if(wp_get_nav_menu_items('footer2')){ ?>
                    <div class="mmm-footer-menu">
                        <?php mmm_menu('footer2','フッターメニュー２'); ?>
                    </div>
                <?php } ?>
                <?php if(wp_get_nav_menu_items('footer3')){ ?>
                    <div class="mmm-footer-menu">
                        <?php mmm_menu('footer3','フッターメニュー３'); ?>
                    </div>
                <?php } ?>
                <?php if(wp_get_nav_menu_items('footer4')){ ?>
                    <div class="mmm-footer-menu">
                        <?php mmm_menu('footer4','フッターメニュー４'); ?>
                    </div>
                <?php } ?>
                </div>
                <p class="mmm-footer-copyright">copyright</p>
            </footer>
        </div>
        <?php wp_footer(); ?>
        <script>objectFitImages();</script>
        <script>
        //スライドショー実行
        (function($){
            $('.mmm-slider').slick({
                autoplay:true,
                autoplaySpeed:5000,
                arrows:true,
                prevArrow:"<div class='mmm-slider-btn mmm-slider-btn--prev'><span class='mmm-slider-arrow mmm-slider-arrow--prev'></div>",
                nextArrow:"<div class='mmm-slider-btn mmm-slider-btn--next'><span class='mmm-slider-arrow mmm-slider-arrow--next'></div>",
            });
        })(jQuery);
        </script>
    </body>
</html>
