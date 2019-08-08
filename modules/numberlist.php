<?php
  echo ex_wrap('start', 'numberlist');
    if(have_rows('list')) {
      echo '<div class="module-inner"><ul class="numberlist-wrap">';
        $count = 0;
        while(have_rows('list')) {
          the_row();
          $count++;
          echo '<li><div class="number">' . $count . '</div><h2>' . get_sub_field('heading') . '</h2><p>' . get_sub_field('content') . '</p></li>';
        }
      echo '</ul></div>';
      echo ex_cta();
    }
  echo ex_wrap('end');
