<?php
  $shopId = wc_get_page_id('shop');
  $heading = get_field('heading', $shopId);
  $cartCount = WC()->cart->get_cart_contents_count();
  $headingSub = '';
  $step1 = get_field('step_one', $shopId);
  $step2 = get_field('step_two', $shopId);
  $step3 = get_field('step_three', $shopId);
  if(is_shop()) {
    $headingSub = $step1['title'];
  } elseif(is_product()) {
    $headingSub = $step2['title'] . ' for ' . get_the_title();
  } elseif(is_cart()) {
    $headingSub = $step3['title'];
  } elseif(is_checkout()) {
    if(is_wc_endpoint_url( 'order-received' )) {
      $headingSub = 'Your Schedule Request has been placed!';
    } else {
      $headingSub = 'Please review your inspection details before submitting.';
    }
  }
  if($cartCount > 0 && !is_checkout() && !is_cart()) {
    echo '<header class="schedule-cart-warning">';
      echo '<span>You have already started scheduling an inspection.<i>If you proceed with a different inspection, this requested date will be discarded.</i></span>';
      echo ex_cta('arrow', 'Complete Inspection', get_permalink(wc_get_page_id('cart')));
    echo '</header>';
  }
?>

<header class="schedule-header">
  <h1 class="schedule-heading"><?php echo $heading; ?></h1>
  <?php if(!empty($headingSub)) { echo '<h2 class="schedule-title">' . $headingSub . '</h2>'; } ?>
</header>
