<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\DiplomeModel;
use Dompdf\Dompdf;
use Dompdf\Options;


class PdfController extends Controller
{

    public function index() 
	{
        // $diplomeModel = new DiplomeModel();
        // $diplomes = $diplomeModel->getDiplomesByInstitut(2); // Récupérer les 10 premiers diplômes
       // echo var_dump($diplomes[0]);
       $this->GenerateQRcode(1902 , 2430);
       // echo var_dump( $this->GenerateQRcode(1 , 2));
       return view('pdf_view');
        //  echo var_dump(  view('pdf_view',$diplomes[0]));
        // die;
    }

    public function htmlToPDF() {
    $diplomeModel = new DiplomeModel();
    $diplomes = $diplomeModel->getDiplomesRangeByInstitut(2,3);
   // echo var_dump($diplomes );
    // die;
    $pdfFolderPath = FCPATH . 'Vague1/'; // Le chemin du dossier où les fichiers PDF seront enregistrés

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($pdfFolderPath)) {
            mkdir($pdfFolderPath, 0777, true);
        }
        $tmp = sys_get_temp_dir();
         // Configuration de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('fontDir', $tmp);
        $options->set('fontCache', $tmp);
        $options->set('tempDir' , $tmp);
        $options->set('chroot' , $tmp);
        // $options->set('defaultFont', 'Courier');
        // Définir le chemin vers le répertoire des polices DomPDF
        // $options->set('fontDir', __DIR__  . '/../../vendor/dompdf/dompdf/lib/fonts/');
        // $options->set('defaultFont', 'calibri-regular.ttf');
       // $options->set('isCssEnabled', true);

//        //Juste pour debugger mon code
//        $fontDir = __DIR__  . '/../../vendor/dompdf/dompdf/lib/fonts/';
//       $defaultFont = 'calibri-regular.ttf';
//     if (!file_exists($fontDir . $defaultFont)) {
//     die($fontDir . $defaultFont.'Erreur : La police par défaut n\'a pas été trouvée.');
// }else{
//    die($fontDir . $defaultFont);
// }


