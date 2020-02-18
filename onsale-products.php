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

      <div class="bg-light py-3">
        <div class="container">
          <div class="row">
            <div class="col-md-12 mb-0"> <a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span><a href="products.php" class="text-lighted">Products</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Sale Products</strong></div>
          </div>
        </div>
      </div>


      <div class="site-section">
        <div class="container">
          <div class="row">
            <div class="title-section mb-5 col-12">
              <h2 class="text-uppercase">Sale Products</h2>
            </div>
          </div>

          <div class="col-md-12 content">
            <div class="row">
              <?php
              $paginationCtrls = '';
              $count_query = $mysqli->query( "SELECT COUNT(ID) FROM products WHERE status='sale' AND stocks > 0" );
              $count_row = $count_query->fetch_row();
              $count_rows = $count_row[0];
              $per_page = 12;
              $last = ceil( $count_rows/$per_page );
              if( $last < 1 ){
                $last = 1;
              }
              $pagenum = 1;
              if( isset( $_GET['page'] ) ){
                $pagenum = (int) $_GET['page'];
              }
              if ( $pagenum < 1 ) { 
                $pagenum = 1; 
              } elseif ( $pagenum > $last) { 
                $pagenum = $last; 
              }
              $limit = 'LIMIT ' .( $pagenum - 1 ) * $per_page .',' .$per_page;
              $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE status='sale' AND stocks > 0 ORDER BY name ASC $limit" );
              $get_products_stmt->execute();
              $get_products_result = $get_products_stmt->get_result();
              if( $get_products_result->num_rows > 0 ) :
                if($last != 1){
                  $paginationCtrls .= '<nav class="pagination-controls" aria-label="..."><ul class="pagination">';
                  if ($pagenum > 1) {
                    $previous = $pagenum - 1;
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'. add_query_arg( 'page', $previous, get_currenturl() ) .'">Previous</a></li>';
                                // Render clickable number links that should appear on the left of the target page number
                    for($i = $pagenum-4; $i < $pagenum; $i++){
                      if($i > 0){
                        $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $i, get_currenturl() ).'">'.$i.'</a></li>';
                      }
                    }
                  }
                            // Render the target page number, but without it being a link
                  $paginationCtrls .= '<li class="page-item active"><span class="page-link">'.$pagenum.'</span></li>';
                            // Render clickable number links that should appear on the right of the target page number
                  for($i = $pagenum+1; $i <= $last; $i++){
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $i, get_currenturl() ).'">'.$i.'</a></li>';
                    if($i >= $pagenum+4){
                      break;
                    }
                  }
                                // This does the same as above, only checking if we are on the last page, and then generating the "Next"
                  if ($pagenum != $last) {
                    $next = $pagenum + 1;
                    $paginationCtrls .= '<li class="page-item"><a class="page-link" href="'.add_query_arg( 'page', $next, get_currenturl() ).'">Next</a></li>';
                  }
                  $paginationCtrls .= '</ul></nav>';
                }
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
          <?php echo $paginationCtrls; ?>
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