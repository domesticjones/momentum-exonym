<?php
  $term = get_queried_object()->term_id;
  $termArray = get_term($term, 'service_groups', 'ARRAY_A');
  $termAcf = 'term_' . $term;
  $subhead = get_field('subheading', $termAcf);
  get_header();
  $stepsHeading = get_field('steps_heading', $termAcf);
  $steps = get_field('steps', $termAcf);
  echo ex_wrap('start', 'hero', 'hero-heading module-bgcolor-black');
    echo ex_moduleBg(get_field('images', $termAcf)['header']['ID'], 'opacity: 0.666;');
    echo '<div class="module-inner">';
      echo '<h1 style="text-align: center;">' . $termArray['name'] . '</h1>';
      if(!empty($subhead)) { echo '<h2 style="text-align: center;">' . $subhead . '</h2>'; };
    echo '</div>';
  echo ex_wrap('end');
  echo ex_wrap('start', 'service-top');
    echo '<div class="module-inner">';
      echo '<div class="left">';
        echo '<h1>' . $stepsHeading . '</h1>';
        echo '<ul class="service-steps">';
          foreach($steps as $step) {
            echo '<li>' . wp_get_attachment_image($step['thumbnail']['ID'], 'thumbnail-large', false, array('class' => 'service-thumb')) . '<div class="service-text"><h2>' . $step['title'] . '</h2>' . $step['content'] . '</div></li>';
          }
        echo '</ul>';
        echo ex_cta('schedule', 'Schedule Now');
      echo '</div>';
      echo '<div class="right">';
        echo wp_get_attachment_image(get_field('images', $termAcf)['photo']['ID'], 'medium', false, array('class' => 'service-image-photo'));
        if(have_posts()) {
          echo '<ul class="service-list">';
            echo '<li class="service-list-title"><h2>Necessary Inspections for ' . $termArray['name'] . '</h2></li>';
            while(have_posts()) {
              the_post();
              echo '<li class="service-single"><h3 class="service-single-title">' . get_the_title() . '</h3><div class="service-single-content">' . get_field('description') . ex_cta('arrow', 'More Info on ' . get_the_title(), get_permalink()) . '</div></li>';
            }
          echo '</ul>';
        }
      echo '</div>';
    echo '</div>';
  echo ex_wrap('end');
  echo ex_wrap('start', 'service-bottom');
    echo '<div class="module-inner">';
      echo '<div class="left">';
        echo wp_get_attachment_image(get_field('images', $termAcf)['infographic']['ID'], 'jumbo');
      echo '</div>';
      echo '<div class="right">';
        the_field('long_description', $termAcf);
      echo '</div>';
    echo '</div>';
  echo ex_wrap('end');
  get_footer();
