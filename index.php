<?php get_header(); ?>
<!-- slideshow example(Java script is at footer.php.) -->
<div class="mmm-slider">
	<img src="<?php echo THEME_URL; ?>/images/slide1.jpg" />
	<img src="<?php echo THEME_URL; ?>/images/slide2.jpg" />
	<img src="<?php echo THEME_URL; ?>/images/slide3.jpg" />
</div>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
		}
	}
?>
<?php get_footer(); ?>
