<?php
  echo ex_wrap('start', 'fullwidth');
    echo '<div class="modal-inner">';
      the_sub_field('content');
      echo ex_cta();
    echo '</div>';
  echo ex_wrap('end');
