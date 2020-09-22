<?php get_header(); ?>
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
					<div class="mmm-post__image mmm-post--index__image">
						<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('medium'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
					</div>
				<?php } ?>
				<div class="mmm-post__text mmm-post--index__text">
					<div class="mmm-post__terms mmm-post--index__terms"><p class="mmm-post-cat mmm-post--index__cat"><?php echo $category; ?></p></div>
					<time class="mmm-post__date mmm-post--index__date"><?php the_time('Y.m.d'); ?></time>
					<h3 class="mmm-post__title mmm-post--index__title"><?php the_title(); ?></h3>
				</div>
			</a>
		<?php } ?>
	</section>
<?php } ?>
<?php get_footer(); ?>
