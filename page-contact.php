<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo THEME_URL; ?>/css/form.css" />
<div class="mmm-post mmm-post--single">
    <h2 class="mmm-post__title mmm-post--single__title">お問い合わせフォーム</h2>
    <div class="mmm-post__content mmm-post--single__content">
        <?php require_once('templates/form.php'); ?>
    </div>
</div>
<?php get_template_part('templates/after-content'); ?>
<?php get_footer(); ?>
