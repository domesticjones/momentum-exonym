<?php
	if(!is_user_logged_in() && (is_cart() || is_checkout())) {
		wp_redirect(get_permalink(wc_get_page_id('myaccount')), 302);
		exit;
	}
	get_header();
	if(have_posts()): while(have_posts()): the_post();
		if(is_cart() || is_checkout() || is_account_page() || get_the_id() == 310) {
			if(get_the_id() == 310 && is_user_logged_in()) {
				$byebye = get_permalink(wc_get_page_id('myaccount'));
				echo 'Redirecting... <script type="text/javascript">window.location="' . $byebye . '"</script>';
			} else {
				the_content();
			}
		} else {
			ex_content();
		}
	endwhile; endif;
	get_footer();
?>
