<?php
include('pdf_mc_table.php');
class PDF extends PDF_MC_Table
{
// Page header
	function Header()
	{
		$this->Ln(2);
	    // Logo
	    $this->Image('http://localhost/mentor/customer_portal/uploads/logo/logo15464205382.png',10,5,40);
	    // Arial bold 15
	    $this->SetFont('Arial','B',20);
	    // Title
	    $this->SetWidths(array(45,140));
	    $this->Row1(array('','Rani Luxmi Bai Memorial Senior Secondary School'),'7');
	    $this->Ln(2);
	    $this->SetFont('Arial','B',10);
	    $this->Row1(array('','Boring Road Patna ,800001'),'5');
	    $this->Row1(array('','Boring Road Patna ,800001'),'5');
	    $this->Row1(array('','Boring Road Patna ,800001'),'5');
	    $this->Row1(array('','Boring Road Patna ,800001'),'5');
	    // Line break
	    $this->Ln(2);
	}

	// Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}
$pdf = new PDF('P','mm','A4');
$pdf->AddPage();
$pdf->ln(10);
$pdf->SetFont('Arial','B','20');
$pdf->Cell('130','7','Tekniko Global Privet Limited',1,2);
$pdf->ln(9);
$pdf->Cell('130','7','Tekniko Global Privet Limited',1,2);
$pdf->ln(9);
$pdf->Cell('130','7','Tekniko Global Privet Limited',1,2);
$pdf->ln(9);
$pdf->output();
?>