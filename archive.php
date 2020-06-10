<?php get_header(); ?>
<?php
	if(have_posts()){
		while(have_posts()){
			the_post();
			?>
			<a href="<?php the_permalink(); ?>" class="mmm-post mmm-post--archive">
		        <?php if( has_post_thumbnail() ){ ?>
		            <div class="mmm-post-image">
		                <img src="<?php echo THEME_URL; ?>/images/dummy.png" data-src="<?php the_post_thumbnail_url('medium'); ?>" class="lazy" alt="<?php the_title(); ?>"/>
		            </div>
		        <?php } ?>
	            <time class="mmm-post-date"><?php the_time('Y.m.d'); ?></time>
	            <h3 class="mmm-post-title"><?php the_title(); ?></h3>
	            <?php
	                $terms = mmm_get_the_terms( get_the_ID() );
	                if( $terms ){
	            ?>
	                <div class="mmm-post-terms">
	                    <?php if( $terms['category'] ){ ?>
	                        <?php foreach( $terms['category'] as $key => $categories ){ ?>
	                            <?php foreach( $categories as $category ){ ?>
	                                <span class="mmm-post-cat mmm-post-cat--<?php echo $key; ?>"><?php echo $category['name']; ?></span>
	                            <?php } ?>
	                        <?php } ?>
	                    <?php } ?>
	                    <?php if( $terms['tag'] ){ ?>
	                        <?php foreach( $terms['tag'] as $key => $tags ){ ?>
	                            <?php foreach( $tags as $tag ){ ?>
	                                <span class="mmm-post-tag mmm-post-tag--<?php echo $key; ?>"><?php echo $tag['name']; ?></span>
	                            <?php } ?>
	                        <?php } ?>
	                    <?php } ?>
	                </div>
	            <?php } ?>
	            <div class="mmm-post-content">
	                <?php
	                    $content = get_the_content();
	                    echo mmm_limit_post_content( $content, 90 );
	                ?>
	            </div>
			</a>
			<?php
		}
	}else{
		?>
		<p>記事がありません</p>
		<?php
	}
?>
<?php mmm_pagination_archive(); ?>
<?php get_template_part( 'template/after-content' ); ?>
<?php get_footer(); ?>
