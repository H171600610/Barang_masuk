<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        cek_login();

        $this->load->model('Admin_model', 'admin');
        $this->load->library('form_validation');
        $this->load->library('CustomPDF');
    }

    public function index()
    {
        global $tanggal, $table;
        $this->form_validation->set_rules('transaksi', 'Transaksi', 'required|in_list[barang_masuk,barang_keluar]');
        $this->form_validation->set_rules('tanggal', 'Periode Tanggal', 'required');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Laporan Transaksi";
            $this->template->load('templates/dashboard', 'laporan/form', $data);
        } else {
            $input = $this->input->post(null, true);
            $table = $input['transaksi'];
            $tanggal = $input['tanggal'];
            $pecah = explode(' - ', $tanggal);
            $mulai = date('Y-m-d', strtotime($pecah[0]));
            $akhir = date('Y-m-d', strtotime(end($pecah)));

            $query = '';
            if ($table == 'barang_masuk') {
                $query = $this->admin->getBarangMasuk(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
            } else {
                $query = $this->admin->getBarangKeluar(null, null, ['mulai' => $mulai, 'akhir' => $akhir]);
            }

            $this->_cetak($query, $table, $tanggal);
        }
    }

    private function _cetak($data, $table, $tanggal)
    {
        global $table, $tanggal;
        $this->load->library('CustomPDF');

        $pdf = $this->custompdf->getInstance();
        //$pdf = new FPDF();
        $pdf->AddPage('L', 'A4');
        //  $pdf->Cell(60, 7, 'Tanggal : ' . $tanggal, 0, 1, 'C');
        // $pdf->Ln(2);

    /*

        $pdf->SetFont('Times', 'B', 14);
        $pdf->Ln(5);
        $pdf->Cell(290, 7, 'PEMERINTAH PROVINSI KALIMANTAN UTARA ' . $table, 0, 1, 'C');
        $pdf->Cell(290, 7, 'Laporan Buku Penerima ' . $table, 0, 1, 'C');
        $pdf->SetFont('Times', '', 10);
        $pdf->Ln(3);
    */ 

        if ($table == 'barang_masuk') :
            $pdf->Cell(1, 7, '' , 0, 0, 'C');
            $pdf->Cell(8, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Tgl Masuk', 1, 0, 'C');
            $pdf->Cell(33, 7, 'ID Transaksi', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Kode Rekening Belanja', 1, 0, 'C');
            $pdf->Cell(37, 7, 'Kode Rincian Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Nomor SP2D', 1, 0, 'C');
            $pdf->Cell(35, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(28, 7, 'Supplier', 1, 0, 'C');
            $pdf->Cell(26, 7, 'Jumlah Masuk', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Stok', 1, 0, 'C');
            $pdf->Ln();
            $no = 1;
            $jum = 0; 
            foreach ($data as $d) {
                $jum = $jum + $d['stok'];
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(1, 7, '' , 0, 0, 'C');
                $pdf->Cell(8, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(20, 7, $d['tanggal_masuk'], 1, 0, 'C');
                $pdf->Cell(33, 7, $d['id_barang_masuk'], 1, 0, 'C');
                $pdf->Cell(40, 7, $d['kode_rekening_belanja'], 1, 0, 'C');
                $pdf->Cell(37, 7, $d['kode_rincian_barang'], 1, 0, 'C');
                $pdf->Cell(30, 7, $d['nomor_sp2d'], 1, 0, 'L');
                $pdf->Cell(35, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(28, 7, $d['nama_supplier'], 1, 0, 'L');
                $pdf->Cell(26, 7, $d['jumlah_masuk'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Cell(20, 7, $d['stok'], 1, 0, 'C');       
                $pdf->Ln();
            } else :
            $pdf->Cell(1, 7, '' , 0, 0, 'C');
            $pdf->Cell(10, 7, 'No.', 1, 0, 'C');
            $pdf->Cell(25, 7, 'Tgl Keluar', 1, 0, 'C');
            $pdf->Cell(35, 7, 'ID Transaksi', 1, 0, 'C');
            $pdf->Cell(40, 7, 'Kode Rekening Belanja', 1, 0, 'C');
            $pdf->Cell(37, 7, 'Kode Rincian Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Nomor SP2D', 1, 0, 'C');
            $pdf->Cell(50, 7, 'Nama Barang', 1, 0, 'C');
            $pdf->Cell(30, 7, 'Jumlah Keluar', 1, 0, 'C');
            $pdf->Cell(20, 7, 'Stok', 1, 0, 'C');
            $pdf->Ln();

            $no = 1;
            $jum = 0; 
            foreach ($data as $d) {
                $jum = $jum + $d['stok'];
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(1, 7, '' , 0, 0, 'C');
                $pdf->Cell(10, 7, $no++ . '.', 1, 0, 'C');
                $pdf->Cell(25, 7, $d['tanggal_keluar'], 1, 0, 'C');
                $pdf->Cell(35, 7, $d['id_barang_keluar'], 1, 0, 'C');
                 $pdf->Cell(40, 7,$d['kode_rekening_belanja'], 1, 0, 'C');
                $pdf->Cell(37, 7, $d['kode_rincian_barang'], 1, 0, 'C');
                $pdf->Cell(30, 7, $d['nomor_sp2d'], 1, 0, 'L');
                $pdf->Cell(50, 7, $d['nama_barang'], 1, 0, 'L');
                $pdf->Cell(30, 7, $d['jumlah_keluar'] . ' ' . $d['nama_satuan'], 1, 0, 'C');
                $pdf->Cell(20, 7, $d['stok'], 1, 0, 'C');
                $pdf->Ln();
            }
        endif;
        if ($table == 'barang_masuk') {
        $pdf->Cell(1, 7, '' , 0, 0, 'C');
        $pdf->Cell(257, 7, 'Jumlah Total' , 1, 0, 'C');$pdf->Cell(20, 7, $jum, 1, 0, 'C');  
        $pdf->Ln(30);
        $pdf->Cell(100, 7, 'Kepala SKPD/UPTD,' , 0, 0, 'C');
        $pdf->Cell(200, 7, 'Penyimpan Barang,' , 0, 0, 'C');
        $pdf->Ln(20);
        $pdf->Cell(100, 7, '..............................' , 0, 0, 'C');
        $pdf->Cell(200, 7, '..............................' , 0, 1, 'C');
        $pdf->Cell(100, 7, 'NIP..........................' , 0, 0, 'C');
        $pdf->Cell(200, 7, 'NIP..........................' , 0, 1, 'C');
        }else{
        $pdf->Cell(1, 7, '' , 0, 0, 'C');
        $pdf->Cell(257, 7, 'Jumlah Total' , 1, 0, 'C');$pdf->Cell(20, 7, $jum, 1, 0, 'C'); 
        $pdf->Ln(30);
        $pdf->Cell(100, 7, 'Kepala SKPD/UPTD,' , 0, 0, 'C');
        $pdf->Cell(200, 7, 'Penyimpan Barang,' , 0, 0, 'C');
        $pdf->Ln(20);
        $pdf->Cell(100, 7, '..............................' , 0, 0, 'C');
        $pdf->Cell(200, 7, '..............................' , 0, 1, 'C');
        $pdf->Ln(0);
        $pdf->Cell(100, 7, 'NIP..........................' , 0, 0, 'C');
        $pdf->Cell(200, 7, 'NIP..........................' , 0, 1, 'C');
        }
        $file_name = $table . ' ' . $tanggal;
        $pdf->Output('I', $file_name);
    }
}
