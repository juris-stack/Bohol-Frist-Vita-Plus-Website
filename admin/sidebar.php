<?php
/** 
 * Admin sidebar template
 * 
 * @package SJM
 * @author 
 */
?>
<!-- MENU SIDEBAR-->
<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
        <a href="<?php echo site_url(); ?>">
            <h3 class="logo-title">FIRST VITA PLUS</h3>
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                <?php include 'navigation.php'; ?>
            </ul>
        </nav>
    </div>
</aside>
<!-- END MENU SIDEBAR-->

<!-- PAGE CONTAINER-->
<div class="page-container">
    <!-- HEADER DESKTOP-->
    <header class="header-desktop">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <div class="header-wrap">
                    <div class="header-button">
                        <div class="noti-wrap">
                            <?php if( currentuser_is_admin() ) : ?>
                            <div id="notif" class="noti__item js-item-menu">
                                <?php
                                $notif_stmt = $mysqli->prepare( "SELECT * FROM notifications WHERE status = 'unread' ORDER BY date_added DESC" );
                                $notif_stmt->execute();
                                $notif_result = $notif_stmt->get_result();
                                $notif_count = $notif_result->num_rows;
                                $j = 0; ?>
                                <i class="zmdi zmdi-notifications"></i>
                                <span id="notif-count" class="quantity" <?php
                                        if( $notif_count > 0 ) {
                                            echo ' style="display: inline-block"';
                                        } ?>><?php echo $notif_count; ?></span>
                                <div class="notifi-dropdown js-dropdown">
                                    <div class="notifi__title">
                                        <p id="notif-title">You have <span><?php echo $notif_count; ?></span> Notifications</p>
                                    </div>
                                    <div id="notif-content">
                                    <?php
                                    if( $notif_count > 0 ) :
                                     while( $row = $notif_result->fetch_assoc() ) :
                                        if( $j >= 5 ) {
                                            break;
                                        } ?>
                                        
                                            <?php
                                            $notif_message = '';
                                            $notif_date_pre = '';
                                             switch ( $row['type'] ) :
                                                 case 'order' : 
                                                     $notif_message = $row['message']; 
                                                     $notif_date_pre = 'Ref # ' . $row['rel_ID'] . ', '; ?>
                                                    <a href="order.php?action=edit&id=<?php echo $row['rel_ID']; ?>" class="notifi__item">
                                                        <div class="bg-c1 img-cir img-40">
                                                            <i class="zmdi zmdi-shopping-cart-add"></i>
                                                    <?php
                                                     break;
                                             endswitch; ?>
                                            </div>
                                            <div class="content">
                                                <p><?php echo $notif_message; ?></p>
                                                <span class="date"><?php echo $notif_date_pre . time_ago( $row['date_added'] ); ?></span>
                                            </div>
                                        </a>
                                    <?php $j++; endwhile; ?>
                                    <?php endif; ?>
                                    </div>
                                    <!--
                                    <div class="notifi__footer">
                                        <a href="#">All notifications</a>
                                    </div>-->
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if( currentuser_is_staff() ) : ?>
                            <div id="notif" class="noti__item js-item-menu">
                                <?php
                                $notif_stmt = $mysqli->prepare( "SELECT * FROM notifications WHERE status = 'unread' ORDER BY date_added DESC" );
                                $notif_stmt->execute();
                                $notif_result = $notif_stmt->get_result();
                                $notif_count = $notif_result->num_rows;
                                $j = 0; ?>
                                <i class="zmdi zmdi-notifications"></i>
                                <span id="notif-count" class="quantity" <?php
                                        if( $notif_count > 0 ) {
                                            echo ' style="display: inline-block"';
                                        } ?>><?php echo $notif_count; ?></span>
                                <div class="notifi-dropdown js-dropdown">
                                    <div class="notifi__title">
                                        <p id="notif-title">You have <span><?php echo $notif_count; ?></span> Notifications</p>
                                    </div>
                                    <div id="notif-content">
                                    <?php
                                    if( $notif_count > 0 ) :
                                     while( $row = $notif_result->fetch_assoc() ) :
                                        if( $j >= 5 ) {
                                            break;
                                        } ?>
                                        
                                            <?php
                                            $notif_message = '';
                                            $notif_date_pre = '';
                                             switch ( $row['type'] ) :
                                                 case 'order' : 
                                                     $notif_message = $row['message']; 
                                                     $notif_date_pre = 'Ref # ' . $row['rel_ID'] . ', '; ?>
                                                    <a href="order.php?action=edit&id=<?php echo $row['rel_ID']; ?>" class="notifi__item">
                                                        <div class="bg-c1 img-cir img-40">
                                                            <i class="zmdi zmdi-shopping-cart-add"></i>
                                                    <?php
                                                     break;
                                             endswitch; ?>
                                            </div>
                                            <div class="content">
                                                <p><?php echo $notif_message; ?></p>
                                                <span class="date"><?php echo $notif_date_pre . time_ago( $row['date_added'] ); ?></span>
                                            </div>
                                        </a>
                                    <?php $j++; endwhile; ?>
                                    <?php endif; ?>
                                    </div>
                                    <!--
                                    <div class="notifi__footer">
                                        <a href="#">All notifications</a>
                                    </div>-->
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="account-wrap">
                            <div class="account-item clearfix js-item-menu">
                                <div class="image">
                                    <img src="<?php echo get_userimage( $user_id, 'small' ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>" />
                                </div>
                                <div class="content">
                                    <a class="js-acc-btn" href="#"><?php echo get_currentuser( 'username' ); ?></a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="profile.php">
                                                <img src="<?php echo get_userimage( $user_id, 'small' ); ?>" alt="<?php echo get_currentuser( 'username' ); ?>" />
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="profile.php"><?php echo get_currentuser( 'username' ); ?></a>
                                            </h5>
                                            <span class="email"><?php echo get_currentuser( 'email' ); ?></span>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="user.php">
                                                <i class="zmdi zmdi-account"></i>Account</a>
                                        </div>
                                        <?php if( currentuser_is_admin() ) : ?>
                                        <div class="account-dropdown__item">
                                            <a href="settings.php">
                                                <i class="zmdi zmdi-settings"></i>Setting</a>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="account-dropdown__footer">
                                        <a href="../login.php?action=logout&redirect=login.php">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- HEADER DESKTOP-->
    
    <!-- MAIN CONTENT-->
    <div class="main-content">
        <div class="section__content section__content--p30">
            <div class="container-fluid">
                <?php show_site_notice(); ?>