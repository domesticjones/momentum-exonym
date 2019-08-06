			<?php
				if(have_rows('affiliates', 'options')) {
					echo '<nav id="affiliates"><ul>';
						while(have_rows('affiliates', 'options')) {
							the_row();
							$affiliateLink = get_sub_field('link');
							$affiliateLogo = get_sub_field('monochrome_logo');
							echo '<li>';
								if($affiliateLink) { echo '<a href="' . $affiliateLink['url'] . '" target="' . $affiliateLink['target'] . '" title="' . $affiliateLink['title'] . '">'; }
									if($affiliateLogo) {
										echo wp_get_attachment_image($affiliateLogo['ID'], 'small');
									} else {
										echo $affiliateLink['title'];
									}
								if($affiliateLink) { echo '</a>'; }
							echo '</li>';
						}
					echo '</ul></nav>';
				}
			?>
			<footer id="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
				<?php
					if(have_rows('certifications', 'options')) {
						while(have_rows('certifications', 'options')) {
							the_row();
							the_sub_field('certification');
							the_sub_field('cert_id');
						}
					}
				?>
				<p class="copyright">&copy; Copyright <?php ex_brand('legal'); ?></p>
				<nav class="nav-footer" role="navigation">
					<?php wp_nav_menu(array(
						'container' => 'ul',                    // enter '' to remove nav container
						'container_class' => 'footer-links cf',	// class of container (should you choose to use it)
						'menu' => __('Footer', 'exonym'),	      // nav name
						'menu_class' => 'nav footer-nav cf',    // adding custom nav class
						'theme_location' => 'footer-menu',		  // where it's located in the theme
						'before' => '',							            // before the menu
						'after' => '',							            // after the menu
						'link_before' => '',					          // before each link
						'link_after' => '',						          // after each link
						'depth' => 1,							              // limit the depth of the nav
						'fallback_cb' => ''						          // fallback function
					)); ?>
				</nav>
			</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
