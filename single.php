<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<div class="mmm-post mmm-post--single">
				<h2 class="mmm-post-title"><?php the_title(); ?></h2>
				<time class="mmm-post-date"><?php the_time('Y.m.d'); ?></time>
				<?php
					$terms = mmm_get_the_terms( get_the_ID() );
					if( $terms ){
				?>
					<div class="mmm-post-terms">
						<?php if( $terms['category'] ){ ?>
							<?php foreach( $terms['category'] as $tax => $categories ){ ?>
								<?php foreach( $categories as $category ){ ?>
									<a href="<?php echo $category['link']; ?>" class="mmm-post-cat mmm-post-cat--<?php echo $tax; ?>"><?php echo $category['name']; ?></a>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php if( $terms['tag'] ){ ?>
							<?php foreach( $terms['tag'] as $tax => $tags ){ ?>
								<?php foreach( $tags as $tag ){ ?>
									<a href="<?php echo $category['link']; ?>" class="mmm-post-tag mmm-post-tag--<?php echo $tax; ?>"><?php echo $tag['name']; ?></a>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if( has_post_thumbnail() ){ ?>
					<div class="mmm-post-image">
						<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('full'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
					</div>
				<?php } ?>
				<div class="mmm-post-content">
				    <?php the_content(); ?>
				</div>
				<?php mmm_related_posts( get_the_ID() ); ?>
			</div>
			<?php
		}
	}
?>
<?php get_template_part('template/after-content'); ?>
<?php get_footer(); ?>
