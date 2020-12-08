<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="mmm-post mmm-post--archive">
		        <?php if( has_post_thumbnail() ){ ?>
		            <div class="mmm-post__image mmm-post--archive__image">
		                <img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('medium'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
		            </div>
				<?php } ?>
				<div class="mmm-post-text">
					<?php
						$terms = mmm_get_the_terms( get_the_ID() );
						if( $terms ){
					?>
						<div class="mmm-post__terms mmm-post--archive__terms">
							<?php if( $terms['category'] ){ ?>
								<?php foreach( $terms['category'] as $tax => $categories ){ ?>
									<?php foreach( $categories as $category ){ ?>
										<span class="mmm-post__cat mmm-post--archive__cat mmm-post__cat--<?php echo $tax; ?> mmm-post--archive__cat--<?php echo $tax; ?>"><?php echo $category['name']; ?></span>
									<?php } ?>
								<?php } ?>
							<?php } ?>
							<?php if( $terms['tag'] ){ ?>
								<?php foreach( $terms['tag'] as $tax => $tags ){ ?>
									<?php foreach( $tags as $tag ){ ?>
										<span href="<?php echo $tag['link']; ?>" class="mmm-post__tag mmm-post--archive__tag mmm-post__tag--<?php echo $tax; ?> mmm-post--archive__tag--<?php echo $tax; ?>"><?php echo $tag['name']; ?></span>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
					<time class="mmm-post__date mmm-post--archive__date"><?php the_time('Y.m.d'); ?></time>
					<h3 class="mmm-post__title mmm-post--archive__title"><?php the_title(); ?></h3>
					<div class="mmm-post__content mmm-post--archive__content">
						<?php
							$content = get_the_content();
							echo mmm_limit_post_content( $content, 90 );
						?>
					</div>
				</div>
			</a>
			<?php
		}
	}else{
		?>
		<p>※記事がありません</p>
		<?php
	}
?>
<?php mmm_pagination_archive(); ?>
<?php get_template_part( 'templates/after-content' ); ?>
<?php get_footer(); ?>
