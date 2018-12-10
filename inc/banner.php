<?php
global $post;
$post_id = ( isset($post->ID) ) ? $post->ID : 0;
$banner = get_mb_custom_fields($post_id,'home_banner');
$banner_items = ($banner) ? count($banner) : 0;
?>
<div class="banner-section" />
	<div class="banner clear animated fadeIn<?php echo ($banner_items) ? ' flexslider':''?>">
	<?php if($banner) { ?>
		<ul class="banner-items slides">
			<?php $ctr=1; foreach($banner as $b) { 
				//$banner_title = $b->title;
				$banner_caption = $b->caption;
				$banner_image = ( isset($b->image) && $b->image ) ? $b->image : '';
				$button_label = $b->button_label;
				$button_type = $b->button_type;
				$custom_class = (isset($b->custom_class) && $b->custom_class) ? $b->custom_class : '';
				$button_link = (isset($b->button_link)) ? $b->button_link : '';
				$banner_link = ($button_link && is_numeric($button_link)) ? get_permalink($button_link) : $button_link;
				if($banner_image) {  ?>
				<li class="slide<?php echo ($custom_class) ? ' ' . $custom_class:'';?>">
					<div class="banner-image">
						<img src="<?php echo $banner_image['url']?>" alt="<?php echo $banner_image['title']?>" />
					</div>
					<div class="banner-caption">
						<div class="mid clear">
							<div class="captionfx wow" data-wow-delay=".3s">
								<?php echo $banner_caption; ?>
								<?php if($button_label && $banner_link) { ?>
								<div class="buttondiv">
									<span class="swipe-effect">
										<a class="btn" href="<?php echo $banner_link; ?>"><span><?php echo $button_label; ?></span></a>
									</span>
								</div>
								<?php } ?>
							</div>
						</div>
						<span class="shape shape1 wow" data-wow-delay=".3s"></span>
						<span class="shape shape2 wow" data-wow-delay=".1s"></span>
					</div>
				</li>
				<?php $ctr++; } ?>
			<?php } ?>
		</ul>
	<?php } else { ?>
		<div class="banner-image">
			<img src="<?php bloginfo('template_url');?>/images/banner-sample.jpg" alt="" />
		</div>
		<div class="banner-caption">
			<div class="mid clear">
				<div class="animated fadeInUp wow" data-wow-delay=".4s">
					<h3>Aliquam eleifend nam</h3>
					<h2>Lorem ipsum dolor</h2>
					<div class="buttondiv">
						<span class="swipe-effect">
							<a class="btn" href="#"><span>Learn More</span></a>
						</span>
					</div>
				</div>
			</div>
			<span class="shape shape1 animated fadeInLeft wow" data-wow-delay=".2s"></span>
			<span class="shape shape2 animated fadeInLeft wow" data-wow-delay=".1s"></span>
		</div>
	<?php } ?>
	</div>
</div>