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