<!DOCTYPE html>
<?php require_once 'functions.php';?>
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

    <?php include 'top-nav.php';?>

      <div class="site-navbar bg-white py-2">

      <div class="site-blocks-cover">
        <div class="container">
          <div class="row">
            <div class="col-md-6 ml-auto order-md-2 align-self-start">
              <div class="site-block-cover-content">
                <h2 class="sub-title">Purchase now and earn additional income!</h2>
                <h1 style="color: #1ABC9C">Membership</h1>
                <?php
                if( user_is_loggedin() ) : ?>
                  <p><a href="products.php" class="btn btn-black rounded-0">Shop Now</a></p>                                                
                  <?php else : ?>
                    <p><a href="login.php" class="btn btn-black rounded-0">Login or Register</a></p> 
                  <?php endif; ?>
              </div>
            </div>
            <div class="col-md-6 order-1">
              <img src="website/images/banner.png" alt="Image" class="img-fluid">
            </div>
          </div>
        </div>
      </div>

      <div class="site-section">
        <div class="container">
          <div class="title-section mb-5">
            <h2 class="text-uppercase"><span class="d-block">Discover</span>Bohol First Vita Plus</h2>
          </div>
          <div class="row align-items-stretch">
            <div class="col-lg-8">
              <div class="product-item sm-height full-height bg-gray">
                <a href="about.php" class="product-category text-center">What is First Vita Plus</a>
                <img src="website/images/about.png" alt="Image" class="img-fluid">                
              </div>
            </div>
            <div class="col-lg-4">
              <div class="product-item sm-height bg-gray mb-4">
                <a href="dealership.php" class="product-category" style="margin-top: 10%">Dealership</a>
                <img src="website/images/dealer.png" alt="Image" class="img-fluid" style="margin-top: -12.5%">
              </div>

              <div class="product-item sm-height bg-gray">
                <a href="contact.php" class="product-category" style="margin-top: 10%">Locate Us</a>
                <img src="website/images/location.png" alt="Image" class="img-fluid" style="margin-top: -12.5%">
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="site-section">
        <div class="container">
          <div class="row">
            <div class="title-section mb-5 col-12">
              <h2 class="text-uppercase">Newest Products</h2>
            </div>
          </div>
          <div class="row">
            <?php
            $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='published' AND stocks > 0 ORDER BY ID DESC LIMIT 6" );
            $get_products_stmt->execute();
            $get_products_result = $get_products_stmt->get_result();
            if( $get_products_result->num_rows > 0 ) :
              while( $row = $get_products_result->fetch_assoc() ) : ?>
                <div class="col-lg-4 col-md-6 item-entry mb-4">
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