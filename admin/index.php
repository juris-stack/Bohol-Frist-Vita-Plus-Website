<?php
/**
 * Admin main index
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php';

if( currentuser_is_customer() ) {
    include_once 'profile.php';
    exit;
}

if( currentuser_is_member() ) {
    include_once 'profile.php';
    exit;
}

$site_title = 'Dashboard';
include_once 'header.php';
include_once 'sidebar.php'; ?>

            
<!-- <div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Overview</h2>
        </div>
    </div>
</div> -->
<div class="table-data__tool">
    <div class="table-data__tool-left">       
        <h2 class="title-1">Overview</h2>
    </div>
    <div class="table-data__tool-right">                
        <a href="../qoutation.php" class="au-btn au-btn-icon au-btn--blue au-btn--small">
            <i class="zmdi zmdi-plus"></i>Walk-in Qoutations
        </a>
    </div>
</div>
<div class="row m-t-25">
    <?php
    $count_user_query = $mysqli->query( "SELECT COUNT(ID) FROM users where role = 2" );
    $count_user_row = $count_user_query->fetch_row();
    $count_users = $count_user_row[0]; ?>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="board">
            <div class="panel panel-primary">
                <div class="number">
                    <h3>
                        <h3><?php echo $count_users; ?></h3>
                        <small>Members</small>
                    </h3> 
                </div>
                <div class="icon">
                    <i class="fa fa-user fa-5x yellow"></i>
                </div>
             
            </div>
        </div>
    </div>
    <?php
    $week_earning = 0;
    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status = 'claimed' AND YEARWEEK(date_added) = YEARWEEK(CURDATE())" );
    $week_earnings_stmt->execute();
    $week_earnings_result = $week_earnings_stmt->get_result();
    if( $week_earnings_result->num_rows > 0 ) {
        while( $row = $week_earnings_result->fetch_assoc() ) {
            $week_earning += $row['amount'];
        }
    }
    $week_earnings_stmt->close(); ?>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="board">
            <div class="panel panel-primary">
                <div class="number">
                    <h3>
                        <h3>&#8369; <?php echo number_format( $week_earning, 2 ); ?></h3>
                        <small>earnings this week</small>
                    </h3> 
                </div>
                <div class="icon">
                    <i class="fa fa-calendar fa-5x green"></i>
                </div>
             
            </div>
        </div>
    </div>    
    <?php
    $total_earning = 0;
    $total_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status = 'claimed' " );
    $total_earnings_stmt->execute();
    $total_earnings_result = $total_earnings_stmt->get_result();
    if( $total_earnings_result->num_rows > 0 ) {
        while( $row = $total_earnings_result->fetch_assoc() ) {
            $total_earning += $row['amount'];
        }
    }
    $total_earnings_stmt->close(); ?>
    <div class="col-md-4 col-sm-12 col-xs-12">
        <div class="board">
            <div class="panel panel-primary">
                <div class="number">
                    <h3>
                        <h3>&#8369; <?php echo number_format( $total_earning, 2 ); ?></h3>
                        <small>total earnings</small>
                    </h3> 
                </div>
                <div class="icon">
                    <i class="fa fa-dollar-sign fa-5x blue"></i>
                </div>
             
            </div>
        </div>
    </div> 
</div>
<div class="row">
    <div class="col-md-12 dashboard-mini-cont">
        <h2 class="title-3 m-b-25">Near Expiration</h2>
        <div class="table-responsive table--no-card m-b-40">
            <table class="table table-borderless table-striped table-earning">
                <thead> 
                    <tr class="header-low">
                        <th>item</th>
                        <th><center>qty</center></th>
                        <th>Date of Expiration</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE MONTH(expiration_date) = MONTH(CURDATE())" );
                    $get_products_stmt->execute();
                    $get_products_result = $get_products_stmt->get_result();
                    if( $get_products_result->num_rows > 0 ) :
                        while( $row = $get_products_result->fetch_assoc() ) : ?>
                            <tr>
                                <td><a href="<?php echo site_url( '/product.php?id=' . $row['ID'] ); ?>" class="title-low"><?php echo $row['name']; ?></a></td>
                                <td><center><?php echo $row['stocks']; ?></center></td>
                            
                                <td><?php echo $row['expiration_date'];?></td>
                             
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/product_edit.php?id=' . $row['ID'] . '&action=edit' ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Manage">
                                            <i class="zmdi zmdi-settings"></i>
                                        </a>                                        
                                    </div>
                                    
                                </td>
                            </tr>
                    <?php 
                        endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 dashboard-mini-cont">
        <h2 class="title-3 m-b-25"><?php echo date( 'M j, Y' ); ?> - Reservations</h2>
        <div class="table-responsive table--no-card m-b-40">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <tr class="header-orders">
                        <th>Customer</th>
                        <th><center>Amount</center></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_orders_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status='pending' AND type = 'Reservation' AND DATE(date_added) = CURDATE() ORDER BY date_added DESC LIMIT 8" );
                    $get_orders_stmt->execute();
                    $get_orders_result = $get_orders_stmt->get_result();
                    if( $get_orders_result->num_rows > 0 ) :
                        while( $row = $get_orders_result->fetch_assoc() ) :
                            $order_id = $row['ID'];
                            $user_details = [];
                            $billing = [];
                            $shipping = [];
                            $status = '';
                            $products = [];
                            $amount = '';
                            $order_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE ID = ? LIMIT 1" );
                            $order_stmt->bind_param( 'i', $order_id );
                            $order_stmt->execute();
                            $order_result = $order_stmt->get_result();
                            if( $order_result->num_rows > 0 ) {
                                while( $row = $order_result->fetch_assoc() ) {
                                    $user_details = unserialize( $row['user_details'] );
                                    $billing = $user_details['billing'];
                                    // $shipping = $user_details['shipping'];
                                    $status = $row['status'];
                                    $products = unserialize( $row['products'] );
                                    $sub_amount = $row['sub_amount'];
                                    $amount = $row['amount'];
                                    $date = $row['date_added'];
                                }
                            }
                            $order_stmt->close();

                         ?>
                            <tr>
                                <td><?php echo $billing['firstname'];?></td>
                                <td>&#8369; <?php echo number_format( $amount, 2); ?></td>
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $order_id ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Manage">
                                            <i class="zmdi zmdi-settings"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php 
                        endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 dashboard-mini-cont">
        <h2 class="title-3 m-b-25">Low In Stock</h2>
        <div class="table-responsive table--no-card m-b-40">
            <table class="table table-borderless table-striped table-earning">
                <thead>
                    <tr class="header-low">
                        <th>item</th>
                        <th><center>qty</center></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE stocks <= 5 ORDER BY name ASC LIMIT 8" );
                    $get_products_stmt->execute();
                    $get_products_result = $get_products_stmt->get_result();
                    if( $get_products_result->num_rows > 0 ) :
                        while( $row = $get_products_result->fetch_assoc() ) : ?>
                            <tr>
                                <td><a href="<?php echo site_url( '/product.php?id=' . $row['ID'] ); ?>" class="title-low"><?php echo $row['name']; ?></a></td>
                                <td><center><?php echo $row['stocks']; ?></center></td>
                            

                             
                                <td>
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/product_edit.php?id=' . $row['ID'] . '&action=edit' ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Manage">
                                            <i class="zmdi zmdi-settings"></i>
                                        </a>                                        
                                    </div>
                                    
                                </td>
                            </tr>
                    <?php 
                        endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

                        
<?php include_once 'footer.php';