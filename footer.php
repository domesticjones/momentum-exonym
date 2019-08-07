			</main>
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
				<div class="footer-column footer-brand">
					<img src="<?php ex_logo('primary', 'light'); ?>" alt="Logo for <?php ex_brand(); ?>" class="footer-logo" />
					<p class="copyright">&copy;<?php echo date('Y') . ' '; ex_brand('legal'); ?></p>
				</div>
				<div class="footer-column footer-certs">
					<?php
						if(have_rows('certifications', 'options')) {
							echo '<ul class="certifications">';
								while(have_rows('certifications', 'options')) {
									the_row();
									echo '<li>' . get_sub_field('certification') . ': ' . get_sub_field('cert_id') . '</li>';
								}
							echo '</ul>';
						}
					?>
				</div>
				<div class="footer-column footer-services footer-column-border">
					<nav class="footer-nav" role="navigation">
						<h3 class="footer-title">Services</h3>
						<?php
							wp_nav_menu(array(
								'container' => false,
								'theme_location' => 'services-menu',
							));
						?>
					</nav>
				</div>
				<div class="footer-column footer-quick footer-column-border">
					<nav class="footer-nav" role="navigation">
						<h3 class="footer-title">Quick Links</h3>
						<ul>
							<li><?php echo ex_scheduleCta('Schedule Test'); ?></li>
							<li><a href="#">My Account</a></li>
							<li><a href="#">Contact</a></li>
						</ul>
					</nav>
				</div>
				<div class="footer-column footer-contact footer-column-border">
					<h3 class="footer-title">Contact Us</h3>
					<?php
						ex_contact('phone');
						echo '<nav class="nav-address"><i>';
							ex_contact('address');
						echo '</i></nav>';
					?>
				</div>
			</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>
