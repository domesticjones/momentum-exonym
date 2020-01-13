<?php
  /* TEMPLATE NAME: Admin Clients */
  if(!is_user_logged_in() || !current_user_can('manage_woocommerce')) {
    wp_redirect(get_permalink(wc_get_page_id('myaccount')), 302);
    exit;
  }
  get_header();
    echo ex_wrap('start', 'account');
      echo '<section class="account-data">';
        echo '<h1>Clients</h1>';
        $clientsQueryArgs = array(
        	'role'           => 'Customer',
        	'number'         => '-1',
        	'order'          => 'ASC',
        	'orderby'        => 'user_name',
        );
        $clientsQuery = new WP_User_Query($clientsQueryArgs);
        $clientsRaw = [];
        if(!empty($clientsQuery->results)) {
          	foreach($clientsQuery->results as $user) {
              $userData = get_userdata($user->ID);
              $userDump = get_user_meta($user->ID);
              $company = array('company' => $userDump['billing_company'][0]);
              $name = array('name' => $userDump['first_name'][0] . ' ' . $userDump['last_name'][0]);
              $contact = array('contact' => $userDump['billing_phone'][0] . '<br />' . $userData->user_email);
              $addressL1 = $userDump['billing_address_1'][0];
              $addressL2 = ($userDump['billing_address_2'][0] ? ', ' . $userDump['address_2'][0] : '');
              $addressC = $userDump['billing_city'][0];
              $addressS = $userDump['billing_state'][0];
              $addressP = $userDump['billing_postcode'][0];
              $address = array('address' => $addressL1 . $addressL2 . '<br />' . $addressC . ', ' . $addressS . ' ' . $addressP);
              $accounts = array('accounts' => $userDump['accounts_person'][0] . '<br />' . $userDump['accounts_email'][0]);
              array_push($clientsRaw, $company + $name + $address + $contact + $accounts);
          	}
        }
        if($clientsRaw) {
          usort($clientsRaw, function($a, $b) { return $a['company'] <=> $b['company']; });
          echo '<table id="admin-clients">';
            echo '<thead>';
              echo '<tr><th>Company</th><th>User</th><th>Accounts</th></tr>';
            echo '</thead>';
            echo '<tbody>';
              foreach($clientsRaw as $c) {
                echo '<tr>';
                  echo '<td><p><strong>' . $c['company'] . '</strong><br />' . $c['address']. '</p></td>';
                  echo '<td><p>' . $c['name'] . '<br />' . $c['contact'] . '</p></td>';
                  echo '<td><p>' . $c['accounts'] . '</p></td>';
                echo '</tr>';
              }
            echo '</tbody>';
          echo '</table>';
        }
      echo '</section>';
		  do_action('woocommerce_account_navigation');
    echo ex_wrap('end');
  get_footer();
?>
