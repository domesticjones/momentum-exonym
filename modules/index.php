<?php
  function ex_cta($type = null, $text = null, $url = null) {
    $output = '';
    if(!$type) {
      $type = get_sub_field('cta_type');
    }
    if($type == 'schedule') {
      if($text) {
        $textPrint = $text;
      } else {
        $textPrint = get_sub_field('cta_schedule_text');
      }
      $linkPrint = '#schedule';
      if(is_user_logged_in()) {
        $linkPrint = get_permalink(wc_get_page_id('shop'));
      }
    } elseif($type == 'custom') {
      $linkPrint = get_sub_field('cta_link')['url'];
      $textPrint = get_sub_field('cta_link')['title'];
      $targetPrint = get_sub_field('cta_link')['target'];
      $type = 'arrow';
    }
    if($url != null) { $linkPrint = $url; }
    if($text != null) { $textPrint = $text; }
    if($type != 'none') {
      $output = '<div class="cta-wrap"><a href="' . $linkPrint . '" class="cta-button cta-icon-' . $type . '"><span>' . $textPrint . '</span></a></div>';
    }
    return $output;
  }

  function ex_contentBodyClass($classes) {
    $modulesFirst = get_field('modules', $page->ID)[0];
    if($modulesFirst['acf_fc_layout'] == 'hero_image' && $modulesFirst['hero_image_type'] == 'fullscreen' && get_post_type() == 'page') {
      $classes[] = 'hero-image-top';
    }
    return $classes;
  }
  add_filter('body_class', 'ex_contentBodyClass', 1000);

  function ex_content($id = null) {
    if($id == null) { $id = $post->ID; }
    echo '<article class="' . implode(" ", get_post_class()) . '">';
    if(have_rows('modules', $id)) {
      while(have_rows('modules', $id)) {
        the_row();
        if(get_row_layout() == 'contact_info') {
          get_template_part('modules/contact');
        } elseif(get_row_layout() == 'hero_image') {
          get_template_part('modules/hero');
        } elseif(get_row_layout() == 'full_width') {
          get_template_part('modules/fullwidth');
        } elseif(get_row_layout() == 'metro_categories') {
          get_template_part('modules/metrocats');
        } elseif(get_row_layout() == 'image_content') {
          get_template_part('modules/imagecontent');
        } elseif(get_row_layout() == 'numbered_list') {
          get_template_part('modules/numberlist');
        } elseif(get_row_layout() == 'staff') {
          get_template_part('modules/staff');
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
    if($class != null) {
      $class .= ' ';
    }
    if(!empty($pad)) {
      foreach($pad as $p) {
        array_push($padDeck, 'module-nopad-' . $p);
      }
      $class .= implode(' ', $padDeck) . ' ';
    }
    if($bg) {
      $class .= 'module-bgcolor-' . $bg;
    }
    if($pos == 'start') {
      $output = '<section class="module module-' . $name . ' ' . $class . ' animate-parallax animate-z-normal">';
    } elseif($pos == 'end') {
      $output = '</section>';
    }
    return $output;
  }

  function ex_moduleBg($id, $style = null, $classes = null) {
    $styleOutput = ' style="background-image: url(' . wp_get_attachment_image_url($id, 'jumbo') . ');' . $style . '"';
    $output = '<div class="module-bg"' . $styleOutput . '>' . wp_get_attachment_image($id, 'jumbo') . '</div>';
    return $output;
  }

  function ex_metrocats($cat) {
    $img = $cat['image'];
    $catId = $cat['category_info']['service_category'];
    $headingPre = $cat['category_info']['pre_heading'];
    $heading = $cat['category_info']['heading'];
    $output = '<a href="' . get_term_link($catId) . '" class="metrocat-single" style="background-image: url(' . $img['sizes']['large'] . ')"><h2><span>' . $headingPre . '</span>' . $heading . '</h2></a>';
    return $output;
  }
