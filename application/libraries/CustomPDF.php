<?php
/*
class CustomPDF
{
    public function __construct()
    {
        require (APPPATH . '/third_party/fpdf/fpdf.php');
    }

    function Header(){
        $this->SetFont();
        $this->Cell(80);
        $this->Cell(30,10,'Title',1,0,'C');
        $this->Ln(20);
}
	function Footer(){
        // Position at 1.5 cm from bottom
         $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
*/
require (APPPATH . '/third_party/fpdf/fpdf.php');
    

    /**
     * 
     */
    class CustomPDF extends FPDF
    {
		public function Header(){
        global $tanggal,$table;
        $this->SetFont('Times', 'B', 14);
        $this->Ln(5);
        $this->Cell(290, 7, 'PEMERINTAH PROVINSI KALIMANTAN UTARA ', 0, 1, 'C');
        $this->Cell(290, 7, 'Laporan Buku Penerima '.str_replace("_", " ", ucfirst($table)), 0, 1, 'C');
        $this->SetFont('Times', '', 10);
        $this->Ln(3);   
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(88, 7, 'SKPD / UPTD : SMKN 1 Tanjung Palas Utara - BOP', 0, 1, 'C');
        $this->Cell(60, 7, 'Tanggal : ' . $tanggal, 0, 1, 'C');
		}    	
		public function getInstance(){
			return new CustomPDF();
		}
    }

