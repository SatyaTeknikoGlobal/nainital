<?php
    require_once('pdf_mc_table.php');
    /**
     * 
     */
    class KPDF extends PDF_MC_Table
    {
        function __construct($orientation='L', $unit='mm', $size='A4',$school_info = "",$base,$admin_info)
        {
            parent::__construct($orientation,$unit,$size);
            $this->school_name = ucwords(trim($school_info->school_name));
            $this->school_address = ucwords(trim($admin_info->address));
            $this->school_logo = $base.'/'.$school_info->logo;
            $this->phone = 'Ph. No. '.$school_info->phone;
        }
        //Page Header
        function Header()
        {
            $this->SetTitle($this->school_name.' invoice');
            $this->SetAuthor("Mentor ERP");
            //$this->SetMargins('5', '10');
            // Logo
            $this->Image($this->school_logo,10,5,40);
            // Arial bold 15
            $this->SetFont('Arial','B',20);
            // Title
            $this->Ln(2);
            $this->SetWidths(array(45,130));
            $this->Row1(array('',$this->school_name),'7');
            $this->Ln(2);
            $this->SetFont('Arial','B',10);
            $this->Row1(array('',$this->school_address),'5');
            $this->Row1(array('',$this->phone),'5');
            // Line break
            $this->Ln(2);
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