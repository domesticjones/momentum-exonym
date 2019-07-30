<?php
  get_header();
  if(have_posts()) {
    echo '<h1>Services Archive</h1>';
    echo '<ul>';
    while(have_posts()) {
      the_post();
      $id = $post->ID;
      $icon = get_field('icon');
      $terms = get_the_terms($id, 'service_groups');
      $termsList = [];
      if(!is_wp_error($terms)) {
        foreach($terms as $term) {
          array_push($termsList, $term->name);
        }
      }
      echo '<h2>' . get_the_title($id) . '</h2>';
      echo '<img src="' . $icon['sizes']['small'] . '" height="30" width="30" class="aligncenter" />';
      echo get_the_post_thumbnail($id, 'large', array('class' => 'aligncenter'));
      echo 'Part of the <strong>' . implode(', ', $termsList) . '</strong> Service Group(s)';
      the_field('description');
      if(have_rows('links')) {
        echo '<h3>Links</h3>';
        echo '<ul>';
        while(have_rows('links')) {
          the_row();
          $link = get_sub_field('link');
          echo '<li>';
            echo '<a href="' . $link['url'] . '" target="' . $link['target'] . '">' . $link['title'] . '</a>';
          echo '</li>';
        }
        echo '</ul>';
      }
    }
    echo '</ul>';
  } else {
    get_template_part('404');
  }
  get_footer();
?>
