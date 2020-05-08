<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<div class="mmm-post mmm-post--signle">
				<h2 class="mmm-post-title"><?php the_title(); ?></h2>
				<time class="mmm-post-date"><?php the_time('Y.m.d'); ?></time>
				<?php if( has_post_thumbnail() ){ ?>
					<div class="mmm-post-image">
						<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('full'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
					</div>
				<?php } ?>
				<div class="mmm-post-content">
				    <?php the_content(); ?>
				</div>
				<?php mmm_related_pages( get_the_ID() ); ?>
			</div>
			<?php
		}
	}
?>
<?php get_template_part('template/after-content'); ?>
<?php get_footer(); ?>
