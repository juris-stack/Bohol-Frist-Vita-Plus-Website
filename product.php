<!DOCTYPE html>
<?php
/* 
 * Product single template
 * 
 * @package SJM
 * @author 
 */

require_once 'functions.php';

if( empty( $_GET['p'] ) ) {
  redirect( 'index.php' );
}

$slug = $_GET['p'];

$product_name = '';
$product_id = '';
$excerpt = '';
$description = '';
$price = '';
$sale_price = '';
$stocks = 0;
$sku = '';
$category = '';
$brand = '';
$get_product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE slug = ? LIMIT 1" );
$get_product_stmt->bind_param( 's', $slug );
$get_product_stmt->execute();
$get_product_result = $get_product_stmt->get_result();
if( $get_product_result->num_rows > 0 ) {
  while( $row = $get_product_result->fetch_assoc() ) {
    $product_name = $row['name'];
    $product_id = $row['ID'];
    $excerpt = $row['excerpt'];
    $description = $row['description'];
    $price = $row['price'];
    $sale_price = $row['sale_price'];
    $stocks = $row['stocks'];
    // $sku = $row['sku'];
    $category = $row['category'];
    // $brand = $row['brand'];
  }
}
$get_product_stmt->close();

$site_title = $product_name . ' &mdash; ' . get_siteinfo( 'site-name' );
$page_title = $product_name;?>
<html lang="en">
<head>
  <title>First Vita Plus <?php echo $page_title;?></title>
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

    <?php include 'top-nav.php';?>

    <div class="bg-light py-3">
      <div class="container">
        <div class="row">
          <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span> <a href="products.php" class="text-lighted">Products</a> <span class="mx-2 mb-0">/</span> <strong class="text-black"><?php echo $page_title;?></strong></div>
        </div>
      </div>
    </div>

      

    <div class="site-section">
      <div class="container">
        <?php show_site_notice(); ?>
        <div class="row">
          <div class="col-md-6">
            <div class="item-entry">
              <a href="#" class="product-item md-height bg-gray d-block">
                <img src="<?php echo get_productimage( $product_id ); ?>" alt="<?php echo $product_name; ?>" class="img-fluid">
              </a>

            </div>

          </div>
          <div class="col-md-6">
            <h2 class="text-black"><?php echo $page_title;?></h2>
            <p><?php echo $description;?></p>
            <p><strong class="text-primary h4">
              &#8369; <?php
              if( (int) $sale_price > 0 ) {
                echo '<span class="not">' . number_format($price, 2) . '</span>';
              }else{
                echo '<span>' . number_format($price, 2) . '</span>';
              }
              if( (int) $sale_price > 0 ) {
                echo ' <span>' . number_format($sale_price, 2) . '</span>';
              } ?>
            </strong></p>
            <div class="mb-5">
              <?php if( $stocks > 0 ) : ?>
                <p class="in-stock"><span class="icon-check-circle"></span> <?php echo $stocks; ?> in stock</p>
                <?php if( user_is_loggedin() ) : ?>                    
                  <?php
                  $role = get_currentuser( 'role' );
                  if( $role == '4' ) {
                    ?>
                    <p><a href="<?php echo site_url( '/admin/product_edit.php?id=' . $product_id . '&action=edit' ); ?>" class="btn btn-black rounded-0">Edit Product</a></p>
                    <?php
                  }else if( $role == '3' ) {
                    ?>
                    <p><a href="<?php echo site_url( '/admin/product_edit.php?id=' . $product_id . '&action=edit' ); ?>" class="btn btn-black rounded-0 btn-disabled" disabled="disabled">Edit Product</a></p>
                    <?php
                  } else {
                    ?>                      
                    <form class="form-inline" method="POST" action="">
                      <div class="form-group">
                        <select class="form-control not-rounded" name="quantity">
                          <?php 
                          for( $i = 1; $i <= $stocks; $i++ ) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                          } ?>
                        </select> 
                                            
                        <button type="submit" class="btn btn-primary add-to-cart primary-green"><span class="icon-shopping-bag"></span> Add to Cart</button>
                                                                      
                      </div>
                      <input type="hidden" value="<?php echo $product_id; ?>" name="product-id">
                      <input type="hidden" value="1" name="add-to-cart">
                    </form>
                 <?php } ?>
                  <?php else : ?>                    
                      <div class="form-group form-inline">
                        <select class="form-control not-rounded" name="quantity">
                          <?php 
                          for( $i = 1; $i <= $stocks; $i++ ) {
                            echo '<option value="' . $i . '">' . $i . '</option>';
                          } ?>
                        </select>                      
                        <a href="login.php"><button type="submit" class="btn btn-primary add-to-cart primary-green"><span class="icon-shopping-bag"></span> Add to Cart</button></a>
                      </div>                      
                  <?php endif ;?>
                  <?php else : ?>
                    <span class="red">Out of stock</span>
                  <?php endif; ?>
                </div>

              </div>
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