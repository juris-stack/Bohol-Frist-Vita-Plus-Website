<?php
/**
 * Order admin template
 * 
 * @package SJM
 * @author 
 */

// include the admin functions
require_once 'functions.php';

$order_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';
$action = !empty( $_GET['action'] ) ? $_GET['action'] : '';

if( empty( $order_id ) ) {
    redirect( 'orders.php' );
}

if( $action === 'delete' && currentuser_is_customer() ) {
    die( 'You are unauthorized to access this part of our website!' );
}elseif( $action === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM orders WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $order_id );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'orders.php' );
}

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

if( isset( $_POST['update-order'] ) ) {
    $status = esc_str( $_POST['status'] );
    $managed_by = get_currentuser( 'username' );
    $date = date('y-m-d h:i:s');
    $stmt_update = $mysqli->prepare( "UPDATE orders SET status = ?, managed_by = ?, date_added = ? WHERE  ID = ?" );
    $stmt_update->bind_param( 'sssi', $status, $managed_by, $date, $order_id );
    $stmt_update->execute();
    $stmt_update->close();
    if( $status === 'cancelled' ) {
        foreach( $products as $pid => $qty ) {
            $stocks = get_productby( 'stocks', $pid );
            $stocks += $qty;
            update_product( 'stocks', $stocks, $pid );
        }
    }
    set_site_notice( 'Order updated successfully.', 'success' );
}

if( $action === 'cancel' ) {
    $status = 'cancelled';
    $stmt_update = $mysqli->prepare( "UPDATE orders SET status = ? WHERE  ID = ?" );
    $stmt_update->bind_param( 'si', $status, $order_id );
    $stmt_update->execute();
    $stmt_update->close();
    if( $status === 'cancelled' ) {
        foreach( $products as $pid => $qty ) {
            $stocks = get_productby( 'stocks', $pid );
            $stocks += $qty;
            update_product( 'stocks', $stocks, $pid );
        }
    }
    push_notification( $order_id, 'order', 'Cancelled order' );
    set_site_notice( 'Your order has been cancelled.', 'error' );
}

