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

$dateFrom = '';
$datetO = '';

if( isset( $_POST['search-sales'] ) ) {  
  $from = $_POST['date_from'];
  $to = $_POST['date_to'];    

  $dateFrom = date("Y-m-d", strtotime($from));
  $datetO = date("Y-m-d", strtotime($to));  


  $dateFromTransform = date("M d, Y", strtotime($dateFrom));
  $datetOTransform = date("M d, Y", strtotime($datetO));



}

$site_title = 'Reports';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Search Sales</h2>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="form-group">
            <form method="POST" action="search_sales.php" class="row">
                <div class="col-4">
                    <label>Date from:</label>
                    <input type="date" id="date_from" value="<?php echo $dateFrom?>" name="date_from" class="form-control checkin_date" required>
                </div>
                <div class="col-4">
                    <label>To:</label>
                    <input type="date" id="date_to" value="<?php echo $datetO?>" name="date_to" class="form-control checkout_date" required>
                </div>
                <div class="col-4">
                    <label class="invisible">white line</label>
                    <input type="submit" name="search" value="Search" class="form-control au-btn au-btn-icon au-btn--blue au-btn--small">
                    <input type="hidden" name="search-sales" value="1">
                </div>
            </form>
        </div>
        

        <?php if( isset( $_POST['search-sales'] ) == true ) : ?>
            <?php                
            $today_earning = 0;
            $managed_by = get_currentuser( 'username' );
            $role = get_currentuser( 'role' );
            if( $role == '4' ) {
                $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE date_added BETWEEN '$dateFrom' and '$datetO' AND status != 'pending' ORDER BY date_added DESC" );
            }else if( $role == '3' ){
                $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE date_added BETWEEN '$dateFrom' and '$datetO' AND status != 'pending' ORDER BY date_added DESC" );
            }                
            $today_earnings_stmt->execute();
            $today_earnings_result = $today_earnings_stmt->get_result();
            $today_count = $today_earnings_result->num_rows;
            $searches = [];
            if( $today_count > 0 ) {
                while( $row = $today_earnings_result->fetch_assoc() ) {
                    $today_earning += $row['amount'];
                    $searches[] = $row;
                }
            } ?>
            <div class="table-responsive table-responsive-data2 m-t-25">
                <div class="table-data__tool">
                    <div class="table-data__tool-left">
                        <h4 class="table-date-h4"><?php echo $dateFromTransform . ' to ' . $datetOTransform; ?> &mdash; &#8369; <?php echo number_format( $today_earning, 2 ); ?></h4>
                        <p>Number of Orders &mdash; <?php echo $today_count; ?></p>
                    </div>
                    <div class="table-data__tool-right">
                        <a target="_blank" href="<?php echo site_url( '/admin/generate_sales_search_results.php?type=searchSales&from=' .$dateFrom. '&to=' .$datetO. '' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                            <i class="zmdi zmdi-download"></i> Generate Report</a>
                        </div>
                    </div>
                    <table class="table table-data2">
                        <thead class="thead-light">
                            <tr>
                                <th style="white-space: nowrap;">Order #</th>
                                <th>Items</th>                                    
                                <th>Amount</th>
                                <th>Date</th>
                                <th>type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach( $searches as $search ) : ?>
                                <tr class="tr-shadow">
                                    <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $search['ID'] ); ?>" class="title"><?php echo $search['ID']; ?></a></td>                                        
                                    <td>
                                        <ul>
                                            <?php foreach( unserialize( $search['products'] ) as $id => $qty ) {
                                                $product = get_product( $id );
                                                $price = $product['price'];
                                                $slug = $product['slug'];
                                                $name = $product['name'];
                                                $total = $price * $qty;
                                                echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                            } ?>
                                        </ul>
                                    </td>
                                    <!-- <td><?php echo $search['managed_by']; ?></td> -->
                                    <?php
                                    if($search['sub_amount'] === $search['amount']){
                                        ?>
                                        <td>&#8369;<?php echo number_format($search['amount'], 2); ?></td>
                                        <?php 
                                    }else{
                                        ?>
                                        <td><span style="color: #1ABC9C;">25% disc.</span> &#8369;<?php echo number_format($search['amount'], 2); ?></td>
                                    <?php } ?>
                                    <td><?php echo date($search['date_added']); ?></td>
                                    <td><?php echo $search['type']; ?></td>
                                </tr>
                                <tr class="spacer"></tr>
                                <?php 
                            endforeach;
                            echo '<tr class="spacer"></tr>'; ?>
                        </tbody>
                    </table>
                </div>
                <?php elseif( isset( $_POST['search-sales'] ) == false ) :  ?>
                    <?php
                    $today_earning = 0;
                    $managed_by = get_currentuser( 'username' );
                    $role = get_currentuser( 'role' );
                    if( $role == '4' ) {
                        $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status != 'pending' ORDER BY date_added DESC" );
                    }else if( $role == '3' ){
                        $today_earnings_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE status != 'pending' ORDER BY date_added DESC" );
                    }                
                    $today_earnings_stmt->execute();
                    $today_earnings_result = $today_earnings_stmt->get_result();
                    $today_count = $today_earnings_result->num_rows;
                    $searches = [];
                    if( $today_count > 0 ) {
                        while( $row = $today_earnings_result->fetch_assoc() ) {
                            $today_earning += $row['amount'];
                            $searches[] = $row;
                        }
                    } ?>
                    <div class="table-responsive table-responsive-data2 m-t-25">
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <h4 class="table-date-h4">Total Sales &mdash; &#8369; <?php echo number_format( $today_earning, 2 ); ?></h4>
                                <p>Number of Orders &mdash; <?php echo $today_count; ?></p>
                            </div>
                            <!-- <div class="table-data__tool-right">
                                <a target="_blank" href="<?php echo site_url( '/admin/generate_online_report.php?type=today' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                                    <i class="zmdi zmdi-download"></i> Generate Report</a>
                                </div> -->
                            </div>
                            <table class="table table-data2">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="white-space: nowrap;">Order #</th>
                                        <th>Items</th>                                    
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach( $searches as $search ) : ?>
                                        <tr class="tr-shadow">
                                            <td><a href="<?php echo site_url( '/admin/order.php?action=edit&id=' . $search['ID'] ); ?>" class="title"><?php echo $search['ID']; ?></a></td>                                        
                                            <td>
                                                <ul>
                                                    <?php foreach( unserialize( $search['products'] ) as $id => $qty ) {
                                                        $product = get_product( $id );
                                                        $price = $product['price'];
                                                        $slug = $product['slug'];
                                                        $name = $product['name'];
                                                        $total = $price * $qty;
                                                        echo '<li><a href="' . site_url( '/product.php?p=' . $slug ) . '" class="title">' . $name . '</a> X ' . $qty . ' @ &#8369;' . number_format($price,2) . ' = &#8369;' . number_format($total,2) . '</li>';
                                                    } ?>
                                                </ul>
                                            </td>
                                            <!-- <td><?php echo $search['managed_by']; ?></td> -->
                                            <?php
                                            if($search['sub_amount'] === $search['amount']){
                                                ?>
                                                <td>&#8369;<?php echo number_format($search['amount'], 2); ?></td>
                                                <?php 
                                            }else{
                                                ?>
                                                <td><span style="color: #1ABC9C;">25% disc.</span> &#8369;<?php echo number_format($search['amount'], 2); ?></td>
                                            <?php } ?>
                                            <td><?php echo $search['date_added']; ?></td>
                                            <td><?php echo $search['type']; ?></td>
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
            </div> 


            <?php include_once 'footer.php';