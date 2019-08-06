<?php
  function ex_scheduleCta($text = 'Schedule') {
    $link = '#schedule';
    if(is_user_logged_in()) {
      $link = '#scheduleLink';
    }
    return '<a href="' . $link . '" class="cta-button cta-schedule"><span>' . $text . '</span></a>';
  }
