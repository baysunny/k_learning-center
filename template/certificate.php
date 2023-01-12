<?php

function hex2dec($couleur = "#000000"){
    $R = substr($couleur, 1, 2);
    $rouge = hexdec($R);
    $V = substr($couleur, 3, 2);
    $vert = hexdec($V);
    $B = substr($couleur, 5, 2);
    $bleu = hexdec($B);
    $tbl_couleur = array();
    $tbl_couleur['R']=$rouge;
    $tbl_couleur['V']=$vert;
    $tbl_couleur['B']=$bleu;
    return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
    return $px*25.4/72;
}

function txtentities($html){
    $trans = get_html_translation_table(HTML_ENTITIES);
    $trans = array_flip($trans);
    return strtr($html, $trans);
}

class CTemplate extends FPDF{

    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;
    private $name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi, $code, $tempat, $backgroundImage;
    private $textTemplate;
    private $userData;

    function __init__Data__($name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi, $code, $tempat){
        $this->name = $name;
        $this->nip = $nip;
        $this->birthdate = $birthdate;
        $this->golongan = $golongan;
        $this->jabatan = $jabatan;
        $this->unitKerja = $unitKerja;
        $this->instansi = $instansi;
        $this->code = $code;
        $this->tempat = $tempat;
    }

    function __init__Data($userData){
        $this->userData = $userData;
    }

    function __init__Template($textTemplate){
        $this->textTemplate = $textTemplate;
    }

    function header(){
        if(strlen($this->textTemplate["backgroundImage"]) > 4){
            $this->Image($_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$this->textTemplate["backgroundImage"], 0, 0, $this->w, $this->h);
        }
        $this->SetFont("Arial", "B", 16);
        $this->Cell(240, 5, $this->textTemplate["x1"], 0, 0, "C");
        $this->Ln(9);
        $this->SetFont("Times", "", 14);
        $this->Cell(240, 5, $this->textTemplate["x2"], 0, 0, "C");
        $this->Ln(10);
    }

