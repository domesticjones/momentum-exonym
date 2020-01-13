<?php
  $image = get_sub_field('image');
  $align = get_sub_field('image_alignment');
  $type = get_sub_field('content_type');
  $heading = get_sub_field('heading');
  $content = get_sub_field('content');
  $classes = 'imagecontent-align-' . $align . ' imagecontent-type-' . $type;
  if(!$image) { $classes .= ' imagecontent-noimg'; }
  echo ex_wrap('start', 'imagecontent', $classes);
    if($image) { echo ex_moduleBg($image['ID']); }
    echo '<div class="module-inner">';
      if($heading) { echo '<h2 class="imagecontent-header">' . $heading . '</h2>'; }
      echo $content;
      echo ex_cta();
    echo '</div>';
  echo ex_wrap('end');
