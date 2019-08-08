<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title><?php wp_title(); ?></title>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
		<div id="container">
			<header id="tagline"><h1><?php bloginfo('description'); ?></h1></header>
      <header id="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
        <a href="<?php echo get_home_url(); ?>" class="header-logo">
					<img src="<?php ex_logo('primary', 'light'); ?>" alt="Logo for <?php ex_brand(); ?>" />
				</a>
        <nav class="header-nav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
          <?php
						wp_nav_menu(array(
	            'container' => false,
	            'theme_location' => 'header-menu',
	          ));
					?>
        </nav>
        <?php echo ex_cta('schedule', 'Schedule'); ?>
				<a href="#" id="header-nav-toggle">
          <span class="line"></span>
          <span class="line"></span>
          <span class="line"></span>
				</a>
      </header>
			<nav id="nav-responsive">
				<?php
					wp_nav_menu(array(
						'container' => false,
						'theme_location' => 'responsive-menu',
					));
					echo ex_cta('schedule', 'Schedule');
				?>
			</nav>
			<main role="main" aria-label="Content">
