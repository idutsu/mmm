<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php
			$breadcrumb = new MMM_Breadcrumb();
			$is_home = $breadcrumb->is_home;
			$site_title = esc_html( get_bloginfo('name') );
			$meta_title = $breadcrumb->meta_title;
			$meta_description = $breadcrumb->meta_description;
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
				<?php $site_title_tag = $is_home ? "h1" : "p" ; ?>
				<<?php echo $site_title_tag; ?> class="mmm-site-title"><a href="<?php echo home_url(); ?>"><?php echo $site_title; ?></a></<?php echo $site_title_tag; ?>>
				<div class="mmm-sp-fix">
					<a href="tel:">電話で相談</a>
					<a href="<?php echo home_url('/contact/'); ?>">メールで相談</a>
				</div>
				<?php if( wp_get_nav_menu_items('global') ){ ?>
					<?php mmm_menu('global'); ?>
					<div class="mmm-hamburger"><span></span><span></span><span></span></div>
				<?php } ?>
				<?php wp_head(); ?>
			</header>
		</div>
		<div class="mmm-wrapper">
			<?php if( !$is_home ){
				get_sidebar();
			} ?>
			<div class="mmm-main">
				<main>
					<?php if( !$is_home ){ ?>
						<h1 class="mmm-page-title"><?php echo $breadcrumb->breadcrumb[1]['name']; ?></h1>
						<?php $breadcrumb->echo(); ?>
					<?php } ?>
