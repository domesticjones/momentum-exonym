<?php
  $servicesPage = 305;
  get_header();
  echo ex_content($servicesPage);
  echo ex_wrap('start', 'services');
  $termsArgs = array(
    'taxonomy'    => 'service_groups',
    'hide_empty'  =>  false,
  );
  $termsList = get_terms($termsArgs);
  if(!is_wp_error($termsList)) {
    echo '<ul class="services-groups-wrap">';
      foreach($termsList as $term) {
        $termAcf = 'term_' . $term->term_id;
        $photo = get_field('images', $termAcf)['photo'];
        $link = get_term_link($term->term_id);
        $name = $term->name;
        $desc = $term->description;
        echo '<li>';
          echo '<div class="image" style="background-image: url(' . wp_get_attachment_image_url($photo['ID'], 'large') . ')">' . wp_get_attachment_image($photo['ID'], 'large') . '</div>';
          echo '<div class="content"><h2>' . $name . '</h2><p>' . $desc . '</p>' . ex_cta('arrow', 'Learn More', $link) . '</div>';
        echo '</li>';
      }
    echo '</ul>';
  }
  echo ex_wrap('end');
  get_footer();
?>
