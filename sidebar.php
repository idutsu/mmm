<?php if(wp_get_nav_menu_items('sidebar')){ ?>
    <div class="mmm-aside">
        <aside>
            <?php mmm_menu('sidebar','サイドメニュー'); ?>
        </aside>
    </div>
<?php } ?>