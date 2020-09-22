                </main>
            </div>
        </div>
        <div class="mmm-footer">
            <footer>
                <?php if(wp_get_nav_menu_items('footer')){ ?>
                    <?php mmm_menu('footer','フッターメニュー'); ?>
                <?php } ?>
            </footer>
        </div>
        <?php wp_footer(); ?>
        <script>objectFitImages();</script>
    </body>
</html>
