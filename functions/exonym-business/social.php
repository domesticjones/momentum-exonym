<?php
	/* ================
		 SOCIAL MEDIA TAB
		 ================ */

  if (!defined('WPINC')) { die; }

  // Display Social Icons
  function ex_social() {
    if (have_rows('social_networks', 'options')) {
      echo '<nav class="nav-social"><ul>';
        while (have_rows('social_networks', 'options')): the_row();
          $icon = get_sub_field('icon');
            echo '<li><a href="' . get_sub_field('url') . '" title="' . get_sub_field('description') . '" target="_blank">';
              echo '<img src="' . $icon['sizes']['small'] . '" alt="' . get_sub_field('description') . '" />';
              echo '<span>' . $icon['title'] . '</span>';
            echo '</a></li>';
        endwhile;
      echo '</ul></nav>';
    }
  }
