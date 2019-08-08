<?php
  $shopId = wc_get_page_id('shop');
  $heading = get_field('heading', $shopId);
  $headingTests = get_field('choose_tests_heading', $shopId);
  $dateSelect = get_field('date_select_disclaimer', $shopId);
  // Make just one step that changes based on wc page type
  $headingSub = '';
  $step1 = get_field('step_one', $shopId);
  $step2 = get_field('step_two', $shopId);
  $step3 = get_field('step_three', $shopId);
  if(is_shop()) {
    $headingSub = $step1['title'];
  } elseif(is_product()) {
    $headingSub = $step2['title'];
  } elseif(is_cart()) {
    $headingSub = $step3['title'];
  } elseif(is_checkout()) {
    $headingSub = 'Please review your inspection details before submitting.';
  }
?>
<header class="schedule-header">
  <h1 class="schedule-heading"><?php echo $heading; ?></h1>
  <?php if(!empty($headingSub)) { echo '<h2 class="schedule-title">' . $headingSub . '</h2>'; } ?>
</header>
