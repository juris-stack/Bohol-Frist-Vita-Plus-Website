<?php
/** 
 * Navigation template
 * 
 * @package SJM
 * @author 
 */
$items = array(
    'index.php' => array(
        'title' => 'Dashboard',
        'icon' => 'fa-tachometer-alt'
    )
);
if( ! currentuser_is_customer() && !currentuser_is_member() ) {
    $items['orders.php'] = array(
        'title' => 'Reservations',
        'icon' => 'fa-shipping-fast'
    );
    // $items['pos.php'] = array(
    //     'title' => 'Quotation',
    //     'icon' => 'fa-shopping-basket',
    //     'subitems' => array(
    //         'pos_members.php' => 'Member',
    //         'pos.php' => 'Regular'
    //     )
    // );
    // $items['pos.php'] = array(
    //     'title' => 'Qoutation',
    //     'icon' => 'fa-shopping-basket'
    // );
    $items['walkin_reports.php'] = array(
        'title' => 'Reports',
        'icon' => 'fa-chart-line',
        'subitems' => array(
            'walkin_reports.php' => 'Walk-in',
            'reservation_reports.php' => 'Online Reservation',            
        )
    );
    // $items['walkin_reports.php'] = array(
    //     'title' => ' Walk-in Reports',
    //     'icon' => 'fa-chart-line',
    // );
    // $items['reservation_reports.php'] = array(
    //     'title' => 'Reservation Reports',
    //     'icon' => 'fa-chart-line',
    // );
    $items['search_sales.php'] = array(
        'title' => 'Search Sales',
        'icon' => 'fa-search',
    );
    $items['products.php'] = array(
        'title' => 'Items',
        'icon' => 'fa-box',
    );
    $items['category.php'] = array(
        'title' => 'Category',
        'icon' => 'fa-list'
    );
    if( currentuser_is_admin() ) {        
        $items['users.php'] = array(
            'title' => 'Users',
            'icon' => 'fa-users',
        );
        
        $items['settings.php'] = array(
            'title' => 'Settings',
            'icon' => 'fa-cog'
        );
    }
}else{
    $items['user.php'] = array(
        'title' => 'Account',
        'icon' => 'fa-user'
    );
    $items[site_url( '/products.php' )] = array(
        'title' => 'Shop Now',
        'icon' => 'fa-shopping-basket'
    );    
}
$url_filename = get_currenturl_filename();
$html = '';
foreach( $items as $file => $item ) {
    $class = [];
    $html .= '<li class="';
    if( ! empty( $item['subitems'] ) ) {
        $html .= 'has-sub';
    }
    $html .= '">';
    $link = ! empty( $item['subitems'] ) ? '#' : $file;
    $html .= '<a class="js-arrow" href="' . $link . '"><i class="fas ' . $item['icon'] . '"></i>' . $item['title'] . '</a>';
    if( ! empty( $item['subitems'] ) ) {
        $html .= '<ul class="list-unstyled navbar__sub-list js-sub-list">';
        foreach( $item['subitems'] as $subfile => $subtitle ) {
            $html .= '<li><a href="' . $subfile . '">' . $subtitle . '</a></li>';
        }
        $html .= '</ul>';
    }
    $html .= '</li>';
} 

echo $html;