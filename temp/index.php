<?php

include "fpdf.php";

$file = "/dashboard/drive/STTPP - Pelayanan Publik (halaman depan) - F4.pdf";



class Certificate extends FPDF{

    private $name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi;

    function __construct__($name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi){
        $this->name = $name;
        $this->nip = $nip;
        $this->birthdate = $birthdate;
        $this->golongan = $golongan;
        $this->jabatan = $jabatan;
        $this->unitKerja = $unitKerja;
        $this->instansi = $instansi;
    }

    function header(){
        $this->Ln(20);
        $this->SetFont("Arial", "B", 14);
        $this->Cell(276, 5, "SURAT TANDA TAMAT PELATIHAN", 0, 0, "C");
        $this->Ln();
        $this->SetFont("Times", "", 12);
        $this->Cell(276, 5, "Nomor: SDM.KP.12-31.", 0, 0, "C");
        $this->Ln(10);
        $this->SetFont("Arial", "", 12);

        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        $this->Justify($text, 250, 6);
        $this->Ln(5);


        $this->Cell(114, 0, "Nama", 0, 0, "C");
        $this->Cell(7, 0, ": $this->name", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(110, 0, "NIP", 0, 0, "C");
        $this->Cell(15, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(144, 0, "Tempat/Tanggal Lahir", 0, 0, "C");
        $this->Cell(-53, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(152, 0, "Pangkat/Golongan/Ruang", 0, 0, "C");
        $this->Cell(-69, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(118, 0, "Jabatan", 0, 0, "C");
        $this->Cell(-1, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(121, 0, "Unit Kerja", 0, 0, "C");
        $this->Cell(-7, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);

        $this->Cell(118, 0, "Instansi", 0, 0, "C");
        $this->Cell(-1, 0, ": Nama", 0, 0, "C");
        $this->Ln(8);


        $this->SetFont("Times", "B", 28);
        $this->Cell(260, 5, "TELAH MENGIKUTI", 0, 0, "C");
        $this->Ln(8);


        $text = "Pelatihan Teknis Pelayanan Publik Angkatan I Tahun Anggaran 2019 yang diselenggarakan oleh Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah dari tanggal 03-09 November 2019 di Pusat Pendidikan dan Pelatihan PMI Provinsi Jawa Tengah yang meliputi 57 (lima puluh tujuh) jam pelajaran.";
        $this->SetFont("Arial", "", 12);

        $this->SetLeftMargin(20);
        $this->Justify($text, 250, 6);
        $this->Ln(5);

        $this->SetFont("Arial", "", 12);
        $this->Cell(400, 0, "Depok, 11 November 2019", 0, 0, "C");
        $this->Ln(6);

        $this->SetFont("Arial", "", 12);
        $this->Cell(400, 0, "Plt. KEPALA BPSDM HUKUM DAN HAM,", 0, 0, "C");
        $this->Ln(20);

        $this->SetFont("Arial", "", 12);
        $this->Cell(400, 0, "MIN USIHEN, S.H., M.H.", 0, 0, "C");
        $this->Ln(6);

        $this->SetFont("Arial", "", 12);
        $this->Cell(400, 0, "NIP. 19690309 199403 2 001", 0, 0, "C");
        $this->Ln(8);


    }


    function Justify($text, $w, $h){
        $tab_paragraphe = explode("\n", $text);
        $nb_paragraphe = count($tab_paragraphe);
        $j = 0;

        while ($j<$nb_paragraphe) {
            $paragraphe = $tab_paragraphe[$j];
            $tab_mot = explode(' ', $paragraphe);
            $nb_mot = count($tab_mot);

        // Handle strings longer than paragraph width
            $k=0;
            $l=0;
            while ($k<$nb_mot){
                $len_mot = strlen ($tab_mot[$k]);
                if ($len_mot<($w-5)){
                    $tab_mot2[$l] = $tab_mot[$k];
                    $l++;
                }else{
                    $m=0;
                    $chaine_lettre='';
                    while ($m<$len_mot) {
                        $lettre = substr($tab_mot[$k], $m, 1);
                        $len_chaine_lettre = $this->GetStringWidth($chaine_lettre.$lettre);
                        if($len_chaine_lettre>($w-7)){
                            $tab_mot2[$l] = $chaine_lettre . '-';
                            $chaine_lettre = $lettre;
                            $l++;
                        }else{
                            $chaine_lettre .= $lettre;
                        }
                        $m++;
                    }
                    if($chaine_lettre){
                        $tab_mot2[$l] = $chaine_lettre;
                        $l++;
                    }
                }
                $k++;
            }

        // Justified lines
            $nb_mot = count($tab_mot2);
            $i=0;
            $ligne = '';
            while($i<$nb_mot){

                $mot = $tab_mot2[$i];
                $len_ligne = $this->GetStringWidth($ligne . ' ' . $mot);

                if($len_ligne>($w-5)){

                    $len_ligne = $this->GetStringWidth($ligne);
                    $nb_carac = strlen ($ligne);
                    $ecart = (($w-2) - $len_ligne) / $nb_carac;
                    $this->_out(sprintf('BT %.3F Tc ET',$ecart*$this->k));
                    $this->MultiCell($w,$h,$ligne);
                    $ligne = $mot;
                }else{
                    if($ligne){
                        $ligne .= ' ' . $mot;
                    }else{
                        $ligne = $mot;
                    }
                }$i++;
            }

            $this->_out('BT 0 Tc ET');
            $this->MultiCell($w,$h,$ligne);
            $tab_mot = '';
            $tab_mot2 = '';
            $j++;
        }
    }


}

$pdf = new myPDF();
$pdf->AliasNbPages();
$pdf->AddPage("L", "A4", 0);
$pdf->Output();
// $pdf = new FPDI();
// $pdf->AddPage();
// $pdf->setSourceFile($file);
// $pdf->Output();
