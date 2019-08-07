<?php
  $type = get_sub_field('hero_image_type');
  $affiliates = get_sub_field('hero_image_affiliates');
  $style = get_sub_field('hero_image_style');
  $content = get_sub_field('hero_image_content');
  $image = get_sub_field('hero_image');
  $imageOpacity = ' opacity: ' . ($style['opacity'] / 100) . ';';
  $gradStyle = null;
  $grad = $style['enable_gradient'];
  if($grad) { $gradStyle = ' hero-grad hero-grad-' . $style['gradient_color']; }
  $classes = 'hero-' . $type . ' module-bgcolor-' . $style['background_color'] . $gradStyle;
  echo ex_wrap('start', 'hero', $classes);
    echo ex_moduleBg($image['ID'], $imageOpacity);
    echo '<div class="module-inner">';
      echo $content;
      echo ex_cta();
    echo '</div>';
    if($affiliates) {
      if(have_rows('affiliates', 'options')) {
        echo '<ul class="hero-affiliates">';
          while(have_rows('affiliates', 'options')) {
            the_row();
            $image = get_sub_field('color_logo');
            echo '<li>' . wp_get_attachment_image($image['id'], 'small') . '</li>';
          }
        echo '</ul>';
      }
    }
  echo ex_wrap('end');
