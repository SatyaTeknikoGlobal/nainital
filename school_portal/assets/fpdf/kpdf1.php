<?php
    require_once('pdf_mc_table.php');
    /**
     * 
     */
    class KPDF1 extends PDF_MC_Table
    {
        function __construct($orientation='L', $unit='mm', $size='A4',$salesmanID = "",$salesman = "",$date = "")
        {
            parent::__construct($orientation,$unit,$size);
            $this->salesmanID = $salesmanID;
            $this->salesman = $salesman;
            $this->date = $date;

        }
        //Page Header
        function Header()
        {
            
            $this->SetTitle('Esales Track | Admin Panel');
            $this->SetAuthor("Tekniko Global Pvt. Ltd.");
            $this->SetMargins('5', '10');
            $this->SetFont('Arial','',12);
            $this->Cell(45,6,'SALES REMITTANCE','B');
            $this->Cell(50,6,'',0);
            $this->Cell(90,6,'KALAHANU RETAIL VENTURE PVT LTD',1,0,'C');
            $this->Cell(50,6,'',0);
            $this->Cell(26,6,'PDC Report','B');
            $this->ln();
            $this->Cell(90,1,'',0,2,'C');
            $this->Cell(15,6,'',0,0,'C');
            $this->Cell(100,6,'',0);
            $this->Cell(60,6,'GST NO: 05AADCK1490Q1Z6',1,2,'C');
            $this->ln();
            $this->Cell(45,6,"DSE CODE - $this->salesmanID",'B',0,'C');
            $this->Cell(45,6,"",'B',0,'C');
            $this->Cell(80,6,"DSE NAME - $this->salesman",'B',0,'C');
            $this->Cell(45,6,"",'B',0,'C');
            $this->Cell(70,6,"SRR DATE - $this->date",'B',0,'C');
            $this->ln();
            $this->Cell(90,3,'',0,1);
            $this->SetWidths(array(13,35,20,34,20,20,16,22,20,16,20,20,12,17));
            srand(microtime()*1000000);
            $this->Row(array("Cust. Code","Cust. Name","Inv. Date","Invoice No","OLD AR Amt","Cash","Chq./ NEFT/ SRN","Chq./ NEFT (Bank/No)","Date (PDC)","Chq./ NEFT/ SRN Amt.","Total Amt","AR Amt","AR Days","ON Acc"));
            $this->Cell(90,1,'',0,1,'C');
        }

        //Page Footer
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
?>