<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php
			$is_home = is_home() || is_front_page() ? true : false ;
			$site_title = esc_html( get_bloginfo('name') );
			$meta_title = "";
			$meta_description = esc_html( get_bloginfo( 'description' ) );
			$breadcrumb = new MMM_Breadcrumb();
			$qo = $breadcrumb->qo;
			if( is_category() ){
				$breadcrumb->tax();
				$meta_title = $qo->name;
				$meta_description = $meta_title."。".$meta_description;
			}else if( is_tag() ){
				$breadcrumb->tax();
				$meta_title = $qo->name;
				$meta_description = $meta_title."。".$meta_description;
			}else if( is_tax() ){
				$breadcrumb->tax();
				$meta_title = $qo->name;
				$meta_description = $meta_title."。".$meta_description;
			}else if( is_post_type_archive() ){
				$breadcrumb->post_type_archive();
				$meta_title = $qo->label;
				$meta_description = $meta_title."。".$meta_description;
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
		<link rel="shortcut icon" href="">
		<link rel="apple-touch-icon" href="">
		<link rel="icon" type="image/png" href="">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div class="mmm-header">
			<header>
				<?php $h1 = $is_home ? "h1" : "p" ; ?>
				<<?php echo $h1; ?> class="mmm-site-title"><a href="<?php echo home_url(); ?>"><?php echo $site_title; ?></a></<?php echo $h1; ?>>
				<div class="mmm-sp-fix">
					<a href="tel:">電話で相談</a>
					<a href="<?php echo home_url('/contact/'); ?>">メールで相談</a>
				</div>
				<?php if(wp_get_nav_menu_items('global')){ ?>
					<?php mmm_menu('global'); ?>
					<div class="mmm-hamburger"><span></span><span></span><span></span></div>
				<?php } ?>
				<?php wp_head(); ?>
			</header>
		</div>
		<div class="mmm-wrapper">
			<?php if( ! $is_home ){
				get_sidebar();
			} ?>
			<div class="mmm-main">
				<main>
					<?php if( ! $is_home ){
						$bc = $breadcrumb->breadcrumb; ?>
						<h1 class="mmm-page-title"><?php echo $bc[1]['text']; ?></h1>
						<div class="mmm-breadcrumb">
							<ul>
							<?php
								$bc_length = count($bc);
								for( $i=0; $i<$bc_length; $i++ ){
									if( $i<$bc_length-1 ){
										echo '<li><a href="'.$bc[$i]['link'].'">'.$bc[$i]['text'].'</a></li><span class="slash">/</span>';
									}else{
										echo '<li><span>'.$bc[$i]['text'].'</span></li>';
									}
								}
							?>
							</ul>
						</div>
					<?php } ?>
