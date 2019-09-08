<?php
$groups = get_the_terms($post->ID, 'service_groups');
echo ex_wrap('start', 'hero', 'hero-heading module-bgcolor-black');
  echo ex_moduleBg(get_post_thumbnail_id(), 'opacity: 0.666;');
  echo '<div class="module-inner">';
    echo '<h1 style="text-align: center;">' . get_the_title() . '</h1>';
    $groupsPrint = [];
    $groupsPlural = '';
    if(!is_wp_error($groups)) {
      foreach($groups as $group) {
        array_push($groupsPrint, $group->name);
      }
    }
    if(count($groupsPrint) > 1) { $groupsPlural = 's'; }
    if(!empty($groupsPrint)) { echo '<h2 style="text-align: center;">Part of the ' . join(', ', $groupsPrint) . ' Inspection' . $groupsPlural . '</h2>'; }
  echo '</div>';
echo ex_wrap('end');
echo ex_wrap('start', 'fullwidth');
  echo '<div class="module-inner">';
    the_field('description');
  echo '</div>';
echo ex_wrap('end');
if(have_rows('files') || have_rows('links')) {
  echo ex_wrap('start', 'fullwidth', 'module-bgcolor-grey service-single-moreinfo');
    echo '<div class="module-inner">';
      echo '<h2 class="title">Further Information</h2>';
      if(have_rows('files')) {
        echo '<ul class="service-more">';
          echo '<li class="service-more-heading"><h3>Files</h3></li>';
          while(have_rows('files')) {
            the_row();
            $link = get_sub_field('file');
            $desc = get_sub_field('description');
            $descPrint = '';
            if(!empty($desc)) { $descPrint = '<p>' . $desc . '</p>'; }
            echo '<li><a href="' . $link['url'] . '" target="_blank">' . $link['title'] . '</a>' . $descPrint . '</li>';
          }
        echo '</ul>';
      }
      if(have_rows('links')) {
        echo '<ul class="service-more">';
          echo '<li class="service-more-heading"><h3>Links</h3></li>';
          while(have_rows('links')) {
            the_row();
            $link = get_sub_field('link');
            $desc = get_sub_field('description');
            $descPrint = '';
            if(!empty($desc)) { $descPrint = '<p>' . $desc . '</p>'; }
            echo '<li><a href="' . $link['url'] . '" target="' . $link['target'] . '">' . $link['title'] . '</a>' . $descPrint . '</li>';
          }
        echo '</ul>';
      }
    echo '</div>';
  echo ex_wrap('end');
}
