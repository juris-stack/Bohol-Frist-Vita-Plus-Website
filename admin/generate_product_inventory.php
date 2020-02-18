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

$type = ! empty( $_GET['type'] ) ? esc_str( $_GET['type'] ) : 'today';


$report_title = 'ITEMS LISTING / INVENTORY (ALL ITEMS)';
$select_stmt = $mysqli->prepare( "SELECT * FROM products ORDER BY date_added DESC" );
$select_stmt->execute();
$select_result = $select_stmt->get_result();
$rows = [];
$count_rows = $select_result->num_rows;
if( $count_rows > 0 ) {
    while( $row = $select_result->fetch_assoc() ) {        
        $rows[] = $row;
    }
}
$select_stmt->close();

// define some HTML content with style
$html = '<div style="text-align: center;">'
        // . '<img src="../assets/images/logo.png" alt="" style="width: 150px; height: auto; margin-bottom: 20px;" /></div>';
        . '<h1>FIRST VITA PLUS SC-BOHOL</h1></div>';
// $html .= '<p>Total no. of products: ' . $count_rows . '</p>';
$html .= '<h3>' . $report_title . '</h3>';
$html .= '<div><table> <tr><td>No. of items: ' . $count_rows . '</td><td style="text-align: right;"> Date:' . date( 'M j, Y' ) .'</td></tr></table></div>';
$html .= '<table cellpadding="4" cellspacing="1">
            <tr class="thead">
                    <td bgcolor="#666666" color="#ffffff" colspan="2">Item Code</td>
                    <td bgcolor="#666666" color="#ffffff" colspan="2">Description</td>
                    <td bgcolor="#666666" color="#ffffff">S.R.P.</td>                    
                    <td bgcolor="#666666" color="#ffffff">QTYHND</td>
                </tr>';

                if( $count_rows > 0 ) :
                    foreach( $rows as $row ) :
                        

                        $html .= '<tr>
                            <td colspan="2">' . $row['item_code'] . '</td>';
                        $html .= '
                            <td colspan="2">' . $row['name'] . '</td>
                            <td>PHP ';
                                if( (int) $row['sale_price'] > 0 ) {
                                     $html .= '<span style="text-decoration: line-through;">' . $row['price'] . '</span>';
                                }else{
                                     $html .= '<span>' . $row['price'] . '</span>';
                                }
                                if( (int) $row['sale_price'] > 0 ) {
                                     $html .= ' <span>' . $row['sale_price'] . '</span>';
                                } 
                        $html .= '</td>                           
                            <td>' . $row['stocks'] . '</td>
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