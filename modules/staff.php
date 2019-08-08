<?php
  $staffQueryArgs = array(
  'post_type'              => array( 'staff' ),
  'posts_per_page'         => '-1',
  'order'                  => 'ASC',
  'orderby'                => 'menu_order',
  );
  $staffQuery = new WP_Query($staffQueryArgs);
  if($staffQuery->have_posts()) {
    echo ex_wrap('start', 'staff');
      echo '<div class="module-inner"><ul class="staff-wrap">';
        while($staffQuery->have_posts()) {
          $staffQuery->the_post();
          $name = get_the_title($post->ID);
          $position = get_field('position');
          $phone = get_field('phone_number');
          $email = get_field('email_address');
          echo '<li>';
            echo '<figure style="background-image: url(' . get_the_post_thumbnail_url($post->ID, 'thumbnail-medium') . ')">' . get_the_post_thumbnail($post->ID, 'thumbnail-medium') . '</figure>';
            echo '<h2>' . $name . '</h2>';
            echo '<p>';
              if($position) { echo $position . '<br />'; }
              if($phone) { echo '<a href="tel:' . $phone . '" target="_blank">' . $phone . '</a><br />'; }
              if($email) { echo '<a href="mailto:' . $email . '" target="_blank">' . $email . '</a><br />'; }
            echo '</p>';
          echo '</li>';
        }
      echo '</ul></div>';
    echo ex_wrap('end');
  }
  wp_reset_postdata();
