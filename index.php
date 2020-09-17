<?php get_header(); ?>
<!-- スライドショー(javascriptはfooter.phpにあります) -->
<div class="mmm-slider">
	<img src="<?php echo THEME_URL; ?>/images/slide1.jpg" />
	<img src="<?php echo THEME_URL; ?>/images/slide2.jpg" />
	<img src="<?php echo THEME_URL; ?>/images/slide3.jpg" />
</div>
<?php if(have_posts()){ ?>
	<section>
		<?php while(have_posts()){ ?>
			<?php the_post(); ?>
			<?php
				$category = '';
				$post_type = $post->post_type;
				if($post_type === 'post'){
					$terms = mmm_get_the_terms($post->ID);
					foreach($terms['category']['category'] as $term){
						$category = $term['name'];
						break;
					}
				}else{
					$category = get_post_type_object($post_type)->labels->singular_name;
				}
			?>
			<a href="<?php the_permalink(); ?>" class="mmm-post mmm-post--index">
				<?php if( has_post_thumbnail() ){ ?>
					<div class="mmm-post-image">
						<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('medium'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
					</div>
				<?php } ?>
				<div class="mmm-post-text">
					<div class="mmm-post-terms"><p class="mmm-post-cat"><?php echo $category; ?></p></div>
					<time class="mmm-post-date"><?php the_time('Y.m.d'); ?></time>
					<h3 class="mmm-post-title"><?php the_title(); ?></h3>
				</div>
			</a>
		<?php } ?>
	</section>
<?php } ?>
<?php get_footer(); ?>
