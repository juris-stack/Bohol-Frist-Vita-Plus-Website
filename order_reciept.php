<!DOCTYPE html>
<?php
/**
 * Order complete template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

if( empty( $_GET['order_id'] ) ) {
  redirect( 'checkout.php' );
}

$order_id = $_GET['order_id'];

$user = get_currentuser( $user_id );
$order_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE ID = ? LIMIT 1" );
$order_stmt->bind_param( 'i', $order_id );
$order_stmt->execute();
$order_result = $order_stmt->get_result();
if( $order_result->num_rows > 0 ) {
  while( $row = $order_result->fetch_assoc() ) {
    $reference = $row['ID'];
    $user_details = unserialize( $row['user_details'] );
    $billing = $user_details['billing'];
        // $shipping = $user_details['shipping'];
    $status = $row['status'];
    $products = unserialize( $row['products'] );
    $sub_amount = $row['sub_amount'];
    $amount = $row['amount'];
    $date = $row['date_added'];
  }
}
$order_stmt->close();


?>
<html lang="en">
<head>
  <title>Bohol First Vita Plus</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700"> 
  <link rel="stylesheet" href="website/fonts/icomoon/style.css">

  <link rel="stylesheet" href="website/css/bootstrap.min.css">
  <link rel="stylesheet" href="website/css/magnific-popup.css">
  <link rel="stylesheet" href="website/css/jquery-ui.css">
  <link rel="stylesheet" href="website/css/owl.carousel.min.css">
  <link rel="stylesheet" href="website/css/owl.theme.default.min.css">


  <link rel="stylesheet" href="website/css/aos.css">

  <link rel="stylesheet" href="website/css/style.css">

</head>
<body>


  <div class="site-wrap">

    <div class="top-nav">
      <div class="top-nav-wrapper">
        <div class="top-nav-content">        
          <?php
          if( user_is_loggedin() ) : ?>        
            <?php $role = get_currentuser( 'role' );
            if( $role == '4' ) {
              ?>
              <div class="dropdown role">
                <img src="<?php echo get_userimage( $user_id ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>">
                <a href="#"><?php echo get_currentuser( 'username' ); ?>'s Account <span class="icon-caret-down"></span></a>            
                <div class="dropdown-content">
                  <p class="dropdown-content-p"><a href="admin/index.php">Dashboard</a></p>
                  <p class="dropdown-content-p"><a href="admin/user.php">Account</a></p>
                  <p class="dropdown-content-p"><a href="login.php?action=logout&redirect=<?php echo get_currenturl(); ?>">Logout</a></p>
                </div>
              </div>
            <?php } else if ( $role == '3' ){?>  
              <div class="dropdown role">
                <img src="<?php echo get_userimage( $user_id ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>">
                <a href="#"><?php echo get_currentuser( 'username' ); ?>'s Account <span class="icon-caret-down"></span></a>            
                <div class="dropdown-content">
                  <p class="dropdown-content-p"><a href="admin/index.php">Dashboard</a></p>
                  <p class="dropdown-content-p"><a href="admin/user.php">Account</a></p>
                  <p class="dropdown-content-p"><a href="login.php?action=logout&redirect=<?php echo get_currenturl(); ?>">Logout</a></p>
                </div>
              </div>        
            <?php } else { ?>
              <a href="cart.php" class="icons-btn d-inline-block bag">
                <span class="icon-shopping-bag"></span>
                <?php
                $cart_items = get_cart_items();
                if( !empty( $cart_items ) && is_array( $cart_items ) && count( $cart_items ) > 0 ) {
                  echo '<span class="number">' . count( $cart_items ) . '</span>';
                } ?>
              </a>            
              <span style="margin-right: 20px; margin-left: 20px;">|</span>                  
              <div class="dropdown">
                <img src="<?php echo get_userimage( $user_id ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>">
                <a href="#"><?php echo get_currentuser( 'username' ); ?>'s Account <span class="icon-caret-down"></span></a>            
                <div class="dropdown-content">
                  <p class="dropdown-content-p"><a href="admin/index.php">Dashboard</a></p>
                  <p class="dropdown-content-p"><a href="admin/user.php">Account</a></p>
                  <p class="dropdown-content-p"><a href="login.php?action=logout&redirect=index.php">Logout</a></p>
                </div>
              </div>  
            <?php } ?>                                           
            <?php else : ?>
              <a href="login.php" class="icons-btn d-inline-block bag">
                <span class="icon-shopping-bag"></span>
                <!-- <?php
                $cart_items = get_cart_items();
                if( !empty( $cart_items ) && is_array( $cart_items ) && count( $cart_items ) > 0 ) {
                  echo '<span class="number">' . count( $cart_items ) . '</span>';
                } ?> -->
              </a>
              <span style="margin-right: 20px; margin-left: 10px;">|</span> 
              <a href="login.php"> Login or Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="site-navbar bg-white py-2">

        <div class="search-wrap">
          <div class="container">
            <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
            <form id="header-search" action="search.php">
              <input type="text" name="s" class="form-control" placeholder="Search keyword and hit enter...">
            </form>  
          </div>
        </div>      

        <div class="container">
          <div class="d-flex align-items-center justify-content-between">
            <div class="logo">
              <div class="site-logo">
                <a href="index.php" class="js-logo-clone">Bohol First Vita Plus</a>
              </div>
            </div>
            <div class="main-nav d-none d-lg-block">
              <nav class="site-navigation text-right text-md-center" role="navigation">
                <ul class="site-menu js-clone-nav d-none d-lg-block">                
                  <li><a href="index.php">Home</a></li>
                  <li><a href="products.php">Products</a></li>
                  <li><a href="about.php">About</a></li>
                  <li><a href="contact.php">Contact</a></li>                
                </ul>
              </nav>
            </div>
            <div class="icons">
              <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
              <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
            </div>
          </div>
        </div>

      </div>
      
      <div class="bg-light py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span> <a href="cart.php" class="text-lighted">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black"><?php echo $page_title;?></strong></div>          
          </div>
        </div>
      </div> 
<?php
          function itexmo($number,$message,$apicode){
              $ch = curl_init();
              $itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
              curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 
                  http_build_query($itexmo));
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            return curl_exec ($ch);
              curl_close ($ch);}
                    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE stocks <= 5 ORDER BY name ASC LIMIT 8" );
                    $get_products_stmt->execute();
                    $get_products_result = $get_products_stmt->get_result();
                    if( $get_products_result->num_rows > 0 ) :
                        while( $row = $get_products_result->fetch_assoc() ) : ?>
                          
                          <?php     
                            //====================SMS
          
          $get_number=get_siteinfo( 'company-phone' );
          
          $result = itexmo("$get_number","Stock for ".$row['name']." is running out! You only have ".$row['stocks']." stock(s) available.\n\n-Bohol First Vita Plus","ST-TANNY420621_B7B7J");
        if ($result == ""){
        echo "";  
        }else if ($result == 0){
        echo "";
        }
        else{ 
        echo "Error Num ". $result . " was encountered!";
        }
            

//====================SMS ?>
                              
                              
                    <?php 
                        endwhile;
                    endif; ?>

      <div class="site-section">
        <div class="container">
          <div class="row">
            <div class="col-md-12 text-center">
              <span class="icon-check_circle display-3 text-success"></span>
              <h2 class="display-3 text-black">Thank you!</h2>
              <p class="lead mb-5">Your reservation was successfuly completed.</p>            
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 text-center border">
              <p class="margin-top text-black">Order # : <?php echo $reference;?></p>
              <hr>
              <p>Please have this amount ready.</p>
              <p><strong class="text-primary h4"> &#8369; <?php echo number_format( $amount, 2 );?></strong></p>
              

              <div class="border">              
                <p>For more details, track your reservation under your <strong><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $reference ); ?>">Account</a></strong></p>                          
              </div>
              


              <p class="margin-top"><a href="products.php" class="btn btn-sm height-auto px-4 py-3 btn-primary">Continue Shopping</a></p>
            </div>
            <div class="col-md-4"></div>
          </div>
        </div>
      </div>

      

      <?php include 'footer.php';?> 
    </div>

    <script src="website/js/jquery-3.3.1.min.js"></script>
    <script src="website/js/jquery-ui.js"></script>
    <script src="website/js/popper.min.js"></script>
    <script src="website/js/bootstrap.min.js"></script>
    <script src="website/js/owl.carousel.min.js"></script>
    <script src="website/js/jquery.magnific-popup.min.js"></script>
    <script src="website/js/aos.js"></script>

    <script src="website/js/main.js"></script>

  </body>
  </html>