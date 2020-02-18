<?php
/* 
* Search template
* 
* @package SJM
* @author 
*/

require_once 'functions.php';

if( empty( $_GET['s'] ) ) {
    redirect( site_url() );
}

$s = esc_str( $_GET['s'] );

$site_title = 'Search Result &mdash; ' . get_siteinfo( 'site-name' );
$page_title = 'Search result for "' . $s . '"';
?>

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

      <div class="col-md-12 content">
        <div class="row">
          <?php
          $search = "%$s%";
          $products_select_stmt = $mysqli->prepare( "SELECT * FROM products WHERE name LIKE ? AND status='published' AND stocks > 0 ORDER BY name ASC" );
          $products_select_stmt->bind_param( 's', $search );
          $products_select_stmt->execute();
          $products_select_result = $products_select_stmt->get_result();
          if( $products_select_result->num_rows > 0 ) : ?>
             <?php while( $row = $products_select_result->fetch_assoc() ) : ?>

                <div class="col-lg-3 col-md-6 item-entry mb-4">
                    <a href="product.php?p=<?php echo $row['slug']; ?>" class="product-item md-height bg-gray d-block">
                      <img src="<?php echo get_productimage( $row['ID'], 'medium' ); ?>" alt="<?php echo $row['name']; ?>" class="img-fluid">
                  </a>
                  <h2 class="item-title"><a href="product.php?p=<?php echo $row['slug']; ?>"><?php echo $row['name']; ?></a></h2>
                  <strong class="item-price">
                      <span class="price">Price: &#8369; <?php
                      if( (int) $row['sale_price'] > 0 ) {
                        echo '<span class="not">' . number_format($row['price'], 2) . '</span>';
                    }else{
                        echo number_format($row['price'], 2);
                    }
                    if( (int) $row['sale_price'] > 0 ) {
                        echo ' ' . number_format($row['sale_price'], 2);
                    } ?>
                </span>
            </strong>
        </div>
    <?php endwhile; ?>
    <?php else : ?>
        <div class="col-md-12">                    
            <div class="row">
                <div class="title-section mb-5 col-12">
                  <h2 class="text-uppercase">We couldn't find <span style="color: #a94442;"><?php echo $s;?></span> for sale.</h2>
              </div>
          </div>                    
          <div class="row line-top">
            <div class="col-md-12 section-title-medium">
                <h2 class="item-title">You may also like</h2>
            </div>                        

            <?php
            $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='published' AND stocks > 0 ORDER BY ID DESC LIMIT 8" );
            $get_products_stmt->execute();
            $get_products_result = $get_products_stmt->get_result();
            if( $get_products_result->num_rows > 0 ) :
                while( $row = $get_products_result->fetch_assoc() ) : ?>
                    <div class="col-lg-3 col-md-6 item-entry mb-4">
                        <a href="product.php?p=<?php echo $row['slug']; ?>" class="product-item md-height bg-gray d-block">
                          <img src="<?php echo get_productimage( $row['ID'], 'medium' ); ?>" alt="<?php echo $row['name']; ?>" class="img-fluid">
                      </a>
                      <h2 class="item-title"><a href="product.php?p=<?php echo $row['slug']; ?>"><?php echo $row['name']; ?></a></h2>
                      <strong class="item-price">
                          <span class="price">Price: &#8369; <?php
                          if( (int) $row['sale_price'] > 0 ) {
                            echo '<span class="not">' . number_format($row['price'], 2) . '</span>';
                        }else{
                            echo number_format($row['price'], 2);
                        }
                        if( (int) $row['sale_price'] > 0 ) {
                            echo ' ' . number_format($row['sale_price'], 2);
                        } ?>
                    </span>
                </strong>
            </div>
            <?php
        endwhile;
    endif;
    $get_products_stmt->close(); ?>
</div>
</div>
<?php endif; $products_select_stmt->close(); ?>
</div>


</div>
</div>
</div>

<footer class="site-footer custom-border-top">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
        <h3 class="footer-heading mb-4">Promo</h3>
        <a href="#" class="block-6">
          <img src="" alt="Image placeholder" class="img-fluid rounded mb-4">
          <h3 class="font-weight-light text-lighted mb-0">Purchase now and earn additional income!</h3>
          <p>Promo from  July 15 &mdash; 25, 2019</p>
      </a>
  </div>
  <div class="col-lg-5 ml-auto mb-5 mb-lg-0">
    <div class="row">
      <div class="col-md-12">
        <h3 class="footer-heading mb-4">Quick Links</h3>
    </div>
    <div class="col-md-6 col-lg-4">
        <ul class="list-unstyled">
          <li><a href="#">Home</a></li>
          <li><a href="#">About</a></li>
          <li><a href="#">Contact</a></li>
      </ul>
  </div>
  <div class="col-md-6 col-lg-4">
    <ul class="list-unstyled">
      <li><a href="#">Products</a></li>
      <li><a href="#">Promo</a></li>

  </ul>
</div>
<div class="col-md-6 col-lg-4">
    <ul class="list-unstyled">
      <li><a href="#">Dealer Rules and Regulations</a></li>
  </ul>
</div>
</div>
</div>

<div class="col-md-6 col-lg-3">
    <div class="block-5 mb-5">
      <h3 class="footer-heading mb-4">Contact Info</h3>
      <ul class="list-unstyled">
        <li class="address"><?php echo get_siteinfo( 'company-address' ); ?></li>
        <li class="phone"><?php echo get_siteinfo( 'company-phone' ); ?></li>
        <li class="email"><?php echo get_siteinfo( 'company-email' ); ?></li>
    </ul>
</div>

</div>
</div>
<div class="row pt-5 mt-5 text-center">
  <div class="col-md-12">
    <p>
      Copyright &copy; <script>document.write(new Date().getFullYear());</script>
  </p>
</div>

</div>
</div>
</footer>
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