<?php
  /* TEMPLATE NAME: Admin Bookings */
  if(!is_user_logged_in() || !current_user_can('manage_woocommerce')) {
    wp_redirect(get_permalink(wc_get_page_id('myaccount')), 302);
    exit;
  }
  $cancel = $_GET['cancelOrder'];
  $filterDate = $_GET['dateFilter'];
  $browseId = $_GET['idFilter'];
  $statusFilter = $_GET['statusFilter'];
  $timeZone = get_option('timezone_string');
  $todayRaw = new DateTime('now', new DateTimezone($timeZone));
  $today = $todayRaw->format('Y-m-d');
  $thisPage = '//'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];
  get_header();
    function filterButton($label, $time) {
      $filterDate = $_GET['dateFilter'];
      $class = '';
      if($filterDate == $time) {
        $class = ' is-active';
      }
      return '<button class="admin-account-filter' . $class . '" type="button" data-date="' . $time . '">' . $label . '</button>';
    }
    function browseButton($label, $status) {
      $statusFilter = $_GET['statusFilter'];
      $class = '';
      $thisPage = '//'. $_SERVER['HTTP_HOST'] . explode('?', $_SERVER['REQUEST_URI'], 2)[0];
      if($statusFilter == $status) {
        $class = ' is-active';
      }
      return '<a href="' . $thisPage . '?statusFilter=' . $status . '" class="admin-account-filter' . $class . '" type="button" data-status="' . $status . '">' . $label . '</a>';
    }
    function bookingsSortDate($a, $b) {
    	return strcmp($b->start, $a->start);
    }
    $filterDateRaw = strtotime($filterDate);
    $todayRaw = new DateTime('today', new DateTimezone($timeZone));
    $tomorrowRaw = new DateTime('tomorrow', new DateTimezone($timeZone));
    $yesterdayRaw = new DateTime('yesterday', new DateTimezone($timeZone));
    $tomorrow = $tomorrowRaw->format('Y-m-d');
    $yesterday = $yesterdayRaw->format('Y-m-d');
    echo ex_wrap('start', 'account');
      echo '<section class="account-data">';
        echo '<h1>Appointments</h1>';
        $filterButtons = '<div class="admin-filter-date-presets">' . filterButton('Yesterday', $yesterday) . filterButton('Today', $today) . filterButton('Tomorrow', $tomorrow) . '</div>';
        $browseButtons = '<div class="admin-filter-browse-presets">' . browseButton('Pending', 'pending-confirmation') . browseButton('Upcoming', 'future') . browseButton('Past', 'past') . '</div>';
        echo '<form id="admin-account-filters"><div class="admin-filter-date-group"><input id="admin-filter-date" type="date" name="dateFilter" value="' . $filterDate . '"><button id="account-filters-submit" class="account-filters-submit" type="submit"><span>Update</span></button></div>' . $filterButtons . '</form>';
        echo '<form id="admin-account-browse" method="GET" action="' . $thisPage . '"><div class="admin-filter-browse-group"><input id="admin-filter-search" type="number" name="idFilter" value="' . $browseId . '" placeholder="Appointment ID"><button id="account-browse-submit" class="account-filters-submit" type="submit"><span>Search</span></button></div>' . $browseButtons . '</form>';
          $id = $booking->id;
          $booking_ids = WC_Bookings_Controller::get_bookings_in_date_range($filterDateRaw, $filterDateRaw, null, false);
          foreach ($booking_ids as $key => $value) {
            if($value->status == 'in-cart') {
              $new_array[] = $value;
              unset($booking_ids[$key]);
            }
            if($browseId) {
              if($value->id != $browseId) {
                $new_array[] = $value;
                unset($booking_ids[$key]);
              }
            }
            if($statusFilter) {
              if($statusFilter == 'past') {
                if($value->start >= strtotime($todayRaw->format('Y-m-d'))) {
                  $new_array[] = $value;
                  unset($booking_ids[$key]);
                }
              } elseif($statusFilter == 'future') {
                if($value->start <= strtotime($todayRaw->format('Y-m-d'))) {
                  $new_array[] = $value;
                  unset($booking_ids[$key]);
                }
              } else {
                if($value->status != $statusFilter) {
                  $new_array[] = $value;
                  unset($booking_ids[$key]);
                }
              }
            }
          }
          usort($booking_ids, 'bookingsSortDate');
          if($booking_ids) {
            $bookingCount = count($booking_ids);
            $bookingCountPlural = 's';
            if($bookingCount == 1) { $bookingCountPlural = ''; }
            echo '<p>Showing ' . $bookingCount . ' Appointment' . $bookingCountPlural . '</p>';
            echo '<ol class="admin-booking-list">';
              foreach($booking_ids as $booking) {
                $id = $booking->id;
                if(isset($cancel) && $cancel == $id) {
                  $booking->update_status('cancelled', 'order_notes');
                 }
                $status = $booking->status;
                $ord = wc_get_order($booking->order_id);
                $title = get_the_title($booking->product_id);
                $date = $booking->start;
                $notes = $ord->get_customer_note();
                $company = $ord->get_billing_company();
                $nameFirst = $ord->get_billing_first_name();
                $nameLast = $ord->get_billing_last_name();
                $confirm = wp_nonce_url(admin_url('admin-ajax.php?action=wc-booking-confirm&booking_id=' . $id), 'wc-booking-confirm');

                $statusConfirm = '';
                if($status == 'pending-confirmation') {
                  $status = 'pending';
                  $statusConfirm = '<a href="' . $confirm . '">Click to Confirm Appointment # ' . $id . '</a>';
                }
                echo '<li>';
                  echo '<div class="admin-booking-left"><h2>#' . $id . '<small>' . date('D, M j, Y', $date) . '</small></h2><span>' . $company . '</span><mark class="' . $status . '">' . $status . '</mark></div>';
                  echo '<div class="admin-booking-right"><h3>' . $title . '<small>' . ex_wcParseNotes($notes, 'area') . '</small></h3>' . ex_wcParseNotes($notes, 'services') . ex_wcParseNotes($notes, 'address') . ex_wcParseNotes($notes, 'sqft') . ex_wcParseNotes($notes, 'sup') . ex_wcParseNotes($notes, 'manualj') . $statusConfirm . '</div>';
                echo '</li>';
              }
            echo '</ol>';
          } else {
            $errorMessage = '';
            if($browseId) {
              $errorMessage = 'No appointment with id #' . $browseId . ' found.';
            } elseif($filterDate) {
              $errorMessage = 'No appointments scheduled for ' . $filterDate . '.';
            } elseif($statusFilter) {
              $errorMessage = 'No appointments with the status ' . $statusFilter . '.';
            }
            echo '<p>' . $errorMessage . '</p>';
          }
      echo '</section>';
		  do_action('woocommerce_account_navigation');
    echo ex_wrap('end');
  get_footer();
?>
