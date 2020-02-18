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
if( ! currentuser_is_admin() ) {
    die( 'You are unauthorized to access this part of our website!' );
}

if( isset( $_POST['update-site-info'] ) ) {
    update_siteinfo( 'site-name', $_POST['site-name'] );
    update_siteinfo( 'site-url', $_POST['site-url'] );
    update_siteinfo( 'company-email', $_POST['company-email'] );
    update_siteinfo( 'company-phone', $_POST['company-phone'] );
    update_siteinfo( 'company-address', $_POST['company-address'] );
    
    // echo site notice
    set_site_notice( 'Update success.', 'success' );
}

$site_title = 'Site Settings';
include_once 'header.php';
include_once 'sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <div class="overview-wrap">
            <h2 class="title-1">Site Settings</h2>
        </div>
    </div>
</div>

<form method="POST" action="settings.php">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <strong>General Settings</strong>
                </div>
                <div class="card-body card-block">
                    <div class="form-group">
                        <label for="site-name">Site Name</label>
                        <input type="text" value="<?php echo get_siteinfo( 'site-name' ); ?>" name="site-name" class="form-control" id="site-name">
                    </div>
                    <div class="form-group">
                        <label for="site-url">Site URL</label>
                        <input type="text" value="<?php echo get_siteinfo( 'site-url' ); ?>" name="site-url" class="form-control" id="site-url">
                    </div>
                    <div class="form-group">
                        <label for="company-email">Company Email</label>
                        <input type="text" value="<?php echo get_siteinfo( 'company-email' ); ?>" name="company-email" class="form-control" id="company-email">
                    </div>
                    <div class="form-group">
                        <label for="company-phone">Company Phone #</label>
                        <input type="text" value="<?php echo get_siteinfo( 'company-phone' ); ?>" name="company-phone" class="form-control" id="company-phone">
                    </div>
                    <div class="form-group">
                        <label for="company-address">Company Address</label>
                        <input type="text" value="<?php echo get_siteinfo( 'company-address' ); ?>" name="company-address" class="form-control" id="company-address">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-md" style="float: right">Save Changes</button>
                    <input type="hidden" name="update-site-info" value="1">
                </div>
            </div>
        </div>
    </div>
</form>
                        
<?php include_once 'footer.php';