    function bBody(){

        $this->SetFont("Arial", "", 12);
        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        $this->Justify($this->textTemplate["x3"], 240, 6);
        $this->Ln(1);


        $this->setX(72);
        $this->Cell(58, 8, "Nama", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["name"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Nip", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["nip"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Tempat/Tanggal Lahir", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["tempat"].", ".$this->userData["birthDate"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Pangkat/Golongan/Ruang", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["golongan"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Jabatan", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["jabatan"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Unit Kerja", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["unitKerja"], 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "instansi", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["instansi"], 0, 1, "L");
        $this->Ln(2);

        $this->SetFont("Times", "B", 28);
        $this->Cell(240, 10, $this->textTemplate["x4"], 0, 1, "C");
        $this->Ln(4);

        $this->SetFont("Arial", "", 12);
        $this->SetLeftMargin(20);
        if($this->userData["detailHistoryRead"]["certificateType"] == "yearly"){
            $this->Justify(
                "Pembelajaran Mandiri Melalui Badiklat Learning Center. dengan perhitungan meliputi {$this->userData['detailHistoryRead']['totalReadingTime']} jam pelajaran."
                , 240, 6);
        }else{
            $this->Justify($this->textTemplate["x5"], 240, 6);    
        }
        
        $this->Ln(7);
    }

    function footer(){
        global $CONFIG;

        $stupidUrl = $CONFIG["CERTIFICATE"]."dashboard/drive/certificate/verified.php?certificate-id=".$this->code."&";
        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl."%2F&choe=UTF-8";
        $this->Image($googleUrl, 30, 138, 40, 30, 'PNG');
        $this->setX(140);
        $this->Cell(120, 8, $this->textTemplate["x6"], 0, 1, "C");




        $this->setX(140);
        $this->SetFont("Arial", "", 12);
        $temp = explode(" ", $this->textTemplate["x7"]);
        if(sizeof($temp) > 4){
            $s = (int)( sizeof($temp) / 2 );
            $x1 = (implode(" ", array_slice($temp, 0, $s - 2)));
            $x2 = (implode(" ", array_slice($temp, $s - 1)));
            $this->Cell(120, 7, $x1, 0, 1, "C");
            $this->setX(140);
            $this->Cell(120, 8, $x2, 0, 0, "C");
        }else{
            $this->Cell(120, 0, $this->textTemplate["x7"], 0, 0, "C");
        }

        $this->Ln(20);
        $this->Cell(120, 8, $this->userData["certificateCode"], 0, 0, "L");


        $this->SetFont("Arial", "", 12);
        $this->Cell(120, 8, $this->textTemplate["x8"], 0, 1, "C");

        $this->setX(140);
        $this->Cell(120, 8, $this->textTemplate["x9"], 0, 0, "C");
    }

    function bodyMe(){
        $this->SetFont("Arial", "", 12);
        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        $this->Justify($this->textTemplate["x3"], 190, 6);
        $this->Ln(5);
        if(strlen($this->textTemplate["background_image"]) > 4){
            $this->Image($_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$this->textTemplate["background_image"], 0, 0, $this->w, $this->h);
        }
        $this->Ln(8);
        $this->setX(71);
        $this->Cell(1, 0, "Nama", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->name, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "NIP", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->nip, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "Tempat/Tanggal Lahir", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->tempat.", ".$this->birthdate, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Pangkat/Golongan/Ruang", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->golongan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Jabatan", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->jabatan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Unit Kerja", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->unitKerja, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "instansi", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->instansi, 0, 0, "L");
        $this->Ln(13);


        $this->SetFont("Times", "B", 28);
        $this->Cell(260, 5, $this->textTemplate["x4"], 0, 0, "C");
        $this->Ln(8);

        $this->SetFont("Arial", "", 12);

        $this->SetLeftMargin(20);
        $this->Justify($this->textTemplate["x5"], 250, 6);
    }

    function header1(){

        $this->Ln(20);
        $this->SetFont("Arial", "B", 14);
        // $this->Cell(276, 5, "SURAT TANDA TAMAT PELATIHAN", 0, 0, "C");
        $this->Cell(276, 5, $this->textTemplate["x1"], 0, 0, "C");
        $this->Ln();
        $this->SetFont("Times", "", 12);
        // $this->Cell(276, 5, "Nomor: SDM.KP.12-31.", 0, 0, "C");
        $this->Cell(276, 5, $this->textTemplate["x2"], 0, 0, "C");
        $this->Ln(10);
        $this->SetFont("Arial", "", 12);

        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        // $this->Justify($text, 250, 6);
        $this->Justify($this->textTemplate["x3"], 250, 6);
        $this->Ln(5);
        if(strlen($this->textTemplate["background_image"]) > 4){
            $this->Image($_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$this->textTemplate["background_image"], 0, 0, $this->w, $this->h);
        }
        $this->setX(71);
        $this->Cell(1, 0, "Nama", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->name, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "NIP", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->nip, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "Tempat/Tanggal Lahir", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->tempat.", ".$this->birthdate, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Pangkat/Golongan/Ruang", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->golongan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Jabatan", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->jabatan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Unit Kerja", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->unitKerja, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "instansi", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->instansi, 0, 0, "L");
        $this->Ln(13);


        $this->SetFont("Times", "B", 28);
        // $this->Cell(260, 5, "TELAH MENGIKUTI", 0, 0, "C");
        $this->Cell(260, 5, $this->textTemplate["x4"], 0, 0, "C");
        $this->Ln(8);


        $text = "Pelatihan Teknis Pelayanan Publik Angkatan I Tahun Anggaran 2019 yang diselenggarakan oleh Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah dari tanggal 03-09 November 2019 di Pusat Pendidikan dan Pelatihan PMI Provinsi Jawa Tengah yang meliputi 20 (dua puluh) jam pelajaran.";
        $this->SetFont("Arial", "", 12);

        $this->SetLeftMargin(20);
        // $this->Justify($text, 250, 6);
        $this->Justify($this->textTemplate["x5"], 250, 6);
        $this->Ln(15);
        global $CONFIG;
        $this->setX(50);
        $stupidUrl = $CONFIG["CERTIFICATE"]."dashboard/drive/certificate/verified.php?certificate-id=".$this->code."&";
        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl."%2F&choe=UTF-8";
        $this->Image($googleUrl, 45, 160, 30, 0, 'PNG');
        $this->setX(193);
        // $this->Cell(400, 0, "Depok, 11 November 2019", 0, 0, "L");
        $this->Cell(400, 0, $this->textTemplate["x6"], 0, 0, "L");
        $this->Ln(8);

        $this->setX(218);
        $this->SetFont("Arial", "", 12);
        // $this->Cell(1, 0, "Plt. KEPALA BPSDM HUKUM DAN HAM,", 0, 0, "C");
        $this->Cell(1, 0, $this->textTemplate["x7"], 0, 0, "C");
        $this->Ln(30);
        $this->setX(30);
        $this->Cell(1, 0, $this->code, 0, 0, "L");


        $this->SetFont("Arial", "", 12);
        $this->setX(219);
        // $this->Cell(1, 0, "MIN USIHEN, S.H., M.H.", 0, 0, "C");
        $this->Cell(1, 0, $this->textTemplate["x8"], 0, 0, "C");
        $this->Ln(6);

        $this->SetFont("Arial", "", 12);
        // $this->Cell(400, 0, "NIP. 19690309 199403 2 001", 0, 0, "C");
        $this->Cell(400, 0, $this->textTemplate["x9"], 0, 0, "C");
        $this->Ln(8);
    }

    function header11(){

        $this->Ln(20);
        $this->SetFont("Arial", "B", 14);
        // $this->Cell(276, 5, "SURAT TANDA TAMAT PELATIHAN", 0, 0, "C");
        $this->Cell(276, 5, $this->textTemplate["x1"], 0, 0, "C");
        $this->Ln();
        $this->SetFont("Times", "", 12);
        // $this->Cell(276, 5, "Nomor: SDM.KP.12-31.", 0, 0, "C");
        $this->Cell(276, 5, $this->textTemplate["x2"], 0, 0, "C");
        $this->Ln(10);
        $this->SetFont("Arial", "", 12);

        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        // $this->Justify($text, 250, 6);
        $this->Justify($this->textTemplate["x3"], 250, 6);
        $this->Ln(5);
        if(strlen($this->textTemplate["background_image"]) > 4){
            $this->Image($_SERVER['DOCUMENT_ROOT']."/dashboard/drive/images/".$this->textTemplate["background_image"], 0, 0, $this->w, $this->h);
        }
        $this->setX(71);
        $this->Cell(1, 0, "Nama", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->name, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "NIP", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->nip, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "Tempat/Tanggal Lahir", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->tempat.", ".$this->birthdate, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Pangkat/Golongan/Ruang", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->golongan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Jabatan", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->jabatan, 0, 0, "L");
        $this->Ln(8);


        $this->setX(71);
        $this->Cell(1, 0, "Unit Kerja", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->unitKerja, 0, 0, "L");
        $this->Ln(8);

        $this->setX(71);
        $this->Cell(1, 0, "instansi", 0, 0, "L");
        $this->setX(124);
        $this->Cell(70, 0, ": ".$this->instansi, 0, 0, "L");
        $this->Ln(13);


        $this->SetFont("Times", "B", 28);
        // $this->Cell(260, 5, "TELAH MENGIKUTI", 0, 0, "C");
        $this->Cell(260, 5, $this->textTemplate["x4"], 0, 0, "C");
        $this->Ln(8);


        $text = "Pelatihan Teknis Pelayanan Publik Angkatan I Tahun Anggaran 2019 yang diselenggarakan oleh Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah dari tanggal 03-09 November 2019 di Pusat Pendidikan dan Pelatihan PMI Provinsi Jawa Tengah yang meliputi 20 (dua puluh) jam pelajaran.";
        $this->SetFont("Arial", "", 12);

        $this->SetLeftMargin(20);
        // $this->Justify($text, 250, 6);
        $this->Justify($this->textTemplate["x5"], 250, 6);
        $this->Ln(15);
        global $CONFIG;
        $this->setX(50);
        $stupidUrl = $CONFIG["CERTIFICATE"]."dashboard/drive/certificate/verified.php?certificate-id=".$this->code."&";
        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl."%2F&choe=UTF-8";
        $this->Image($googleUrl, 45, 160, 30, 0, 'PNG');
        $this->setX(193);
        // $this->Cell(400, 0, "Depok, 11 November 2019", 0, 0, "L");
        $this->Cell(400, 0, $this->textTemplate["x6"], 0, 0, "L");
        $this->Ln(8);

        $this->setX(218);
        $this->SetFont("Arial", "", 12);
        // $this->Cell(1, 0, "Plt. KEPALA BPSDM HUKUM DAN HAM,", 0, 0, "C");
        $this->Cell(1, 0, $this->textTemplate["x7"], 0, 0, "C");
        $this->Ln(30);
        $this->setX(30);
        $this->Cell(1, 0, $this->code, 0, 0, "L");


        $this->SetFont("Arial", "", 12);
        $this->setX(219);
        // $this->Cell(1, 0, "MIN USIHEN, S.H., M.H.", 0, 0, "C");
        $this->Cell(1, 0, $this->textTemplate["x8"], 0, 0, "C");
        $this->Ln(6);

        $this->SetFont("Arial", "", 12);
        // $this->Cell(400, 0, "NIP. 19690309 199403 2 001", 0, 0, "C");
        $this->Cell(400, 0, $this->textTemplate["x9"], 0, 0, "C");
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

    function WriteHTML($html){
        //HTML parser
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
        $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach($a as $i=>$e){
            if($i%2==0){
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,stripslashes(txtentities($e)));
            }else{
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr){
        //Opening tag
        switch($tag){
            case 'STRONG':
                $this->SetStyle('B',true);
                break;
            case 'EM':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
                }
    }

    function CloseTag($tag){
        //Closing tag
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont=false;
            }
        }
    }

    function SetStyle($tag, $enable){
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
        {
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt){
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}


class TemplateCertificateEvent extends FPDF{

    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;
    private $name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi, $code, $tempat, $backgroundImage;
    private $textTemplate;

    function __init__Data($name, $nip, $birthdate, $golongan, $jabatan, $unitKerja, $instansi, $code, $tempat){
        $this->name = $name;
        $this->nip = $nip;
        $this->birthdate = $birthdate;
        $this->golongan = $golongan;
        $this->jabatan = $jabatan;
        $this->unitKerja = $unitKerja;
        $this->instansi = $instansi;
        $this->code = $code;
        $this->tempat = $tempat;
    }

    function __init__Template($textTemplate){
        $this->textTemplate = $textTemplate;
    }

    function header(){
        if(strlen($this->textTemplate["backgroundImage"]) > 4){
            $this->Image($_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-certificate-background/".$this->textTemplate["backgroundImage"], 0, 0, $this->w, $this->h);
        }
        $this->SetFont("Arial", "B", 16);
        $this->Cell(240, 5, $this->textTemplate["x1"], 0, 0, "C");
        $this->Ln(9);
        $this->SetFont("Times", "", 14);
        $this->Cell(240, 5, $this->textTemplate["x2"], 0, 0, "C");
        $this->Ln(10);
    }

    function bBody(){

        $this->SetFont("Arial", "", 12);
        $text = "Badan Pengembangan Sumber Daya Manusia Hukum dan HAM Kementerian Hukum dan HAM Republik Indonesia melalui Balai Pendidikan dan Pelatihan Hukum dan HAM Jawa Tengah berdasarkan Undang-Undang Nomor 5 Tahun 2014 tentang Aparatur Sipil Negara, serta ketentuan pelaksanaannya menyatakan bahwa:";

        $this->SetLeftMargin(20);
        $this->Justify($this->textTemplate["x3"], 240, 6);
        $this->Ln(1);


        $this->setX(72);
        $this->Cell(58, 8, "Nama", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->name, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Nip", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->nip, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Tempat/Tanggal Lahir", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->tempat.", ".$this->birthdate, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Pangkat/Golongan/Ruang", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->golongan, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Jabatan", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->jabatan, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "Unit Kerja", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->unitKerja, 0, 1, "L");

        $this->setX(72);
        $this->Cell(58, 8, "instansi", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->instansi, 0, 1, "L");
        $this->Ln(2);

        $this->SetFont("Times", "B", 28);

        $this->Cell(240, 10, $this->textTemplate["x4"], 0, 1, "C");
        $this->Ln(4);



        $this->SetFont("Arial", "", 12);

        $this->SetLeftMargin(20);
        $this->Justify($this->textTemplate["x5"], 240, 6);
        $this->Ln(7);
    }

    function footer(){
        global $CONFIG;

        $stupidUrl = $CONFIG["CERTIFICATE"]."dashboard/drive/certificate/verified.php?certificate-id=".$this->code."&";
        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=http%3A%2F%2F".$stupidUrl."%2F&choe=UTF-8";
        $this->Image($googleUrl, 30, 138, 35, 30, 'PNG');
        $this->setX(140);
        $this->Cell(120, 8, $this->textTemplate["x6"], 0, 1, "C");




        $this->setX(140);
        $this->SetFont("Arial", "", 12);
        $temp = explode(" ", $this->textTemplate["x7"]);
        if(sizeof($temp) > 4){
            $s = (int)( sizeof($temp) / 2 );
            $x1 = (implode(" ", array_slice($temp, 0, $s - 2)));
            $x2 = (implode(" ", array_slice($temp, $s - 1)));
            $this->Cell(120, 7, $x1, 0, 1, "C");
            $this->setX(140);
            $this->Cell(120, 8, $x2, 0, 0, "C");
        }else{
            $this->Cell(120, 0, $this->textTemplate["x7"], 0, 0, "C");
        }

        $this->Ln(20);
        $this->Cell(120, 8, $this->code, 0, 0, "L");


        $this->SetFont("Arial", "", 12);
        $this->Cell(120, 8, $this->textTemplate["x8"], 0, 1, "C");

        $this->setX(140);
        $this->Cell(120, 8, $this->textTemplate["x9"], 0, 0, "C");
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

    function WriteHTML($html){
        //HTML parser
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
        $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,stripslashes(txtentities($e)));
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr){
        //Opening tag
        switch($tag){
            case 'STRONG':
                $this->SetStyle('B',true);
                break;
            case 'EM':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
                }
    }

    function CloseTag($tag){
        //Closing tag
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont=false;
            }
        }
    }

    function SetStyle($tag, $enable){
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s){
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt){
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}


class TemplateRegistrationCodeQRCode extends FPDF{
    protected $B;
    protected $I;
    protected $U;
    protected $HREF;
    protected $fontList;
    protected $issetfont;
    protected $issetcolor;
    private $QRCode;
    private $event;
    private $userData;
    private $host;

    function __init__Data($QRCode, $event, $userData){
        $this->QRCode = $QRCode;
        $this->event = $event;
        $this->userData = $userData;
    }

    function __init__System($host){
        $this->host = $host;
    }

    function header(){
        $this->SetFont("Arial", "B", 16);
        $this->setY(15);
        $this->setX(20);
        $this->Cell(220, 8, "Learning Center", 0, 1, "C");

        $this->setX(20);
        $this->Cell(220, 8, "Kementrian Hukum dan Hak Asasi Manusia", 0, 1, "C");
        
        $this->Line(20, 35, 240, 35);
        $this->Ln(1);
        
    }

    function bBody(){
        $local_file_path = $this->host."/dashboard/drive/drive-event/event-temporary-files/".$this->userData["image"];
        $remote_file_path = $this->host."dashboard/drive/drive-user/user-images/".$this->userData["image"];

        // $curlHandler=curl_init();

        // curl_setopt($curlHandler, CURLOPT_URL, $remote_file_path);

        // curl_setopt($curlHandler, CURLOPT_HEADER,0);

        // curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);

        // $local_file_path = curl_exec($curlHandler);



        

        $this->SetFont("Arial", "", 12);
        $googleUrl = "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".$this->QRCode."&choe=UTF-8";
        $this->Image($googleUrl, 20, 38, 60, 60, 'PNG');

        
        $logo = $_SERVER['DOCUMENT_ROOT']."/dashboard/drive/drive-event/event-important-files/logo.png";
        $this->Image($logo, 28, 10, 24, 24);
        
        $this->Ln(1);        
        $this->setY(38);

        $this->setX(20);
        $this->Cell(220, 8, "ID-CARD Event", 0, 1, "C");

        $this->setX(90);
        $this->Cell(40, 8, "Nama", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["name"], 0, 1, "L");

        $this->setX(90);
        $this->Cell(40, 8, "Nip", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["nip"], 0, 1, "L");

        $this->setX(90);
        $this->Cell(40, 8, "Event", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->event["eventName"], 0, 1, "L");

        $this->setX(90);
        $this->Cell(40, 8, "RegistrationID", 0, 0, "L");
        $this->Cell(110, 8, ": ".$this->userData["userRegistration"]["registrationCode"], 0, 1, "L");

        $this->setX(90);
        $this->Cell(40, 8, "Event Dimulai", 0, 0, "L");
        $this->Cell(110, 8, ": ".date_formating($this->event["eventStart"])." / ".time_formating2($this->event["eventStart"]), 0, 1, "L");

        $this->setX(90);
        $this->Cell(40, 8, "Event Berakhir", 0, 0, "L");
        $this->Cell(110, 8, ": ".date_formating($this->event["eventEnd"])." / ".time_formating2($this->event["eventEnd"]), 0, 1, "L");


        $this->setX(90);
        $this->Cell(40, 8, "Tgl Registrasi", 0, 0, "L");
        $this->Cell(110, 8, ": ".date_formating($this->userData["userRegistration"]["dateCreated"]), 0, 1, "L");
        // curl_close($curlHandler);
        

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

    function WriteHTML($html){
        //HTML parser
        $html=strip_tags($html,"<b><u><i><a><img><p><br><strong><em><font><tr><blockquote>"); //supprime tous les tags sauf ceux reconnus
        $html=str_replace("\n",' ',$html); //remplace retour à la ligne par un espace
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //éclate la chaîne avec les balises
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,stripslashes(txtentities($e)));
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract attributes
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $attr=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr){
        //Opening tag
        switch($tag){
            case 'STRONG':
                $this->SetStyle('B',true);
                break;
            case 'EM':
                $this->SetStyle('I',true);
                break;
            case 'B':
            case 'I':
            case 'U':
                $this->SetStyle($tag,true);
                break;
            case 'A':
                $this->HREF=$attr['HREF'];
                break;
            case 'IMG':
                if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
                    if(!isset($attr['WIDTH']))
                        $attr['WIDTH'] = 0;
                    if(!isset($attr['HEIGHT']))
                        $attr['HEIGHT'] = 0;
                    $this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
                }
                break;
            case 'TR':
            case 'BLOCKQUOTE':
            case 'BR':
                $this->Ln(5);
                break;
            case 'P':
                $this->Ln(10);
                break;
            case 'FONT':
                if (isset($attr['COLOR']) && $attr['COLOR']!='') {
                    $coul=hex2dec($attr['COLOR']);
                    $this->SetTextColor($coul['R'],$coul['V'],$coul['B']);
                    $this->issetcolor=true;
                }
                if (isset($attr['FACE']) && in_array(strtolower($attr['FACE']), $this->fontlist)) {
                    $this->SetFont(strtolower($attr['FACE']));
                    $this->issetfont=true;
                }
                break;
                }
    }

    function CloseTag($tag){
        //Closing tag
        if($tag=='STRONG')
            $tag='B';
        if($tag=='EM')
            $tag='I';
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='FONT'){
            if ($this->issetcolor==true) {
                $this->SetTextColor(0);
            }
            if ($this->issetfont) {
                $this->SetFont('arial');
                $this->issetfont=false;
            }
        }
    }

    function SetStyle($tag, $enable){
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s){
            if($this->$s>0)
                $style.=$s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt){
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
}