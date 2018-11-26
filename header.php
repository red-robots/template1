<?php
/**
 * The header for theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href="https://fonts.googleapis.com/css?family=Niramit:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script>
<?php wp_head(); ?>
</head>

<?php
$logo = get_custom_logo();
$obj = get_queried_object();
$is_home = ( isset($obj->post_name) && $obj->post_name=='home' ) ? true : false;
$classes = ($is_home) ? 'homepage' : 'subpage';
?>
<body <?php body_class($classes); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>
	<header id="masthead" class="site-header clear">

		<div class="top-header full-wrapper">
			<div class="head-left">
			<?php if($logo) { ?>
	            <div class="logo"><?php echo $logo;?></div>
	        <?php } else { ?>
	            <div class="logo logo-name">
	            	<a href="<?php bloginfo('url'); ?>"><span name="logo_text"><?php bloginfo('name'); ?></span></a>
	            </div>
	        <?php } ?>
	    	</div>

	    	<div class="head-right">
	    		<span class="text1">NEED DEBT ADVICE? CALL US!</span>
	    		<span class="text2"><i class="fa fa-phone"></i> <a class="phone" href="tel:2510001234">251 000 1234</a></span>
	    	</div>
    	</div>

    	<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'MENU', 'acstarter' ); ?></button>
		<nav id="site-navigation" class="main-navigation full-wrapper" role="navigation">			
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu' ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<?php if( $is_home ) { ?>
	<?php get_template_part('inc/banner'); ?>
	<?php } ?>

	<div id="content" class="site-content wrapper">
