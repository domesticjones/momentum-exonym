<?php
  function ex_scheduleCta() {
    $link = '#schedule';
    if(is_user_logged_in()) {
      $link = '#scheduleLink';
    }
    return '<a href="' . $link . '" class="cta-button cta-schedule"><span>Schedule</span></a>';
  }
