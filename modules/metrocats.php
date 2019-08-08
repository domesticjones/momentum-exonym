<?php
  $count = 0;
  $cats = get_sub_field('category_blocks');
  echo ex_wrap('start', 'metrocats');
    echo '<div class="module-inner">';
      echo '<div class="metrocats-left">' . ex_metrocats($cats[0]) . '</div>';
      echo '<div class="metrocats-right">';
        echo '<div class="metrocats-top">' . ex_metrocats($cats[1]) . '</div>';
        echo '<div class="metrocats-bottom">' . ex_metrocats($cats[2]) . ex_metrocats($cats[3]) . '</div>';
      echo '</div>';
    echo '</div>';
  echo ex_wrap('end');