    foreach ($diplomes as $diplome) {
      set_time_limit(0); // TEMPS D'EXECUTION SANS LIMITE
    $date_naiss_fr = mb_strtoupper ($this->ReplaceDate($diplome->date_naiss)) ;
   //  $date_naiss_en = strtotime($diplome->date_naiss);
   // $date_naiss_en = ucfirst($diplome->date_naiss) ;
   
    $lieu_naiss = mb_strtoupper ($diplome->lieu_naiss);
    $matricule = mb_strtoupper ($diplome->matricule);
    $mention = mb_strtoupper ($this->Mention($diplome->mention));
    $nom_prenom = mb_strtoupper ($diplome->nom_prenom);
    $type_diplome_fr = mb_strtoupper ($diplome->intitule_fr);
    $type_diplome_en =mb_strtoupper ( $diplome->intitule_en);
    $annee_obtention = mb_strtoupper ($diplome->annee_obtention);
    $filiere = mb_strtoupper ( $this->getAfterEs($type_diplome_fr));
    $specialite = mb_strtoupper ($diplome->specialite);
          //echo var_dump($diplome->date_naiss);
      // die;
   //$file_qr ='a';
  $file_qr = $this->convertToBase64(FCPATH .'uploads/' . $diplome->file_qr);
   // $file_qr = $this->convertToBase64(FCPATH .'/images/qrcod.png');
   $fond = $this->convertToBase64(FCPATH .'/images/fond_fsega.png');
   // echo var_dump($file_qr);
    //    die;
       
   $logo = $this->convertToBase64(FCPATH .'/images/logo_fsega.png');
    $cmr = $this->convertToBase64(FCPATH .'/images/Coat_of_arms_of_Cameroon.png');
   $minesup = $this->convertToBase64(FCPATH .'/images/Logo_minesup.png');
  //  $fond = 'a';
  //   $logo = 'a';
  //   $cmr = 'a';
  //   $minesup ='a';
// code pour gerer la taille de police de la date de naissance
$naiss = $date_naiss_fr.' A '.$lieu_naiss;
$nbCaracteres = mb_strlen($naiss);
echo $nbCaracteres;
$taillePolice = 0.4;
if ($nbCaracteres > 37 && $nbCaracteres < 48) {
  $taillePolice = 0.3;
} else if ($nbCaracteres > 47) {
  $taillePolice = 0.25;
}
//fin de code pour gerer la taille de police de la date de naissance

//fin specialite caracteres
$specialite1 = $specialite;
$nbCaracteres1 = mb_strlen($specialite1);
$taillePolice1 = 0.4;
if ($nbCaracteres1 > 47) {
  $taillePolice1 = 0.25;
} 
// fin specialite caracteres

//echo var_dump($nbCaracteres1);
  // die;
         // $dompdf = new PDF();
      // $dompdf->setOptions($options);
      $dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diploma</title>
    <style>
    @font-face {
    font-family: \'Calibri\';
    src: url(  '.__DIR__ .' \'/../../vendor/dompdf/dompdf/lib/fonts/\') format(\'truetype\');
}
</style>
</head>
  <body>
  <div style="line-height: 1em;" >
    <img class="centre fondfsega" src="data:image/jpeg;base64,'.$fond.'" alt="Fond FSEG" style="  width: 27cm;
        height: 18.3cm;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
      ">
  </div>
  <div class="centre bord1" style=" width: 27cm;
        height: 18.3cm;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
         border: 2px solid blue; padding: 2px; ">
    <div class="centre bord2" style="width: 26.7cm;
        height: 17.9cm; border: 5px solid blue; padding: 2px; ">
      <div class="container centre bord3" style="width: 26.3cm;
        height: 17.6cm;
         position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);">
        <table style="width: 100%;">
          <tr style=" font-size: 0.3cm;">
            <td>
              <img height="75"  src="data:image/jpeg;base64,'.$logo.'">
            </td>
            <td style="width: 40%;font-weight: 900;">
              <center>
                <div style="line-height: 1em;" > RÉPUBLIQUE DU CAMEROUN </div>
                <div style="line-height: 1em;" > Paix - Travail - Patrie </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > MINISTÈRE DE L\'ENSEIGNEMENT SUPÉRIEUR </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > UNIVERSITÉ DE DOUALA </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > FACULTE DES SCIENCES ÉCONOMIQUES </div>
                <div style="line-height: 1em;" > ET DE GESTION APPLIQUÉE </div>
              </center>
            </td>
            <td>
              <center>
                <img height="75"  src="data:image/jpeg;base64,'.$cmr.'">
              </center>
            </td>
            <td style="width: 40%;font-weight: 900;">
              <center>
                <div style="line-height: 1em;" > REPUBLIC OF CAMEROON </div>
                <div style="line-height: 1em;" > Peace - Work - Fatherland </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > MINISTRY OF HIGHER EDUCATION </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > UNIVERSITY OF DOUALA </div>
                <div style="line-height: 1em;" > ********************* </div>
                <div style="line-height: 1em;" > FACULTY OF ECONOMICS </div>
                <div style="line-height: 1em;" > AND APPLIED MANAGEMENT </div>
              </center>
            </td>
            <td>
              <img height="75" src="data:image/jpeg;base64,'.$minesup.'">
            </td>
          </tr>
        </table>
        <br>
        <table style="width: 100%; ">
          <tr>
            <td></td>
            <td>
              <center>
                <div style=" font-weight: 900; color: blue; font-size: 0.8cm; text-transform: uppercase;"> DIPLÔME DE LICENCE / BACHELOR\'S DEGREE CERTIFICATE </div>
              </center>
            </td>
            <td></td>
          </tr>
        </table>
        <table style="width: 100%; ">
          <tr>
            <td></td>
            <td style="font-family: \'Calibri\'; font-weight: 900;  width: 50%; text-align: center; text-justify: inter-word; font-size: 0.3528cm;">
              <div style="font-family: \'Calibri\'; line-height: 1em;" > N°________________/MINESUP/DCAA/UDo/FSEGA/D/VDSSE/DAASR/CSDPER </div>
              <br>
            </td>
          </tr>
        </table>
        <table style="width: 100%; border-spacing: 0;font-family: \'Calibri\';  ">
          <tr style="">
            <td style="width: 80%;  vertical-align: top;">
              <div style="line-height: 1em;font-weight: 900;  font-size: 0.3528cm;"> LE MINISTRE D\'ÉTAT, MINISTRE DE L\'ENSEIGNEMENT SUPÉRIEUR, CHANCELIER DES ORDRES ACADÉMIQUES </div>
              <div style="line-height: 1em;font-weight: bold;  font-size: 0.3528cm; font-style: italic;"> THE MINISTER OF STATE, MINISTER OF HIGHER EDUCATION, CHANCELLOR OF ACADEMIC ORDERS </div>
              <div style="line-height: 1em;font-weight: 600; font-size: 0.3175cm;"> Vu le décret n°93/036 du 19 janvier 1993 portant organisation administrative de l\'Université de Douala, </div>
              <div style="line-height: 1em;font-weight: 600;   font-size: 0.3175cm; font-style: italic;"> Mindful of decree N° 93/036 of January, 1993 to organize the administrative and academic structure of the University of Douala </div>
              <div style="line-height: 1em;font-weight: 600;   font-size: 0.3175cm;"> Vu les textes en vigueur portant organisation des enseignements et des évaluations à la Faculté des Sciences Économiques et de Gestion Appliquée </div>
              <div style="line-height: 1em;font-weight: 600;   font-size: 0.3175cm; font-style: italic;"> Mindful of the regulations organising the courses and examinations at the Faculty of Economics and Applied Management </div>
            </td>
            <td style="font-weight: 900; text-align: center; text-justify: inter-word; font-size: 0.3528cm;">
              <div style="line-height: 1em;" >
                <img width="100" height="100" src="data:image/jpeg;base64,'.$file_qr.'">
              </div>
              <div style="line-height: 1em;" > N° Matricule : <span style=" color: blue; font-size: 0.3528cm;">'.$matricule.'</span>
              </div>
              <div style="line-height: 1em; text-align: center; font-weight: 600; font-style: italic;">Matriculation N° :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   </div>
            </td>
          </tr>
        </table>
        <table style="width: 100%; ">
          <tr style=" font-weight: 900; text-justify: inter-word; font-size: 0.3528cm;">
            <td style="width:60%; padding: 0; margin:0;">
              <div style="line-height: 1em;margin: 0;"> Délivre à M./Mme./Mlle.&nbsp;&nbsp;&nbsp; <span style=" color: blue; font-size: 0.4586cm;">'.$nom_prenom.'</span></div>
              <div style="line-height: 1em; font-weight: 600; margin: 0;font-size: 0.3175cm; font-style: italic;"> issued to Mr/Mrs./Ms. </div>
            </td>
            <td style="padding: 0; margin:0;">
              <div style="line-height: 1em;" font-size: 0.30cm; > Né(e) le :<span style=" color: blue; font-size: '.$taillePolice.'cm;"> '.$naiss.'</span></div>
              <div style="line-height: 1em;font-weight: 600;  font-size: 0.3175cm;  font-style: italic;"> Born on : <span> </span>
              </div>
            </td>
          </tr>
        </table>
          <br>
        <table style=" width: 100%; serif; font-weight: 900; color: blue; font-size: 0.7cm; text-transform: uppercase;">
          <tr>
            <td>
              <center>
                <div style="line-height: 1em;" >
                  <span> '.$type_diplome_fr.' </span>
                </div>
                <div style="line-height: 1em;font-weight: 600;  font-size: 0.6cm ;font-style: italic;">
                  <span style="   color: blue; "> '.$type_diplome_en.' </span>
                </div>
              </center>
            </td>
          </tr>
        </table>
        <table style="width: 100%;">
          <tr style="font-weight: 900;   text-justify: inter-word; font-size: 0.3528cm;">
            <td style="width: 35%; vertical-align: bottom;">
              <div style="line-height: 1em;" > Filière : <span style="color: blue; font-size:0.4cm" ;>'.$filiere.'</span>
              </div>
              <div style="font-weight: 600; font-size: 0.3175cm; font-style: italic;"> Field of studies </div>
            </td>
            <td style="width: 45%; vertical-align: bottom;">
              <div style="line-height: 1em;" > Spécialité : <span style=" color: blue; font-size:'.$taillePolice1.'cm;">'.$specialite.'</span></div>
              <div style="font-weight: 600;  font-size: 0.3175cm; font-style: italic;"> Speciality </div>
            </td>
            <td style="width: 20%; vertical-align: bottom;">
              <div style="font-size:0.3528cm;"> Mention : <span style=" color: blue; font-size:0.4cm">'.$mention.'</span></div>
              <div style=" font-weight: 600; font-style: italic; font-size: 0.3175cm;"> Grade : </div>
            </td>
          </tr>
        </table>
        <table style="width: 100%;">
          <tr style="font-weight: 900; text-justify: inter-word; font-size: 0.3528cm;">
            <td style="width: 50%; ">
              <div style="line-height: 1em;" >
                <div style="line-height: 1em;font-size: 0.3175cm; font-style: italic;"> Procès-verbal des déliberations du jury en session de : <span style=" color: blue; font-size:0.4cm"; font-weight: 900;>'.$annee_obtention.'</span></div>
                <div style="line-height: 1em;font-weight: 600;  font-size: 0.3175cm; font-style: italic;"> Official report of the deliberation of the session of </div>
              </div>
            </td>
          </tr>
        </table>
        <table style="width: 100%;">
          <tr>
            <td style="font-weight: 600;  font-size: 0.3528cm;">
              <span> Pour en jouir avec les droits et prérogatives qui y sont rattachés </span>
              <div style="line-height: 1em;font-weight: 600;  font-style: italic; font-size: 0.3175cm;"> With all the rights and privileges appartaining thereto </div>
            </td>
            <td style="width: 50%; ">
              <center>
                <div style="line-height: 1em;font-weight: 900;font-size: 0.358cm;" >Fait à Yaoundé, le : </div>
                <div style="line-height: 1em;font-weight: 600;  font-style: italic; font-size: 0.3175cm;"> Done at Yaoundé, on the </div>
              </center>
            </td>
          </tr>
        </table>
        <table style="width: 100%; ">
          <tr>
            <td style="font-weight: 900; width: 40%; font-size: 0.3528cm;  vertical-align: top;">
              <center>
                <div style="line-height: 1em;font-weight: bold;"> Le Recteur </div>
                <div style="line-height: 1em;font-weight: 600;font-style: italic;"> The Rector </div>
              </center>
            </td>
            <td style="width: 60%; vertical-align: top;">
              <center>
                <div style="line-height: 1em;font-weight: 900; font-size: 0.3528cm ; "> Le Ministre d\'État, Ministre de l\'Enseignement Supérieur, <div style="line-height: 1em;" > Chancelier des Ordres Académiques </div>
                </div>
                <div style="line-height: 1em;font-weight: 600;  font-size: 0.3528cm ;  font-style: italic;"> The Minister of State, Minister of Higher Education, <div style="line-height: 1em;" > Chancellor of Academic Orders </div>
                </div>
              </center>
            </td>
          </tr>
        </table>
        <table style="width: 100%;">
          <tr>
            <td style=" vertical-align: bottom;">
              <br>
              <br>
              <br>
              <center>
                <div style="line-height: 1em;font-size: 0.19cm;"> IL N\'EST DÉLIVRÉ QU\'UN SEUL DIPLÔME. L\'INTÉRESSÉ PEUT EN FAIRE ÉTABLIR AUTANT DE COPIES CERTIFIÉES CONFORMES QU\'IL DÉSIRE. <div style="font-style: italic;"> ONLY ONE COPY OF THIS CERTIFICATE CAN BE ISSUED. CERTIFIED COPIES OF IT CAN BE MADE WHENEVER NECESSARY. </div>
                </div>
              </center>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</body>
</html>';
   //  $html = view('pdf_view') ;
   //echo var_dump($html);
  // die;
  
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->loadHtml($html);
        $dompdf->render();
         // Enregistrer le fichier PDF
            $pdfFileName = 'diplome_' .$diplome->id_diplome. '.pdf';
            $pdfFilePath = $pdfFolderPath . $pdfFileName;
            file_put_contents($pdfFilePath, $dompdf->output());
    //   echo var_dump($html);
      // die;

    }
  // echo var_dump($html);
    //  die;
     return "Les fichiers PDF ont été générés avec succès.";

    }

  
 public function convertToBase64( $imagePath)
    {
        // Load the image_lib library
        $imageLib = \Config\Services::image();
        // Set the path to your image file

        // Check if the file exists
        if (!file_exists($imagePath)) {
            die('Image not found.');
        }

        // Read the image file
        $imageData = file_get_contents($imagePath);

        // Convert the image data to base64
        $base64Image = base64_encode($imageData);

        // Display the base64-encoded image data
        return $base64Image;
    }
    function convertSvgToPng($svgPath, $outputPath)
    {
        // Check if the SVG file exists
        if (!file_exists($svgPath)) {
            die('SVG file not found.');
        }
    
        // Build the command to convert SVG to PNG using Inkscape
        $command = "inkscape --export-png=$outputPath --export-type=png $svgPath";
    
        // Execute the command
        exec($command, $output, $returnCode);
    
        // Check if the conversion was successful
        if ($returnCode !== 0) {
            die('Error converting SVG to PNG.');
        }
    
        echo 'Conversion successful!';
    }

    function Mention($mention){
      switch ($mention) {
        case 'TB':
           $fr = 'Très bien';
           $en = 'Very good';
           break;
       case 'B':
           $fr = 'Bien';
           $en = 'Good';
           break;
       case 'AB':
           $fr = 'Assez bien';
           $en = 'Fairly good';
           break;
       case 'E':
           $fr = 'Excellent';
           $en = 'Excellent';
           break;
       case 'P':
           $fr = 'Passable';
           $en = 'Passable';
           break;
            case 'TE':
           $fr = 'Très Excellent';
           $en = 'Very Excellent';
           break;
            case 'H':
           $fr = 'Honorable';
           $en = 'Honorable';
           break;
            case 'TH':
           $fr = 'Très Honorable';
           $en = 'Very Honorable ';
           break;
            case 'THF':
           $fr = 'Très Honorable avec Félicitations du Jury';
           $en = 'Very Honorable with Appreciations of the Jury';
           break;
       default:
           $fr = '';
           $en = '';
           break;
       }

       return $fr;
    }

    function ReplaceDate($chaine) {
      // Expression régulière pour rechercher les dates au format jj/mm/aaaa ou aaaa-mm-jj
      $pattern = '/(\d{2}\/\d{2}\/\d{4}|\d{4}-\d{2}-\d{2})/';
      
      // Remplacement des dates par le format demandé
      $chaineModifiee = preg_replace_callback($pattern, function($matches) {
        
          // Créer un objet DateTime à partir de la chaîne de date
          $date = \DateTime::createFromFormat('d/m/Y', $matches[0]);
  
          // Si la création avec le format 'd/m/Y' échoue, essayer avec 'Y-m-d'
          if (!$date) {
              $date = \DateTime::createFromFormat('Y-m-d', $matches[0]);
          }
  
          // Si la création de l'objet DateTime réussit
          if ($date) {
            // $france_timezone = new \DateTimeZone("Europe/Paris");
            //   $date = $date->setTimezone($france_timezone);
            //   $locale =  \Locale::setDefault('fr-FR');
            //   echo var_dump( $locale);
              // Formater la date selon le modèle souhaité
              //\Locale::setDefault('fr_FR.utf8');
              // Set the locale to French
             

             // setlocale(LC_TIME, ['fr.utf8', 'fra.utf8', 'fr_FR.utf8']);
              // $nomDuMois = strftime('%B', $date->getTimestamp());
              //echo var_dump( $nomDuMois);
             // $formattedDate = $date->format('d F Y');
             
            //  $formattedDate = strftime('%d %B %Y', $date->getTimestamp());

            $locale = 'fr_FR.utf8';
            $dateFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::LONG, \IntlDateFormatter::NONE);
            $dateFormatter->setPattern('dd MMMM y');
            $formattedDate = $dateFormatter->format($date);

              return ucwords($formattedDate);
          }
  
          // Retourne la date telle quelle si le format n'est pas reconnu
          return $matches[0];
  
      }, $chaine);
  
      return $chaineModifiee;
  }


// Recuperer la filiere
public function getAfterEs($inputString)
    {
        // Recherche de la position de "ÈS" dans la chaîne
        $position = strpos($inputString, 'ÈS');

        // Si "ÈS" est trouvé, retourne la sous-chaîne après "ÈS"
        if ($position !== false) {
            $result = trim(substr($inputString, $position + 4));
            return $result;
        }

        // Si "ÈS" n'est pas trouvé, retourne la chaîne d'origine
        return $inputString;
    }

  function GenerateQRcode($firstId , $lastId){
    set_time_limit(0); // TEMPS D'EXECUTION SANS LIMITE
    $diplomeModel = new DiplomeModel();
     for ($i=$firstId; $i <= $lastId; $i++) { 
      $diplomeModel->generate_qrcode($i);
     }
  }

  
  

}