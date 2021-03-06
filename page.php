<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<div class="mmm-post mmm-post--single">
				<div class="mmm-post__content mmm-post--single__content">
					<h1 class="mmm-post__title mmm-post--single__title"><?php the_title(); ?></h1>
					<?php if( has_post_thumbnail() ){ ?>
						<div class="mmm-post__image mmm-post--single__image">
							<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('full'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
						</div>
					<?php } ?>
					<?php if( get_the_content() === "" ){ ?>
 					   <p>※現在準備中です</p>
 				   <?php }else{ ?>
 					   <?php the_content(); ?>
 				   <?php } ?>
				</div>
			</div>
			<?php
		}
	}else{
		?>
		<p>※記事がありません</p>
		<?php
	}
?>
<?php mmm_related_pages( get_the_ID() ); ?>
<?php get_template_part('templates/after-content'); ?>
<?php get_footer(); ?>
