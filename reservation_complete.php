<?php
/**
 * Order complete template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

$billing_fname = '';
$billing_lname = '';
$billing_telephone = '';
$billing_address = '';
$get_cart_items = get_cart_items();

if( isset( $_POST['finalize-cart'] ) ) {
  $user_details = [];
  $cart_sub_total = $_POST['cart-sub-total'];
  $cart_total = $_POST['cart-total'];
  $billing_fname = esc_str( $_POST['billing-firstname'] );
  $billing_lname = esc_str( $_POST['billing-lastname'] );
    // $billing_email = esc_email( $_POST['billing-email'] );
  $billing_telephone = esc_str( $_POST['billing-telephone'] );
  $billing_address = esc_str( $_POST['billing-address'] );

  $user_details = array(
    'billing' => array(
      'firstname' => $billing_fname,
      'lastname' => $billing_lname,
            // 'email' => $billing_email,
      'telephone' => $billing_telephone,
      'address' => $billing_address,
    )
  );

  $products_serialize = serialize( $get_cart_items );
  $user_details_serialize = serialize( $user_details );
  $type = 'Reservation';
  $status = 'pending';

  $insert_order = $mysqli->prepare( "INSERT INTO orders (type, sub_amount, amount, products, status, user_details, user_ID) VALUES (?, ?, ?, ?, ?, ?, ?)" );
  $insert_order->bind_param( 'ssssssi', $type, $cart_sub_total, $cart_total, $products_serialize, $status, $user_details_serialize, $user_id );
  $insert_order->execute();
  $reference = $mysqli->insert_id;
  $insert_order->close();

  foreach ( $get_cart_items as $id => $val ) {
    $get_stocks = esc_int( get_productby( 'stocks', $id ) );
    $stock = $get_stocks - $val;
    update_product( 'stocks', $stock, $id );
  }    
  /** Empty the cart */
  remove_cart_items();

    // Update biling information
  update_usermeta( 'billing-firstname', $billing_fname, $user_id );
  update_usermeta( 'billing-lastname', $billing_lname, $user_id );
  update_usermeta( 'billing-telephone', $billing_telephone, $user_id );
  update_usermeta( 'billing-address', $billing_address, $user_id );


  /** Send email notification to admin */
    // $to = get_siteinfo( 'company-email' );
    // $subject = 'New Order Received &mdash; ' . get_siteinfo( 'site-name' );
    // $mssg = "A new order was received with reference # $reference \n";
    // $mssg .= "Visit this link to view order " . site_url( '/admin/order.php?id=' . $reference );
    // send_mail( $to, $subject, $mssg );

  /** Push notification */
  push_notification( $reference, 'order', 'New order recieved' );

  /** Show notice to front-end user */
  // set_site_notice( 'Your order has been submitted. Please pay your order at the earliest time of your convenience.', 'success' );
  header("Location: order_reciept.php?order_id=$reference");
}



?>
