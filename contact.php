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
          <div class="col-md-12 mb-0"><a href="index.php" class="text-lighted">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Contact</strong></div>
        </div>
      </div>
    </div>    


    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h2 class="h3 mb-3 text-black">Get In Touch</h2>
          </div>

          <div class="col-md-6 ml-auto">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3933.3910730033945!2d123.84906061430004!3d9.647586481651015!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aa4db210b53b99%3A0xa1abb25cf287d172!2sFirst%20Vita%20Plus%20-%20SC%20Bohol!5e0!3m2!1sen!2sph!4v1569316537398!5m2!1sen!2sph" width="100%" height="515" frameborder="0" style="border:0;" allowfullscreen=""></iframe>

          </div>

          <div class="col-md-6">
            
              
              <div class="p-3 p-lg-5 border">
                <p class="text-lighted-p">Address</p>
                <p><span class="icon-location-arrow lighted-big"></span> <?php echo get_siteinfo( 'company-address' ); ?></p>
                <p class="text-lighted-p">Contact</p>
                <p><span class="icon-phone lighted-big"></span> <?php echo get_siteinfo( 'company-phone' ); ?></p>
                <p class="text-lighted-p">Email</p>
                <p><span class="icon-mail_outline lighted-big"></span> <a href="#"><?php echo get_siteinfo( 'company-email' ); ?></a></p>
                <p class="text-lighted-p">Facebook</p>
                <p><span class="icon-facebook-square lighted-big"></span> <a target="_blank" href="https://www.facebook.com/firstvitaplusbohol/">https://www.facebook.com/firstvitaplusbohol/</a></p>
              </div>
            
          </div>
          
        </div>
      </div>
    </div>


    <?php include 'footer.php';?>   
    </div>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
    
  </body>
</html>