$site_title = 'Reservation #' . $order_id . ' Details';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1"><?php echo $site_title . ' &mdash; ' . ucfirst( $status ); ?></h2>            
        </div>
        <div class="row">
            <?php if( currentuser_is_customer() ) : ?>
            <div class="col-sm-12">
            <?php elseif( currentuser_is_member() ) : ?>
            <div class="col-sm-12">
            <?php else : ?>
            <div class="col-sm-8">
            <?php endif; ?>
                <div class="card">
                    <?php if( currentuser_is_admin() ) : ?> 
                    <div class="card-body card-block">
                        <div class="row">                          
                            <div class="col-md-12">
                                <h4>Customers Information</h4>
                                <hr>
                                <p>Name: <?php echo $billing['firstname'] . ' ' . $billing['lastname']; ?></p>
                                <p>Address: <?php echo $billing['address']; ?></p>                                
                                <p>Phone: <?php echo $billing['telephone']; ?></p>
                                <p>Date of Reservation: <?php echo $date; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <?php if( currentuser_is_staff() ) : ?> 
                    <div class="card-body card-block">
                        <div class="row">                          
                            <div class="col-md-12">
                                <h4>Customers Information</h4>
                                <hr>
                                <p>Name: <?php echo $billing['firstname'] . ' ' . $billing['lastname']; ?></p>
                                <p>Address: <?php echo $billing['address']; ?></p>                                
                                <p>Phone: <?php echo $billing['telephone']; ?></p>
                                <p>Date of Transaction: <?php echo $date; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="card-header">
                        <strong>Items</strong>
                    </div>
                    <div class="card-body card-block">
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr class="header-orders">
                                        <th></th>
                                        <th style="color: white">Product</th>
                                        <th style="color: white">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach( $products as $pid => $qty ) :
                                        echo '<tr class="tr-shadow">';
                                        $product_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
                                        $product_stmt->bind_param( 'i', $pid );
                                        $product_stmt->execute();
                                        $product_result = $product_stmt->get_result();
                                        if( $product_result->num_rows > 0 ) :
                                            while( $row = $product_result->fetch_assoc() ) : ?>
                                                <td>
                                                    <a href="<?php echo  site_url( '/product.php?p=' . $row['slug'] ); ?>">
                                                        <img class="image-small" src="<?php echo get_productimage( $pid, 'small' ); ?>" alt="<?php echo $row['name']; ?>">
                                                    </a>
                                                </td>
                                                <td><a href="<?php echo  site_url( '/product.php?p=' . $row['slug'] ); ?>"><?php echo $row['name']; ?></a> X <?php echo $qty; ?></td>
                                                <td>&#8369; <?php
                                                if( (int) $row['sale_price'] > 0 ) {
                                                    echo '<span class="not">' . $row['price'] . '</span>';
                                                }else{
                                                    echo number_format( $row['price'], 2);
                                                }
                                                if( (int) $row['sale_price'] > 0 ) {
                                                    echo ' ' . number_format( $row['sale_price'], 2 );
                                                } ?></td>
                                            <?php
                                            endwhile;
                                        endif;
                                        $product_stmt->close(); ?>
                                    <?php
                                    echo '</tr>';
                                    endforeach; ?>
                                </tbody>
                                <!-- <tbody>
                                    <tr>
                                        <th></th>
                                        <th class="text-right"><span style="font-size: 16px;">Reservation</span></th>
                                        <th class="text-left"><span style="font-size: 16px;">Summary</span></th>
                                    </tr>
                                </tbody> -->
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td class="text-right"><Strong style="font-size: 16px;">Transaction</strong></td>
                                        <td class="text-left"><strong style="font-size: 16px;">Summary</strong></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-right">Sub Total</td>
                                        <td>&#8369; <?php echo number_format( $sub_amount, 2); ?></td>
                                    </tr>
                                    <?php if ( currentuser_is_member()) : ?>
                                    <tr>                                        
                                        <td></td>
                                        <td class="text-right">Member</td>
                                        <td><span>25% Discount</span></td>
                                    </tr>
                                    <?php else : ?>
                                    <tr>
                                        <td></td>
                                        <td class="text-right">Member</td>
                                        <td><span class="not">25% Discount</span></td>
                                    </tr>
                                    <?php endif ; ?>   
                                    <tr>
                                        <td></td>
                                        <td class="text-right">Total</td>
                                        <td>&#8369; <?php echo number_format( $amount, 2); ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php if( currentuser_is_admin() ) : ?>
            <div class="col-sm-4">
                <div class="card">
                    <form method="POST" action="">
                        <div class="card-header">
                            <strong>Action</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status" <?php
                                if( $status === 'cancelled' || $status === 'claimed' ) {
                                    echo 'disabled';
                                } ?>>
                                    <option value="pending" <?php selected( 'pending', $status ); ?>>Pending</option>
                                    <option value="claimed" <?php selected( 'claimed', $status ); ?>>Claimed</option>
                                    <option value="cancelled" <?php selected( 'cancelled', $status ); ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-md" <?php
                                if( $status === 'cancelled' || $status === 'claimed' ) {
                                    echo 'disabled';
                                } ?>>Update</button>
                            <input type="hidden" name="update-order" value="1">
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
            <?php if( currentuser_is_staff() ) : ?>
            <div class="col-sm-4">
                <div class="card">
                    <form method="POST" action="">
                        <div class="card-header">
                            <strong>Action</strong>
                        </div>
                        <div class="card-body card-block">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select id="status" class="form-control" name="status" <?php
                                if( $status === 'cancelled' || $status === 'claimed' ) {
                                    echo 'disabled';
                                } ?>>
                                    <option value="pending" <?php selected( 'pending', $status ); ?>>Pending</option>
                                    <option value="claimed" <?php selected( 'claimed', $status ); ?>>Claimed</option>
                                    <option value="cancelled" <?php selected( 'cancelled', $status ); ?>>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary btn-md" <?php
                                if( $status === 'cancelled' || $status === 'claimed' ) {
                                    echo 'disabled';
                                } ?>>Update</button>
                            <input type="hidden" name="update-order" value="1">
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'footer.php';