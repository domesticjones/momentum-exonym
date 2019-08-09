<?php
  $shopId = wc_get_page_id('shop');
  $productTitle = get_the_title();
  $dateSelectText = get_field('date_select_disclaimer', $shopId);
  $chooseHeading = get_field('choose_tests_heading', $shopId);
  $serviceCat = get_field('service_category');
  echo '<aside class="inspection-sidebar">';
    if($serviceCat == null) {
      echo '<h3>' . $chooseHeading . '</h3>';
      $inspectionQueryArgs = array(
      	'post_type'      => array( 'service' ),
      	'posts_per_page' => '-1',
        'order'          => 'ASC',
      	'orderby'        => 'title',
      );
      $inspectionQuery = new WP_Query($inspectionQueryArgs);
      if($inspectionQuery->have_posts()) {
        echo '<ul id="inspection-choose">';
      	while($inspectionQuery->have_posts()) {
      		$inspectionQuery->the_post();
          echo '<li>' . get_the_title() . '</li>';
      	}
        echo '</ul>';
      }
      wp_reset_postdata();
    }
    echo str_replace('%%product%%', $productTitle, $dateSelectText);
  echo '</aside>';
?>
