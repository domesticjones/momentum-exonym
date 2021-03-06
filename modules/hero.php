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
    if($type == 'image') {
      echo '<div class="hero-image-only">';
        echo wp_get_attachment_image($image['id'], 'jumbo', array($classes => 'hero-image-only-img'));
        echo '<div class="hero-image-only-content">';
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
          echo '<div class="text">';
            echo $content;
          echo '</div>';
          echo '<div class="calltoaction">';
            echo ex_cta();
          echo '</div>';
        echo '</div>';
      echo '</div>';
    } else {
      echo ex_moduleBg($image['ID'], $imageOpacity);
      echo '<div class="module-inner">';
        echo $content;
        echo ex_cta();
      echo '</div>';
    }
    if($affiliates && $type != 'image') {
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
