<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<?php
	$is_home = is_home() || is_front_page() ? true : false ;
	$site_title = esc_html( get_bloginfo() );
	$meta_title = "";
	$meta_description = esc_html( get_bloginfo( 'description' ) );
	$breadcrumb = new MMM_Breadcrumb();
	$qo = $breadcrumb->qo;
	$text_after_archive_title = "一覧";
	if( is_category() ){
		$breadcrumb->tax();
		$meta_title = $qo->name.$text_after_archive_title;
		$meta_description = "[".$meta_title."]".$meta_description;
	}else if( is_tag() ){
		$breadcrumb->tax();
		$meta_title = $qo->name.$text_after_archive_title;
		$meta_description = "[".$meta_title."]".$meta_description;
	}else if( is_tax() ){
		$breadcrumb->tax();
		$meta_title = $qo->name.$text_after_archive_title;
		$meta_description = "[".$meta_title."]".$meta_description;
	}else if( is_post_type_archive() ){
		$breadcrumb->post_type_archive();
		$meta_title = $qo->label.$text_after_archive_title;
		$meta_description = "[".$meta_title."]".$meta_description;
	}else if( is_single() ){
		$breadcrumb->single();
		$post_type_label = '';
		$post_type = $qo->post_type;
		if( $post_type !== 'post' ){
			$po = get_post_type_object( $qo->post_type );
			$post_type_label = "[".$po->labels->singular_name."]";
		}
		$meta_title = $post_type_label.$qo->post_title;
		$meta_description = $post_type_label.mmm_limit_post_content( $qo->post_content, 90 );
	}else if( is_page() ){
		$breadcrumb->page();
		$meta_title = $qo->post_title;
		$meta_description = mmm_limit_post_content( $qo->post_content, 90 );
	}else if( is_search() ){
		$breadcrumb->search();
		$meta_title = end( $breadcrumb->breadcrumb )['text'];
		$meta_description = $meta_title."。".$meta_description;
	}else if( is_404() ){
		$breadcrumb->notfound();
		$meta_title = end( $breadcrumb->breadcrumb )['text'];
		$meta_description = $meta_title."。".$meta_description;
	}
?>
<title><?php if( $meta_title !== "" ){ echo $meta_title."｜"; } ?><?php echo $site_title; ?></title>
<meta name="description" content="<?php echo $meta_description; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php wp_head(); ?>
</head>
<body>
<header>
	<?php $h1 = $is_home ? "h1" : "p" ; ?>
	<<?php echo $h1; ?> class="mmm-site-title"><a href="<?php echo home_url(); ?>"><?php echo $site_title; ?></a></<?php echo $h1; ?>>
	<?php mmm_menu('global'); ?>
	<div class="hamburger"><span></span><span></span><span></span></div>
<?php wp_head(); ?>
</header>
<main>
	<?php if( ! $is_home ){ ?>
		<h1 class="mmm-page-title"><?php echo $meta_title; ?></h1>
		<div class="mmm-breadcrumb">
			<ul>
			<?php
			foreach( $breadcrumb->breadcrumb as $bc ){
				if( $bc['link'] ){
					echo '<li><a href="'.$bc['link'].'">'.$bc['text'].'</a></li> /';
				}else{
					echo '<li><span>'.$bc['text'].'</span></li>';
				}
			}
			?>
			</ul>
		</div>
		<?php get_sidebar(); ?>
	<?php } ?>
