<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<div class="mmm-post mmm-post--single">
				<time class="mmm-post__date mmm-post--single__date"><?php the_time('Y.m.d'); ?></time>
				<h2 class="mmm-post__title mmm-post--single__title"><?php the_title(); ?></h2>			
				<?php
					$terms = mmm_get_the_terms( get_the_ID() );
					if( $terms ){
				?>
					<div class="mmm-post__terms mmm-post--single__terms">
						<?php if( $terms['category'] ){ ?>
							<?php foreach( $terms['category'] as $tax => $categories ){ ?>
								<?php foreach( $categories as $category ){ ?>
									<a href="<?php echo $category['link']; ?>" class="mmm-post__cat mmm-post--single__cat mmm-post__cat--<?php echo $tax; ?> mmm-post--single__cat--<?php echo $tax; ?>"><?php echo $category['name']; ?></a>
								<?php } ?>
							<?php } ?>
						<?php } ?>
						<?php if( $terms['tag'] ){ ?>
							<?php foreach( $terms['tag'] as $tax => $tags ){ ?>
								<?php foreach( $tags as $tag ){ ?>
									<a href="<?php echo $tag['link']; ?>" class="mmm-post__tag mmm-post--single__tag mmm-post__tag--<?php echo $tax; ?> mmm-post--single__tag--<?php echo $tax; ?>"><?php echo $tag['name']; ?></a>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
				<?php if( has_post_thumbnail() ){ ?>
					<div class="mmm-post__image mmm-post--single__image">
						<img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('full'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
					</div>
				<?php } ?>
				<div class="mmm-post__content mmm-post--single__content">
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
<?php mmm_related_posts( get_the_ID() ); ?>
<?php get_template_part('templates/after-content'); ?>
<?php get_footer(); ?>
