<?php
$wp_query = new WP_Query(array('post_status'=>'private','pagename'=>'home'));
if ( have_posts() ) : the_post();
get_header(); ?>

<?php /* COLUMNS */ ?>
<div class="wrapper">
	<div class="home-columns clear">
	<div class="column animated zoomIn wow" data-wow-delay=".1s">
		<div class="textwrap">
			<div class="icon"><img src="<?php echo get_bloginfo('template_url')?>/images/icons/vault.png" alt="" /></div>
			<h3 class="title">Debt Collection</h3>
			<div class="text">
				Lorem ipsum ultricies facilisis nullam porta faucibus pretium luctus elit mauris curae hendrerit faucibus ad, pharetra dictumst nibh sapien suspendisse donec euismod ullamcorper mi dui augue.
			</div>
		</div>
	</div>

	<div class="column animated zoomIn wow" data-wow-delay=".2s">
		<div class="textwrap">
			<div class="icon"><img src="<?php echo get_bloginfo('template_url')?>/images/icons/report.png" alt="" /></div>
			<h3 class="title">Credit Reporting</h3>
			<div class="text">
				Lorem ipsum ultricies facilisis nullam porta faucibus pretium luctus elit mauris curae hendrerit faucibus ad, pharetra dictumst nibh sapien suspendisse donec euismod ullamcorper mi dui augue.
			</div>
		</div>
	</div>

	<div class="column animated zoomIn wow" data-wow-delay=".3s">
		<div class="textwrap">
			<div class="icon"><img src="<?php echo get_bloginfo('template_url')?>/images/icons/documents.png" alt="" /></div>
			<h3 class="title">Document Service</h3>
			<div class="text">
				Lorem ipsum ultricies facilisis nullam porta faucibus pretium luctus elit mauris curae hendrerit faucibus ad, pharetra dictumst nibh sapien suspendisse donec euismod ullamcorper mi dui augue.
			</div>
		</div>
	</div>

	<div class="column animated zoomIn wow" data-wow-delay=".4s">
		<div class="textwrap">
			<div class="icon"><img src="<?php echo get_bloginfo('template_url')?>/images/icons/payment.png" alt="" /></div>
			<h3 class="title">Make a Payment </h3>
			<div class="text">
				Lorem ipsum ultricies facilisis nullam porta faucibus pretium luctus elit mauris curae hendrerit faucibus ad, pharetra dictumst nibh sapien suspendisse donec euismod ullamcorper mi dui augue.
			</div>
		</div>
	</div>
	</div>
</div>

<?php /* WHY US */ ?>
<div class="intro-section clear animated fadeIn wow" data-wow-delay=".5s">
	<div class="col titlecol">
		<div class="textwrap">
			<span class="small clear">Why Choose</span>
			<span class="large clear">DEBTSQUAD</span>
		</div>
	</div>
	<div class="col textcol" style="background-image:url(<?php echo get_bloginfo('template_url')?>/images/intro.jpg)">
		<div class="textwrap clear">
			<p>Lorem ipsum eros eget metus blandit est praesent, enim ligula justo quisque auctor senectus nulla, habitasse posuere orci phasellus ultricies cubilia neque auctor tellus senectus quam nunc eleifend nullam eleifend, diam maecenas luctus ad metus maecenas ad, cras quam fusce phasellus et leo cras ac pellentesque rutrum mollis donec cubilia porta, varius nunc lectus donec luctus maecenas morbi, pulvinar cubilia dictumst hac in.</p>
			<p>Nostra blandit pharetra porttitor tortor hac per tempus malesuada suscipit, class fringilla aliquet per adipiscing phasellus hac senectus potenti sit, facilisis ut risus dapibus nisl porttitor praesent sem fermentum nibh iaculis volutpat blandit.</p>
		</div>
	</div>
</div>

<?php /* TESTIMONIAL */ ?>
<div class="home-testimonial clear full-wrapper animated fadeInUp wow">
	<div class="wrap">
		<div class="text">
			<p>Condimentum semper aenean primis consectetur suscipit aliquam sollicitudin integer nullam mi, ut aliquet etiam rutrum nunc metus aliquet et nisl etiam donec et sociosqu consectetur pretium primis mi potenti, tristique a ornare at ligula rhoncus vehicula consequat morbi velit, tellus aliquam libero odio maecenas curabitur nisl ornare tristique cras justo.</p>
			<div class="author">
				<strong>John Doe Smith</strong>
				<div>Account Manager, AVC Company</div>
			</div>
		</div>
		<div class="cta">
			<span class="swipe-effect">
				<a href="#" class="btn swipe">
					<span>Submit Debt For Collection Now</span>
					<i class="fa fa-caret-right" aria-hidden="true"></i>
				</a>
			</span>
		</div>
	</div>
</div>

<?php endif;
get_footer();
