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
    redirect( 'qoutation_member.php' );
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


  <script src="website/js/jquery-3.3.1.min.js"></script> 

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
                  <p class="dropdown-content-p"><a href="login.php?action=logout&redirect=<?php echo get_currenturl(); ?>">Logout</a></p>
                </div>
              </div>  
            <?php } ?>                                           
            <?php else : ?>
              <a href="cart.php" class="icons-btn d-inline-block bag">
                <span class="icon-shopping-bag"></span>
                <?php
                $cart_items = get_cart_items();
                if( !empty( $cart_items ) && is_array( $cart_items ) && count( $cart_items ) > 0 ) {
                  echo '<span class="number">' . count( $cart_items ) . '</span>';
                } ?>
              </a>
              <span style="margin-right: 20px; margin-left: 10px;">|</span> 
              <a href="login.php"> Login or Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="site-navbar bg-white py-2">             

        <div class="container">
          <div class="d-flex align-items-center justify-content-between">
            <div class="logo">
              <div class="site-logo">
                <a href="#" class="js-logo-clone">First Vita Plus</a>
              </div>
            </div>
           <!--  <div class="main-nav d-none d-lg-block">              
           </div> -->
           <div class="icons">
            <nav class="site-navigation text-right text-md-center" role="navigation">
              <ul class="site-menu js-clone-nav d-none d-lg-block">                
                <li><a href="qoutation.php"> Regular</a></li>
                <li class="active"><a href="qoutation_member.php"> Member</a></li>                                  
              </ul>
            </nav>

            <!-- <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a> -->
            <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span class="icon-menu"></span></a>
          </div>
        </div>
      </div>

    </div>


    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Admin</a> <span class="mx-2 mb-0">/</span> <a href="#" class="text-lighted">Qoutaion</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Member</strong></div>
        </div>
      </div>
    </div>

    <div class="site-section">
      <div class="container border">
        <div class="row">            
          <div class="col-md-5">
            <div class="p-4" role="alert">                                  
              <input type="text" class="form-control" id="search" name="search" placeholder="Search Item..." autofocus>
              <div class="display-scanned">                      
                <table  class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                  <div class="display-items">
                    <p class="text-light-p padding"> <span class="icon-shopping-bag"></span> Items</p>
                    <div id="result">                    
                    </div>
                  </div>
                </table>

              </div>                      
            </div>              
          </div>            
          <div class="col-md-7">
            <div class="p-4" role="alert">
              <h2 class="h3 mb-3 text-black">List of Purchases</h2>                            

              <?php
              $product_ids = [];
              $get_cart_items = get_cart_items();
              if ($get_cart_items == false){

              }else{
                if( count( get_cart_items() ) > 0 ) : ?>
                  <div class="row mb-5">
                    <form class="col-md-12" method="POST" action="qoutation_member.php">
                      <div>                          
                        <table class="table">
                          <thead>
                            <tr>                                
                              <th class="product-name">Product</th>
                              <th class="product-price">Price</th>
                              <th class="product-quantity">Qty</th>
                              <th class="product-total">Total</th>
                              <th></th>                                
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
                                  <td class="product-name">
                                    <a href="product.php?p=<?php echo $row['slug']; ?>" class="text-lighted"><?php echo $row['name']; ?></a>
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
                                  <td><a href="qoutation_member.php?action=delete&id=<?php echo $id; ?>" class="btn btn-primary height-auto btn-sm">X</a></td>


                                  <?php 
                                endwhile;
                                echo '</tr>';
                              }
                              $get_product_stmt->close(); ?>
                            <?php endforeach;
                          endif; ?>
                        </tbody>

                      </table>
                      <input type="submit" value="UPDATE CART" class="btn btn-primary btn-sm btn-block update-cart">
                      <input type="hidden" name="product-ids" value="<?php echo serialize( $product_ids ); ?>">
                      <input type="hidden" name="update-cart" value="1">
                    </form>
                    <form method="POST" action="qoutation_member_complete.php">                                      
                      <div class="qoutation-summary">
                        <input type="text" class="form-control" placeholder="Customer name" name="customer-name" required>                     
                        <div class="form-group row space-top">
                          <div class="col-md-4">
                            <!-- none  -->                       
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">Sub Total</label>                        
                          </div>
                          <div class="col-md-4">
                            <strong class="text-black">&#8369; <?php echo number_format( $cart_total, 2); ?></strong>                                                
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-4">
                            <!-- none  -->                       
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">Member</label>                        
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">25% discount</label>                     
                          </div>
                        </div>
                        <div class="form-group row space-top">
                          <div class="col-md-4">
                            <!-- none  -->                       
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">Total</label>                        
                          </div>
                          <div class="col-md-4">
                            <strong class="text-black">&#8369; <?php echo number_format( $cart_total - ($cart_total * (25/100)), 2); ?></strong>
                            <input type="hidden" class="pos-input" id="total" name="total" value="<?php echo$cart_total - ($cart_total * (25/100));?>">                     
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-4">
                            <!-- none  -->                       
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">Amount Recieved</label>                        
                          </div>
                          <div class="col-md-4">
                            <strong class="text-black"><input onkeyup="success()" class="form-control" id="payment" type="text" name="payment"></strong>                       
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-4">
                            <!-- none  -->                       
                          </div>
                          <div class="col-md-4">
                            <label class="text-black">Change</label>                        
                          </div>
                          <div class="col-md-4">
                            <strong class="text-black">&#8369; <input class="change" type="text" id="change" name="change" readonly=""> </strong>                       
                          </div>
                        </div>                                        
                      </div>                  
                      <input id="submitBtn" disabled type="submit" value="PROCCESS" class="btn btn-red btn-lg btn-block" />
                      <input type="hidden" name="finalize-cart" value="1">
                      <input name="cart-sub-total" type="hidden" value="<?php echo $cart_total; ?>">
                      <input name="cart-total" type="hidden" value="<?php echo$cart_total - ($cart_total * (25/100));?>">                  
                    </form>
                  <?php } ?>

                </div>                                          

              </div>              
            </div>
          </div> 
        </div>
      </div>      
    </div>


    <script type="text/javascript">
      $(function(){
        $("#payment").keyup(function(){  
          if($(this).val() == ''){  
            $("#change").val(0);
          }else{

            var payment = parseInt($("#payment").val());
            var total = parseInt($("#total").val());

            $("#change").val(payment - total);
          }
        });
      });

      function success() {
        var payment = parseInt($("#payment").val());
        var total = parseInt($("#total").val());
        if(payment < total || document.getElementById("payment").value==="") { 
          $('#submitBtn').prop('disabled', true); 
        } else { 
          $('#submitBtn').prop('disabled', false); 
        }
      }
    </script>
    <script>
      $(document).ready(function(){

       load_data();

       function load_data(query)
       {
        $.ajax({
         url:"getProducts.php",
         method:"POST",
         data:{query:query},
         success:function(data)
         {
          $('#result').html(data);
        }
      });
      }
      $('#search').keyup(function(){
        var search = $(this).val();
        if(search != '')
        {
         load_data(search);
       }
       else
       {
         load_data();
       }
     });
    });
  </script>

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