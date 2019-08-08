<?php
	get_header();
	if(have_posts()): while(have_posts()): the_post();
		if(is_cart() || is_checkout()) {
			the_content();
		} else {
			ex_content();
		}
	endwhile; endif;
	get_footer();
?>
