<?php
/* 
 * Report Template
 * 
 * @package SJM
 * @author
 */

// include the admin functions
require_once 'functions.php';

$site_name = get_siteinfo( 'site-name' );

// Include the main TCPDF library (search for installation path).
require_once('../source/tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($site_name);
$pdf->SetTitle('Sales Report');
$pdf->SetSubject('Sales Report');
$pdf->SetKeywords('Report, Sales, Sales Report, summmary');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

/* NOTE:
 * *********************************************************
 * You can load external XHTML using :
 *
 * $html = file_get_contents('/path/to/your/file.html');
 *
 * External CSS files will be automatically loaded.
 * Sometimes you need to fix the path of the external CSS.
 * *********************************************************
 */

$type = ! empty( $_GET['type'] ) ? esc_str( $_GET['type'] ) : '';
$from = ! empty( $_GET['from'] ) ? esc_str( $_GET['from'] ) : '';
$to = ! empty( $_GET['to'] ) ? esc_str( $_GET['to'] ) : '';
$order_id = !empty( $_GET['id'] ) ? $_GET['id'] : '';

$dateFromTransform = date("M d, Y", strtotime($from));
$datetOTransform = date("M d, Y", strtotime($to));

$earnings = 0;
$managed_by = get_currentuser( 'username' );
$role = get_currentuser( 'role' );
$report_title = 'Sales Report Between ' . $dateFromTransform . ' - ' . $datetOTransform;
            $select_stmt = $mysqli->prepare( "SELECT * FROM orders WHERE date_added BETWEEN '$from' and '$to' AND status != 'pending' ORDER BY date_added DESC" );
$select_stmt->execute();
$select_result = $select_stmt->get_result();
$rows = [];
$user_details = [];
$billing = [];
$count_rows = $select_result->num_rows;
if( $count_rows > 0 ) {
    while( $row = $select_result->fetch_assoc() ) {
        $earnings += $row['amount'];
        $rows[] = $row;
        $user_details = unserialize( $row['user_details'] );
        $billing = $user_details['billing'];
    }
}
$select_stmt->close();

// define some HTML content with style
$html = '<div style="text-align: center; margin-bottom: 10px;">'
        // . '<img src="../assets/images/logo.png" alt="" style="width: 150px; height: auto; margin-bottom: 20px;" />'
        . '<h1>FIRST VITA PLUS SC-BOHOL</h1>'
        . '<h2>' . $report_title . '</h2></div>';
$html .= '<h4>Total Sales: PHP ' . number_format($earnings, 2) . '</h4>';
    $html .= '<p>Total Orders: ' . $count_rows . '</p>';
$html .= '<table cellpadding="4" cellspacing="1">
            <tr class="thead">                    
                    <td bgcolor="#666666" color="#ffffff" colspan="2">Items</td>';
                    if (!empty( $_GET['id'] )){
                        $html .= '<td bgcolor="#666666" color="#ffffff">Customer</td>';
                    }
                    $html .= '<td bgcolor="#666666" color="#ffffff">Amount</td>
                    <td bgcolor="#666666" color="#ffffff">Date</td>
                    <td bgcolor="#666666" color="#ffffff">Type</td>
                </tr>';

                if( $count_rows > 0 ) :
                    foreach( $rows as $row ) :
                        

                        $html .= '<tr>                            
                            <td colspan="2">';
                        foreach( unserialize( $row['products'] ) as $id => $qty ) {
                            $product = get_product( $id );
                            $price = $product['price'];
                            $slug = $product['slug'];
                            $name = $product['name'];
                            $total = $price * $qty;
                            $html .= '- <a href="' . site_url( '/product.php?p=' . $slug ) . '">' . $name . '</a> X ' . $qty . ' @ ' . $price . ' = ' . $total . '<br />';
                        }
                        $html .= '</td>';
                            if(!empty( $_GET['id'] )){
                                $html .= '<td>' . $billing['firstname'] . '</td>';
                            }else{
                                //
                            }
                            
                            $html .= '<td>PHP ' . number_format($row['amount'], 2) . '</td>
                            <td>' . $row['date_added'] . '</td>
                            <td>' . $row['type'] . '</td>
                        </tr>';

                    endforeach;
                endif;

        $html .= '</table>';


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('report-' . time() . '.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>