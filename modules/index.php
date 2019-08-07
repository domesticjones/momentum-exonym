<?php
  function ex_scheduleCta($text = 'Schedule') {
    $link = '#schedule';
    if(is_user_logged_in()) {
      $link = '#scheduleLink';
    }
    return '<a href="' . $link . '" class="cta-button cta-schedule"><span>' . $text . '</span></a>';
  }

  function ex_cta() {
    var_dump('cta');
  }

  function ex_contentBodyClass($classes) {
    $modulesFirst = get_field('modules', $page->ID)[0];
    if($modulesFirst['acf_fc_layout'] == 'hero_image' && $modulesFirst['hero_image_type'] == 'fullscreen' && get_post_type() == 'page') {
      $classes[] = 'hero-image-top';
    }
    return $classes;
  }
  add_filter('body_class', 'ex_contentBodyClass', 1000);

  function ex_content() {
    echo '<article class="' . implode(" ", get_post_class()) . '">';
    if(have_rows('modules')) {
      while(have_rows('modules')) {
        the_row();
        if(get_row_layout() == 'hero_image') {
          get_template_part('modules/hero');
        } elseif(get_row_layout() == 'full_width') {
          get_template_part('modules/fullwidth');
        }
      }
    } else {
      get_template_part('404');
    }
    echo '</article>';
  }

  function ex_wrap($pos, $name = null, $class = null) {
    $output = '';
    $bg = get_sub_field('background_color');
    $pad = get_sub_field('padding_disable');
    $padDeck = [];
    if(!empty($pad)) {
      foreach($pad as $p) {
        array_push($padDeck, 'module-nopad-' . $p);
      }
      $class .= implode(' ', $padDeck) . ' ';
    }
    if($bg) {
      $class .= 'module-bgcolor-' . $bg . ' ';
    }
    if($pos == 'start') {
      $output .= '<section class="module module-' . $name . ' ' . $class . ' animate-parallax animate-z-normal">';
    } elseif($pos == 'end') {
      $output .= '</section>';
    }
    return $output;
  }

  function ex_moduleBg($id, $style = null) {
    $styleOutput = ' style="background-image: url(' . wp_get_attachment_image_url($id, 'jumbo') . ');' . $style . '"';
    $output = '<div class="module-bg"' . $styleOutput . '></div>';
    return $output;
  }
