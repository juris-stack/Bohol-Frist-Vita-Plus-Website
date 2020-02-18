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
  $cart_total = $_POST['cart-total'];
  $payment = $_POST['payment'];

  if( $payment < $cart_total ){
    $message = "Payment is not enough!";
    echo "<script type='text/javascript'>alert('$message'); window.location.assign('qoutation_member.php')</script>";
  }else {
    $billing_fname = '';
    $billing_lname = '';
    $billing_telephone = '';
    $billing_address = '';
    $get_cart_items = get_cart_items();

    $user_details = [];
    $cart_sub_total = $_POST['cart-sub-total'];
    $cart_total = $_POST['cart-total'];    
    $billing_fname = $_POST['customer-name'];
    $billing_lname = '';
    // $billing_email = esc_email( $_POST['billing-email'] );
    $billing_telephone = '';
    $billing_address = '';
    
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
    $customer_type = 'Member';
    $type = 'Walk-in';
    $status = 'claimed';
    
    $insert_order = $mysqli->prepare( "INSERT INTO orders (type, sub_amount, amount, products, status, customer_type, user_details, user_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)" );
    $insert_order->bind_param( 'sssssssi', $type, $cart_sub_total, $cart_total, $products_serialize, $status, $customer_type, $user_details_serialize, $user_id );
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
    $message = "Proccess successfull!";
    echo "<script type='text/javascript'>alert('$message'); window.location.assign('qoutation_member.php')</script>";
    }
  }
