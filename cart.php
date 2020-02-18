<!DOCTYPE html>
<?php
/**
 * Cart template
 * 
 * @package SJM
 * @author 
 */
require_once 'functions.php';

if( isset( $_GET['action'] ) && $_GET['action'] === 'delete' ) {
    $pid = esc_int( $_GET['id'] );
    $new_cart_items = get_cart_items();
    if( isset( $new_cart_items[$pid] ) ) {
        unset( $new_cart_items[$pid] );
        update_cart_items( $new_cart_items );
        set_site_notice( 'Your cart has been updated.', 'success' );
    }else{
        redirect( 'cart.php' );
    }
}

if( isset( $_POST['update-cart'] ) ) {
    $pids = unserialize( $_POST['product-ids'] );
    foreach ( $pids as $pid ) {
        $qty = esc_int( $_POST['quantity-' . $pid] );
        $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
        $get_product_stmt->bind_param( 'i', $pid );
        $get_product_stmt->execute();
        $get_product_result = $get_product_stmt->get_result();
        if( $get_product_result->num_rows > 0 ) {
            while( $row = $get_product_result->fetch_assoc() ) {
                $stocks = esc_int( $row['stocks'] );
                if( $qty <= $stocks ) {
                    update_cart_item( $pid, $qty );
                }
            }
        }
        $get_product_stmt->close();
    }
    set_site_notice( 'Your cart has been updated.', 'success' );
}

$site_title = 'Cart &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Cart';?>
<html lang="en">
<head>
  <title>First Vita Plus</title>
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
                <a href="index.php" class="js-logo-clone">First Vita Plus</a>
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
          <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Cart</strong></div>
        </div>
      </div>
    </div>



    <div class="site-section">
      <div class="container">
        <?php show_site_notice(); ?>
        <?php
        $product_ids = [];
        $get_cart_items = get_cart_items();
        if( count( get_cart_items() ) > 0 ) : ?>
        <div class="row mb-5">
          <form class="col-md-12" method="POST" action="cart.php">
            <div class="site-blocks-table">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th class="product-thumbnail">Image</th>
                    <th class="product-name">Product</th>
                    <th class="product-price">Price</th>
                    <th class="product-quantity">Qty</th>
                    <th class="product-total">Total</th>
                    <th class="product-remove">Remove</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $cart_total = 0;
                  foreach( get_cart_items() as $id => $q ) :
                    $product_ids[] = $id;
                    $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
                    $get_product_stmt->bind_param( 'i', $id );
                    $get_product_stmt->execute();
                    $get_product_result = $get_product_stmt->get_result();
                    if( $get_product_result->num_rows > 0 ) {
                      echo '<tr class="cart-body">';
                      while( $row = $get_product_result->fetch_assoc() ) : 
                        $price = empty( $row['sale_price'] ) ? $row['price'] : $row['sale_price'];
                        $total_price = esc_float( $price ) * $q;
                        $cart_total += $total_price; ?>
                          <td class="product-thumbnail">
                            <img src="<?php echo get_productimage( $id, 'medium' ); ?>" alt="Image" class="img-cart">
                          </td>
                          <td class="product-name">
                            <h2 class="h5 text-black"><a href="product.php?p=<?php echo $row['slug']; ?>" class="text-lighted"><?php echo $row['name']; ?></a></h2>
                          </td>
                          <td>&#8369; <?php echo number_format($price, 2); ?></td>
                          <td>
                            <select name="quantity-<?php echo $id; ?>" class="form-control">
                              <?php 
                              for( $i = 1; $i <= $row['stocks']; $i++ ) {
                                echo '<option value="' . $i . '" ';
                                selected( $i, $q );
                                echo '>' . $i . '</option>';
                              } ?>
                            </select>

                          </td>
                          <td>&#8369; <?php echo number_format($total_price, 2); ?></td>
                          <td><a href="cart.php?action=delete&id=<?php echo $id; ?>" class="btn btn-primary height-auto btn-sm">X</a></td>
                        <?php 
                      endwhile;
                      echo '</tr>';
                    }
                    $get_product_stmt->close(); ?>
                  <?php endforeach; ?>
                  </tbody>
                       
              </table>
            </div>
          
        </div>
        
        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6 mb-3 mb-md-0">
                <!-- <button class="btn btn-primary btn-sm btn-block">Update Cart</button> -->
                <p><input type="submit" value="UPDATE CART" class="btn btn-primary btn-sm btn-block update-cart"></p>
              </div>
              <div class="col-md-6">
                <a href="products.php"><p class="btn btn-black rounded-0">Continue Shopping</p></a>
              </div>
            </div>            
            <div class="row">
              <div class="col-md-12">
                <!-- // display nothig -->
              </div>
              <div class="col-md-8 mb-3 mb-md-0">
                <!-- // display nothig -->
              </div>
              <div class="col-md-4">
                <!-- // display nothig -->
              </div>
            </div>
          </div>
          <div class="col-md-6 pl-5">
            <div class="row justify-content-end">
              <div class="col-md-7">
                <div class="row">
                  <div class="col-md-12 text-right border-bottom mb-5">
                    <h3 class="text-black h4 text-uppercase">Cart Totals</h3>
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black">Subtotal</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black">&#8369; <?php echo number_format( $cart_total, 2); ?></strong>
                  </div>
                </div>

                <?php if( currentuser_is_customer() ) : ?>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black not">Member</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black not">25% discount</strong>
                  </div>
                </div>

                <div class="row mb-5">
                  <div class="col-md-6">
                    <span class="text-black">Total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black">&#8369; <?php echo number_format( $cart_total, 2); ?></strong>
                  </div>
                </div>

                <?php else : ?>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <span class="text-black">Member</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black">25% discount</strong>
                  </div>
                </div>

                <div class="row mb-5">
                  <div class="col-md-6">
                    <span class="text-black">Total</span>
                  </div>
                  <div class="col-md-6 text-right">
                    <strong class="text-black">&#8369; <?php echo number_format( $cart_total - ($cart_total * (25/100)), 2); ?></strong>
                  </div>
                </div>

                <?php endif; ?>

                <div class="row">
                  <div class="col-md-12">
                    <!-- <button class="btn btn-primary btn-lg btn-block checkout"><a href="checkout.php">Checkout</a></button> -->
                    <p><a href="checkout.php" class="btn btn-red btn-lg btn-block">Checkout</a></p>
                    <input type="hidden" name="product-ids" value="<?php echo serialize( $product_ids ); ?>">
                    <input type="hidden" name="update-cart" value="1">
            </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php else : ?>
              <p>Your shopping cart is empty.</p>
        <div class="row">
          <div class="col-md-6">
            <div class="row mb-5">
              <div class="col-md-6 mb-3 mb-md-0">
                <a href="products.php"><button class="btn btn-outline-primary btn-sm btn-block">Continue Shopping</button></a>
              </div>
            </div>
          </div>          
        </div>
              
        <?php endif; ?>
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