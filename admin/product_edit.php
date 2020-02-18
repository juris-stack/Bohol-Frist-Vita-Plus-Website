<?php
/* 
 * product add and edit template
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

$action = !empty( $_GET['action'] ) ? $_GET['action'] : 'add';
$product_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';

if( ( $action === 'edit' || $action === 'delete' ) && empty( $product_id ) ) {
    redirect( 'products.php' );
}

if( $action === 'delete' ) {
    $stmt_delete = $mysqli->prepare( "DELETE FROM products WHERE ID = ?" );
    $stmt_delete->bind_param( 'i', $product_id );
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect( 'products.php' );
}

$item_code = '';
$title = '';
$description = '';
$price = '';
$sale_price = '';
// $sku = '';
$stocks = '';
// $brand = '';
$category = '';
$status = '';
$date_added = '';
$get_number='';

if( isset( $_POST['update'] ) ) {
    $item_code = esc_str( $_POST['item-code'] );
    $title = esc_str( $_POST['title'] );
    $description = esc_textarea( $_POST['description'] );
    $price = esc_str( $_POST['price'] );
    $sale_price = esc_str( $_POST['sale-price'] );
    // $sku = esc_str( $_POST['sku'] );
    $stocks = empty( $_POST['stocks'] ) ? 0 : esc_str( $_POST['stocks'] );
    // $brand = isset( $_POST['brand'] ) ? esc_str( $_POST['brand'] ) : '';
    $category = isset( $_POST['category'] ) ? esc_str( $_POST['category'] ) : '';
    $status = esc_str( $_POST['status'] );
    $status2 = 'sale';
    $expdate = $_POST['exp-date'];
    $slug = esc_slug( $title );
    
    if( $_POST['update'] === 'add' ) {
        $stmt_insert = $mysqli->prepare( "INSERT INTO products (slug, item_code, name, description, category, stocks, price, sale_price, status, expiration_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)" );
        $stmt_insert->bind_param( 'ssssiiissss', $slug, $item_code, $title, $description, $category, $stocks, $price, $sale_price, $status, $expdate );
        $stmt_insert->execute();
        $product_id = $mysqli->insert_id;
        $stmt_insert->close();
    }else if ($_POST['sale-price'] === ''){
        $stmt_update = $mysqli->prepare( "UPDATE products SET slug = ?, item_code = ?, name = ?, description = ?, category = ?, stocks =?, price = ?, sale_price = ?, status = ?, expiration_date = ? WHERE  ID = ?" );
        $stmt_update->bind_param( 'ssssiissssi', $slug, $item_code, $title, $description, $category, $stocks, $price, $sale_price, $status, $expdate, $product_id );
        $stmt_update->execute();
        $stmt_update->close();
        set_site_notice( 'Product updated successfully.', 'success' );
    } else {
        $stmt_update = $mysqli->prepare( "UPDATE products SET slug = ?, item_code = ?, name = ?, description = ?, category = ?, stocks =?, price = ?, sale_price = ?, status = ?, expiration_date = ? WHERE  ID = ?" );
        $stmt_update->bind_param( 'ssssiissssi', $slug, $item_code, $title, $description, $category, $stocks, $price, $sale_price, $status, $expdate, $product_id );
        $stmt_update->execute();
        $stmt_update->close();
        set_site_notice( 'Product updated successfully.', 'success' );
       
    }
    
    // uploading image
    if( isset($_FILES['image']) && $_FILES['image']['size'] > 0 ) {
        upload_images( $_FILES['image']['tmp_name'], 'product', $product_id );
    }
    
    if( $_POST['update'] === 'add' ) {
        // redirect( 'product_edit.php?action=edit&id=' . $product_id );
        redirect( 'products.php' );
    }
     //====================SMS
		if($stocks<=3){
			$get_number=get_siteinfo( 'company-phone' );
				function itexmo($number,$message,$apicode){
      		$ch = curl_init();
      		$itexmo = array('1' => $number, '2' => $message, '3' => $apicode);
      			curl_setopt($ch, CURLOPT_URL,"https://www.itexmo.com/php_api/api.php");
      			curl_setopt($ch, CURLOPT_POST, 1);
      			curl_setopt($ch, CURLOPT_POSTFIELDS, 
                http_build_query($itexmo));
      			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	 return curl_exec ($ch);
      			curl_close ($ch);}
		$result = itexmo("$get_number","Stock for $title is running out! You only have $stocks stock(s) available.\n\n-First Vita Plus","ST-TANNY420621_B7B7J");
				if ($result == ""){
				echo "";	
				}else if ($result == 0){
				echo "Message Sent!";
				}
				else{	
				echo "Error Num ". $result . " was encountered!";
				}
				    }

//====================SMS
}

if( $action === 'edit' ) {
    $get_products_stmt = $mysqli->prepare( "SELECT * FROM products WHERE ID = ? LIMIT 1" );
    $get_products_stmt->bind_param( 'i', $product_id );
    $get_products_stmt->execute();
    $get_products_result = $get_products_stmt->get_result();
    if( $get_products_result->num_rows > 0 ) {
        while( $row = $get_products_result->fetch_assoc() ) {
            $product_id = $row['ID'];
            $item_code = $row['item_code'];
            $title = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
            $sale_price = $row['sale_price'];
            // $sku = $row['sku'];
            $stocks = $row['stocks'];
            $category = $row['category'];
            // $brand = $row['brand'];
            $status = $row['status'];
            $date_added = $row['date_added'];
            $expdate = $row['expiration_date'];
        }
    }



}


$site_title = ucfirst( $action ) . ' Item';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<form method="POST" action="" enctype="multipart/form-data">
<div class="row">
    <div class="col-md-12">
        <div class="page-title">
            <h2 class="title-1"><?php echo $site_title; ?></h2>
        </div>        
    </div>
</div>
    <div class="row">
        <div class="col-sm-8">
            <div class="card">
                <div class="card-header">
                    <strong>Item Details</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="title">Item Code</label>
                        <input type="text" value="<?php echo $item_code; ?>" name="item-code" class="form-control" id="item-code" required>
                    </div>
                    <div class="form-group">
                        <label for="title">Item Name</label>
                        <input type="text" value="<?php echo $title; ?>" name="title" class="form-control" id="title" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="excerpt">Short Description (excerpt)</label>
                        <textarea rows="3" name="excerpt" class="form-control" id="excerpt"><?php echo $excerpt; ?></textarea>
                    </div> -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="price">Price</label>
                            <input type="text" value="<?php echo $price; ?>" name="price" class="form-control" id="price" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sale-price">Sale Price</label>
                            <input type="text" value="<?php echo $sale_price; ?>" name="sale-price" class="form-control" id="sale-price">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stocks">Stocks</label>
                            <input type="text" value="<?php echo $stocks; ?>" name="stocks" class="form-control" id="stocks" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="stocks">Expire Date</label>
                            <input type="date" value="<?php echo $expdate?>" name="exp-date" class="form-control" id="exp-date">
                        </div>
                    </div>
                    <!-- <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="stocks">Stocks</label>
                            <input type="text" value="<?php echo $stocks; ?>" name="stocks" class="form-control" id="stocks" required>
                        </div>
                        <div class="form-group col-md-6">
                            
                        </div>
                    </div> -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea rows="10" name="description" class="form-control" id="description"><?php echo $description; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <strong>Item Image</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="image-upload"><img class="image-preview" <?php
                        if( $action === 'edit' ) {
                            echo 'src="' . get_productimage( $product_id ) . '"';
                            } ?>></label>
                        <input 
                        style="display:block;text-overflow: ellipsis;width: 100%;overflow: hidden; white-space: nowrap;"
                        type="file" name="image" id="image-upload">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <strong><label for="category">Category</label></strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <select id="category" class="form-control" name="category">
                            <?php
                            $get_category_stmt = $mysqli->prepare( "SELECT * FROM category ORDER BY name ASC" );
                            $get_category_stmt->execute();
                            $get_category_result = $get_category_stmt->get_result();
                            if( $get_category_result->num_rows > 0 ) {
                                while( $row = $get_category_result->fetch_assoc() ) {
                                    echo '<option value="' . $row['ID'] . '"';
                                    selected( $row['ID'], $category );
                                    echo '>' . $row['name'] . '</option>';
                                }
                            }else{
                                echo '<option value="">No category</option>';
                            } 
                            $get_category_stmt->close(); ?>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="card">
                <div class="card-header">
                    <strong>Publish</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" class="form-control" name="status">
                            <option value="published" <?php selected( 'published', $status ); ?>>Publish</option>
                            <option value="sale" <?php selected( 'sale', $status ); ?>>Sale</option>
                            <option value="draft" <?php selected( 'draft', $status ); ?>>Draft</option>                            
                        </select>
                    </div>
                </div> 
                <div class="card-footer">
                    <?php
                        $btn_label = '';
                        $submit_type = '';
                            switch( $action ) {
                                case 'edit' :
                                    $btn_label = 'Save Changes';
                                    $submit_type = 'edit';

                                    break;
                                case 'add' :
                                default :
                                    $btn_label = 'Submit';
                                    $submit_type = 'add';
                            } 
                        ?>
                    <button type="submit" class="btn btn-primary btn-md" style="width: 100%"><?php echo $btn_label; ?></button>
                    <input type="hidden" name="update" value="<?php echo $submit_type; ?>">
                </div>               
            </div> 

        </div>
    </div>
</form>

<?php include_once 'footer.php';