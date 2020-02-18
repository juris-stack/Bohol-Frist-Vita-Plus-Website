<!DOCTYPE html>
<?php
/**
 * Checkout template
 * 
 * @package SJM
 * @author 
 */
require_once 'functions.php';

$billing_fname = '';
$billing_lname = '';
$billing_email = '';
$billing_telephone = '';
$billing_address = '';
$get_cart_items = get_cart_items();

if( user_is_loggedin() ) {
  $billing_fname = get_currentusermeta( 'billing-firstname' );
  $billing_lname = get_currentusermeta( 'billing-lastname' );
  $billing_email = get_currentuser( 'billing-email' );
  $billing_telephone = get_currentusermeta( 'billing-telephone' );
  $billing_address = get_currentusermeta( 'billing-address' );
}

$site_title = 'Checkout &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Checkout';
?>
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
            <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span> <a href="cart.php" class="text-lighted">Cart</a> <span class="mx-2 mb-0">/</span> <strong class="text-black"><?php echo $page_title;?></strong></div>
          </div>
        </div>
      </div>

      <div class="site-section">
        <div class="container">
          <div class="row mb-5">
            <div class="col-md-12">
              <div class="border p-4 rounded" role="alert">
                Please be reminded that you only have 2 days to claim your reservation.
              </div>
            </div>
          </div>        
          <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
              <h2 class="h3 mb-3 text-black">Personal Details</h2>
              <form method="POST" action="reservation_complete.php" class="content">
                <div class="p-3 p-lg-5 border">              
                  <div class="form-group row">
                    <div class="col-md-6">
                      <label for="c_fname" class="text-black">First Name <span class="text-danger">*</span></label>
                      <input required type="text" class="form-control" id="c_fname" name="billing-firstname" value="<?php echo $billing_fname; ?>">
                    </div>
                    <div class="col-md-6">
                      <label  for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                      <input required type="text" class="form-control" id="c_lname" name="billing-lastname" value="<?php echo $billing_lname; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-12">
                      <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                      <input required type="text" class="form-control" id="c_address" name="billing-address" placeholder="" value="<?php echo $billing_address; ?>">
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-12">
                      <label for="c_address" class="text-black">Phone Number<span class="text-danger">*</span></label>                  

                      <input type="number" value="<?php echo $billing_telephone; ?>" name="billing-telephone" class="form-control" id="billing-telephone" pattern="[0-9]{11}" title="PLEASE PUT 11-DIGIT NUMBER WITH COUNTRY CODE" onKeyDown="if(this.value.length==11 && event.keyCode!=8) return false;" required>
                    </div>
                  </div>

                </div>
              </div>

              <div class="col-md-6">
                
                <div class="row mb-5">
                  <div class="col-md-12">
                    <h2 class="h3 mb-3 text-black">Your Reservation</h2>
                    <div class="p-3 p-lg-5 border">
                      <table class="table site-block-order-table mb-5">
                        <thead>
                          <th>Product</th>
                          <th>Total</th>
                        </thead>
                        <tbody>
                          <?php
                          $cart_total = 0;
                          foreach( $get_cart_items as $id => $q ) :
                            $product_ids[] = $id;
                            $get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
                            $get_product_stmt->bind_param( 'i', $id );
                            $get_product_stmt->execute();
                            $get_product_result = $get_product_stmt->get_result();
                            if( $get_product_result->num_rows > 0 ) {
                              echo '<tr>';
                              while( $row = $get_product_result->fetch_assoc() ) : 
                                $price = empty( $row['sale_price'] ) ? $row['price'] : $row['sale_price'];
                                $total_price = esc_float( $price ) * $q;
                                $cart_total += $total_price; ?>
                                <td><span class="text-lighted"><?php echo $row['name'] . ' X ' . $q; ?></span></td>
                                <td class="td-total">&#8369; <?php echo $total_price; ?></td>
                                <?php 
                              endwhile;
                              echo '</tr>';
                            }
                            $get_product_stmt->close(); ?>
                          <?php endforeach; ?>                      
                          <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong></td>
                          <td class="text-black">&#8369; <?php echo number_format( $cart_total, 2); ?></td>
                        </tr>
                        <?php if( currentuser_is_customer() ) : ?>
                          <tr>
                            <td class="text-black font-weight-bold not"><strong>Member</strong></td>
                            <td class="text-black font-weight-bold not"><strong>25% discount</strong></td>
                          </tr>
                          <tr>
                            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                            <td class="text-black font-weight-bold"><strong>&#8369; <?php echo number_format( $cart_total, 2); ?></strong></td>
                          </tr>
                          <?php else : ?>
                            <tr>
                              <td class="text-black font-weight-bold"><strong>Member</strong></td>
                              <td class="text-black font-weight-bold"><strong>25% discount</strong></td>
                            </tr>
                            <tr>
                              <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                              <td class="text-black font-weight-bold"><strong>&#8369; <?php echo number_format( $cart_total - ($cart_total * (25/100)), 2); ?></strong></td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>

                      <div class="form-group">                    
                        <button class="btn btn-red btn-lg btn-block" type="submit" value="Finalize Order">Place Reservation</button>
                      </div>
                      <input type="hidden" name="finalize-cart" value="1">
                      <?php if( currentuser_is_member() ) : ?>                    
                        <input name="cart-sub-total" type="hidden" value="<?php echo $cart_total; ?>">
                        <input name="cart-total" type="hidden" value="<?php echo $cart_total - ($cart_total * (25/100)); ?>">
                        <?php else : ?>
                          <input name="cart-sub-total" type="hidden" value="<?php echo $cart_total; ?>">
                          <input name="cart-total" type="hidden" value="<?php echo $cart_total; ?>">
                        <?php endif; ?>
                      </form>

                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!-- </form> -->
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