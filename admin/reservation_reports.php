<?php
/* 
 * Report Template
 * 
 * @package SJM
 * @author
 */

// include the admin functions
require_once 'functions.php';

/** Block unauthorized users */
if( currentuser_is_customer() ) {
    die( 'You are unauthorized to access this part of our website!' );
}

$site_title = 'Reports';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Online Reservation Reports - Claimed</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active title" id="today-tab" data-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">Today &mdash; <?php echo date( 'M j, Y' ); ?></a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link title" id="yesterday-tab" data-toggle="tab" href="#yesterday" role="tab" aria-controls="yesterday" aria-selected="false">Yesterday</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link title" id="week-tab" data-toggle="tab" href="#week" role="tab" aria-controls="week" aria-selected="false">Week</a>
            </li>
            <li class="nav-item">
                <a class="nav-link title" id="month-tab" data-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="false">Month</a>
            </li>
        </ul>
        <div class="tab-content pl-3 p-1" id="myTabContent">
            <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
                <?php
                $today_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '4' ) {
                    $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND DATE(date_added) = CURDATE() ORDER BY date_added DESC" );
                }else if( $role == '3' ){
                    $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND DATE(date_added) = CURDATE() ORDER BY date_added DESC" );
                }                
                $today_earnings_stmt->execute();
                $today_earnings_result = $today_earnings_stmt->get_result();
                $today_count = $today_earnings_result->num_rows;
                $todays = [];
                if( $today_count > 0 ) {
                    while( $row = $today_earnings_result->fetch_assoc() ) {
                        $today_earning += $row['amount'];
                        $todays[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">Today's Sale &mdash; &#8369; <?php echo number_format( $today_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $today_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=today' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $today_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="white-space: nowrap;">Order #</th>
                                    <th>Items</th>
                                    <th style="white-space: nowrap;">Managed by</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $todays as $today ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $today['ID'] ); ?>" class="title"><?php echo $today['ID']; ?></a></td>                                        
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $today['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php echo $today['managed_by']; ?></td>
                                        <?php
                                            if($today['sub_amount'] === $today['amount']){
                                        ?>
                                        <td>&#8369;<?php echo number_format($today['amount'], 2); ?></td>
                                        <?php 
                                            }else{
                                        ?>
                                        <td><span style="color: #1ABC9C;">25% disc.</span> &#8369;<?php echo number_format($today['amount'], 2); ?></td>
                                        <?php } ?>
                                        <td><?php echo $today['date_added']; ?></td>
                                        <td><a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=solo&id=' . $today['ID'] ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i></a></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $today_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="yesterday" role="tabpanel" aria-labelledby="yesterday-tab">
                <?php
                $yesterday_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '4' ) {
                    $yesterday_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND DATE(date_added) = CURDATE() - INTERVAL 1 DAY ORDER BY date_added DESC" );
                }else if( $role == '3' ){
                    $yesterday_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND DATE(date_added) = CURDATE() - INTERVAL 1 DAY ORDER BY date_added DESC" );
                }
                $yesterday_earnings_stmt->execute();
                $yesterday_earnings_result = $yesterday_earnings_stmt->get_result();
                $yesterday_count = $yesterday_earnings_result->num_rows;
                $yesterdays = [];
                if( $yesterday_count > 0 ) {
                    while( $row = $yesterday_earnings_result->fetch_assoc() ) {
                        $yesterday_earning += $row['amount'];
                        $yesterdays[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">Yesterday's Sale &mdash; &#8369; <?php echo number_format( $yesterday_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $yesterday_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=yesterday' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $yesterday_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="white-space: nowrap;">Order #</th>
                                    <th>Items</th>
                                    <th style="white-space: nowrap;">Managed by</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $yesterdays as $yesterday ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $yesterday['ID'] ); ?>" class="title"><?php echo $yesterday['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $yesterday['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php echo $yesterday['managed_by']; ?></td>
                                        <td>&#8369;<?php echo number_format($yesterday['amount'], 2); ?></td>
                                        <td><?php echo $yesterday['date_added']; ?></td>
                                        <td><a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=solo&id=' . $yesterday['ID'] ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i></a></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $yesterday_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="week" role="tabpanel" aria-labelledby="week-tab">
                <?php
                $week_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '4' ) {
                    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND YEARWEEK(date_added) = YEARWEEK(CURDATE()) ORDER BY date_added DESC" );
                }else if( $role == '3' ){
                    $week_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND YEARWEEK(date_added) = YEARWEEK(CURDATE()) ORDER BY date_added DESC" );
                }
                $week_earnings_stmt->execute();
                $week_earnings_result = $week_earnings_stmt->get_result();
                $week_count = $week_earnings_result->num_rows;
                $weeks = [];
                if( $week_count > 0 ) {
                    while( $row = $week_earnings_result->fetch_assoc() ) {
                        $week_earning += $row['amount'];
                        $weeks[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">This week's Sale &mdash; &#8369; <?php echo number_format( $week_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $week_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=week' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $week_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="white-space: nowrap;">Order #</th>
                                    <th>Items</th>
                                    <th style="white-space: nowrap;">Managed by</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $weeks as $week ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $week['ID'] ); ?>" class="title"><?php echo $week['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $week['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                                } ?>
                                            </ul>
                                        </td>
                                        <td><?php echo $week['managed_by']; ?></td>
                                        <?php
                                            if($week['sub_amount'] === $week['amount']){
                                        ?>
                                        <td>&#8369;<?php echo number_format($week['amount'], 2); ?></td>
                                        <?php 
                                            }else{
                                        ?>
                                        <td><span style="color: #1ABC9C;">25% disc.</span> &#8369;<?php echo number_format($week['amount'], 2); ?></td>
                                        <?php } ?>                                        
                                        <td><?php echo $week['date_added']; ?></td>
                                        <td><a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=solo&id=' . $week['ID'] ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i></a></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $week_earnings_stmt->close(); ?>
            </div>
            <div class="tab-pane fade" id="month" role="tabpanel" aria-labelledby="month-tab">
                <?php
                $month_earning = 0;
                $managed_by = get_currentuser( 'username' );
                $role = get_currentuser( 'role' );
                if( $role == '4' ) {
                    $month_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND MONTH(date_added) = MONTH(CURDATE()) ORDER BY date_added DESC" );
                }else if( $role == '3' ){
                    $month_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE type = 'Reservation' AND status != 'pending' AND MONTH(date_added) = MONTH(CURDATE()) ORDER BY date_added DESC" );
                }
                $month_earnings_stmt->execute();
                $month_earnings_result = $month_earnings_stmt->get_result();
                $month_count = $month_earnings_result->num_rows;
                $months = [];
                if( $month_count > 0 ) {
                    while( $row = $month_earnings_result->fetch_assoc() ) {
                        $month_earning += $row['amount'];
                        $months[] = $row;
                    }
                } ?>
                <div class="m-t-25">
                    <div class="table-data__tool">
                        <div class="table-data__tool-left">
                            <h4 class="table-date-h4">This month's Sale &mdash; &#8369; <?php echo number_format( $month_earning, 2 ); ?></h4>
                            <p>Number of Orders &mdash; <?php echo $month_count; ?></p>
                        </div>
                        <div class="table-data__tool-right">
                            <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=month' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <?php if( $month_count > 0 ) : ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <table class="table table-data2">
                            <thead class="thead-light">
                                <tr>
                                    <th style="white-space: nowrap;">Order #</th>
                                    <th>Items</th>
                                    <th style="white-space: nowrap;">Managed by</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $months as $month ) : ?>
                                    <tr class="tr-shadow">
                                        <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $month['ID'] ); ?>" class="title"><?php echo $month['ID']; ?></a></td>
                                        <td>
                                            <ul>
                                                <?php foreach( unserialize( $month['products'] ) as $id => $qty ) {
                                                    $product = get_product( $id );
                                                    $price = $product['price'];
                                                    $slug = $product['slug'];
                                                    $name = $product['name'];
                                                    $total = $price * $qty;
                                                    echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                                } ?>
                                            </ul>
                                        </td>                                        
                                        <td><?php echo $month['managed_by']; ?></td>
                                        <?php
                                            if($month['sub_amount'] === $month['amount']){
                                        ?>
                                        <td>&#8369;<?php echo number_format($month['amount'], 2); ?></td>
                                        <?php 
                                            }else{
                                        ?>
                                        <td><span style="color: #1ABC9C;">25% disc.</span> &#8369;<?php echo number_format($month['amount'], 2); ?></td>
                                        <?php } ?>                                        
                                        <td><?php echo $month['date_added']; ?></td>
                                        <td><a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=solo&id=' . $month['ID'] ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                <i class="zmdi zmdi-download"></i></a></td>
                                    </tr>
                                    <tr class="spacer"></tr>
                                <?php 
                                endforeach;
                                echo '<tr class="spacer"></tr>'; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                <?php $month_earnings_stmt->close(); ?>
            </div>
        </div>

    </div>
</div>

<?php include_once 'footer.php';