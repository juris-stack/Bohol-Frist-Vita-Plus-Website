<?php
/** 
 * Admin products template
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
$posttype = !empty( $_GET['type'] ) ? $_GET['type'] : 'category';

$site_title = 'Category';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Category</h2>
        </div>
        <?php
        $paginationCtrls = '';
        $count_query = $mysqli->query( "SELECT COUNT(ID) FROM category" );
        $count_row = $count_query->fetch_row();
        $count_rows = $count_row[0];
        $per_page = 10;
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
        $get_brand_stmt = $mysqli->prepare( "SELECT * FROM category ORDER BY date_added DESC $limit" );
        $get_brand_stmt->execute();
        $get_brand_result = $get_brand_stmt->get_result();
        if( $get_brand_result->num_rows > 0 ) {

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
        } ?>
        <div class="table-data__tool">                        
            <div class="table-data__tool-left">                
            </div>
            <div class="table-data__tool-right">
                <a href="<?php echo site_url( '/admin/category_edit.php' ); ?>" class="au-btn au-btn-icon au-btn--blue au-btn--small">
                    <i class="zmdi zmdi-plus"></i>Add Category</a>
            </div>
        </div>
        <div class="table-responsive table-responsive-data2">
            <table class="table table-data2">
                <thead class="thead-light">
                    <tr>                        
                        <th>Category Name</th>
                        <th>Description</th>                        
                        <th>Status</th>
                        <?php 
                            if ( currentuser_is_staff() ){
                                        // none
                            } 
                            if ( currentuser_is_admin() ) {
                        ?>
                        <th><center>Action</center></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="products-result">
                    <?php
                    if( $get_brand_result->num_rows > 0 ) :
                        while( $row = $get_brand_result->fetch_assoc() ) :
                            $product_id = $row['ID']; ?>
                            <tr class="tr-shadow">
                                <td>                                    
                                    <a href="<?php echo site_url( '/product.php?p=' . $row['slug'] ); ?>" class="title"><?php echo $row['name']; ?></a>
                                </td>
                                <td><a href="<?php echo site_url( '/product.php?p=' . $row['slug'] ); ?>" class="title"><?php echo $row['description']; ?></a></td>                                                                
                                <td><?php echo $row['status'];?></td>
                                <?php 
                                    if ( currentuser_is_staff() ){
                                        // none
                                    } 
                                    if ( currentuser_is_admin() ) {
                                ?>
                                <td>                                    
                                    <div class="table-data-feature">
                                        <a href="<?php echo site_url( '/admin/category_edit.php?id=' . $product_id . '&action=edit&type=' . $posttype ); ?>" class="item" data-toggle="tooltip" data-placement="top" title="Edit">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                             <!-- <a href="<?php echo site_url( '/admin/product_edit.php?id=' . $product_id . '&action=delete' ); ?>" class="item js-confirm" data-toggle="tooltip" data-placement="top" title="Delete"> -->
                                             <!-- <i class="zmdi zmdi-delete"></i> -->
                                        <!-- </a> -->
                                    </div>                                    
                                </td>
                                <?php } ?>
                            </tr>
                            <tr class="spacer"></tr>
                            <?php
                        endwhile;
                        echo '<tr class="spacer"></tr>';
                    endif; ?>
                </tbody>
            </table>
            <?php echo $paginationCtrls; ?>
        </div>
    </div>
</div>
                        
<?php include_once 'footer.php';