<?php


		$booking_id = 395;
		$booking         = get_wc_booking( $booking_id ); // Object
		$event_id        = $booking->get_google_calendar_event_id(); // String of Google Calendar Event ID
		$product_id      = $booking->get_product_id(); // Integer
		$order           = $booking->get_order(); // Object
		$product         = wc_get_product( $product_id ); // Object
		$booking_product = get_wc_product_booking( $product_id ); // Object
		$resource        = $booking_product->get_resource( $booking->get_resource_id() ); // Bool or Int -- Unused in this instance
		$timezone        = wc_booking_get_timezone_string(); // String
		$description     = ''; // String
		$customer        = $booking->get_customer(); // Object

		// MODIFIED: Customer Note Parsing & Order Details
		$notes 					 = $order->get_customer_note();
		$company				 = $order->get_billing_company();
		$bookingServices = strip_tags(ex_wcParseNotes($notes, 'services'));
		$bookingSup			 = strip_tags(ex_wcParseNotes($notes, 'sup'));
		$bookingAddress	 = strip_tags(str_replace('<br />', ', ', ex_wcParseNotes($notes, 'address')));
		$bookingLot	 	 	 = strip_tags(ex_wcParseNotes($notes, 'lot'));
		$bookingSub	 	 	 = strip_tags(ex_wcParseNotes($notes, 'sub'));
		$bookingArea	 	 = strip_tags(str_replace('Area: ', '', ex_wcParseNotes($notes, 'area')));
		$bookingSqft		 = strip_tags(ex_wcParseNotes($notes, 'sqft'));
		$bookingManj		 = strip_tags(ex_wcParseNotes($notes, 'manualj-raw'));
		$productTitle 	 = $product ? html_entity_decode( $product->get_title() ) : __( 'Booking', 'woocommerce-bookings' );
		$websiteUrl			 = get_site_url();

		// TITLE : {{Lot/Block}}, {{CompanyName}}: {{Service}} - {{SubDivision}}
	 $gCalBookingTitle = sprintf('%s, %s: %s - %s', $bookingLot, $company, $productTitle, $bookingSub);

	 // DESCRIPTION: {{List of Services}} -- Builder/HVAC: {{Supervisor Name}} {{Phone}} -- Square Feet: {{SqFt}} -- Address: {{Address}} -- Manual J
	 $gCalBookingDesc = sprintf('Services: %s', $productTitle) . PHP_EOL;
	 $gCalBookingDesc .= sprintf('Builder/HVAC: %s', $bookingSup) . PHP_EOL;
	 $gCalBookingDesc .= sprintf('SqFt: %s', $bookingSqft) . PHP_EOL;
	 $gCalBookingDesc .= sprintf('Address: %s', $bookingAddress) . PHP_EOL;
	 $gCalBookingDesc .= $bookingManj ? sprintf('Manual J: %s', $bookingManj) . PHP_EOL : '';
	 $gCalBookingDesc .= PHP_EOL . 'Website Data' . PHP_EOL . sprintf('Booking ID: %s -- View on Website: %s/account/admin-bookings/?idFilter=%s', $booking_id, $websiteUrl, $booking_id ) . PHP_EOL;

		 		$booking_data = array(
		 			__( 'Booking ID', 'woocommerce-bookings' )   => $booking_id,
		 			__( 'Booking Type', 'woocommerce-bookings' ) => is_object( $resource ) ? $resource->get_title() : '',
		 			__( 'Persons', 'woocommerce-bookings' )      => $booking->has_persons() ? array_sum( $booking->get_persons() ) : 0,
		 		); // Object
		 		foreach ( $booking_data as $key => $value ) {
		 			if ( empty( $value ) ) {
		 				continue;
		 			}

					// MODIFIED: Removed Description
		 			// $description .= sprintf( '%s: %s', rawurldecode( $key ), rawurldecode( $value ) ) . PHP_EOL; // string(16) "Booking ID: 323
		 		}

		 		$edit_booking_url = admin_url( sprintf( 'post.php?post=%s&action=edit', $booking_id ) ); // string(60) "//localhost:4000/wp-admin/post.php?post=323&action=edit"

		 		// Add read-only message.
		 		/* translators: %s URL to edit booking */

				// MODIFIED: Removed Description
		 		// $description .= PHP_EOL . sprintf( __( 'NOTE: this event cannot be edited in Google Calendar. If you need to make changes, <a href="%s" target="_blank">please edit this booking in WooCommerce</a>.', 'woocommerce-bookings' ), $edit_booking_url );

		 		if ( is_a( $order, 'WC_Order' ) ) {
		 			foreach ( $order->get_items() as $order_item_id => $order_item ) {
		 				if ( $order_item_id !== WC_Booking_Data_Store::get_booking_order_item_id( $booking_id ) ) {
		 					continue;
		 				}
		 				foreach ( $order_item->get_meta_data() as $order_meta_data ) {
		 					$the_meta_data = $order_meta_data->get_data();

		 					if ( is_serialized( $the_meta_data['value'] ) ) {
		 						continue;
		 					}

							// MODIFIED: Removed Description
		 					// $description .= sprintf( '%s: %s', html_entity_decode( $the_meta_data['key'] ), html_entity_decode( $the_meta_data['value'] ) ) . PHP_EOL;
		 				}
		 			}
		 		}
		 		$event = $this->get_event_resource( $booking_id );
		 		if ( empty( $event ) ) {
		 			$event = new Google_Service_Calendar_Event();
		 		}

		 		// If the user edited the description on the Google Calendar side we want to keep that data intact.
		 		if ( empty( trim( $event->getDescription() ) ) ) {
					// MODIFIED: Changed GCal Event Description
		 			// $event->setDescription( wp_kses_post( $description ) );
		 			$event->setDescription( wp_kses_post( $gCalBookingDesc ) );
		 		}

		 		// Set the event data.
				// MODIFIED: Changed GCal Event Title
		 		// $product_title = $product ? html_entity_decode( $product->get_title() ) : __( 'Booking', 'woocommerce-bookings' );
		 		// $event->setSummary( wp_kses_post( sprintf( "%s, %s - #%s", $customer->name, $product_title, $booking->get_id() ) ) );
		 		$event->setSummary( wp_kses_post( $gCalBookingTitle ) );


		var_dump($gCalBookingDesc);
