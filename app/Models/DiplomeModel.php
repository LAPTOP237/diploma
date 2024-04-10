<?php

namespace App\Models;

use CodeIgniter\Model;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;

class DiplomeModel extends Model
{
    protected $table = 'diplomes';
    protected $primaryKey = 'id_diplome';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['nom_prenom', 'statut', 'matricule', 'date_naiss', 'lieu_naiss', 'annee_obtention', 'mention', 'type_diplome_id', 'file_qr', 'sess_admission', 'sess_deliberation', 'specialite'];
    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    // Fonction pour récupérer tous les diplômes
    public function getAll()
    {
        return $this->findAll();
    }

    // Fonction pour récupérer un diplôme par son ID
    public function getById($id)
    {
        return $this->where('id_diplome', $id)->first();
    }

    // Fonction pour créer un nouveau diplôme
    public function create($data)
    {
        return $this->insert($data);
    }

    // // Fonction pour modifier un diplôme
    // public function update($id, $data)
    // {
    //     return $this->update($id, $data);
    // }

    // Fonction pour supprimer un diplôme
    // public function delete($id)
    // {
    //     return $this->delete($id);
    // }

    // Fonction pour rechercher des diplômes par nom
    public function search($nom)
    {
        return $this->like('nom_prenom', $nom)->findAll();
    }

    // Fonction pour récupérer les diplômes d'un type spécifique
    public function getByDiplomeType($typeDiplomeId)
    {
        return $this->where('type_diplome_id', $typeDiplomeId)->findAll();
    }

    // Fonction qui recupere les diplomes par institut
    public function getDiplomesRangeByInstitut($institutId,$type)
    //public function getDiplomesRangeByInstitut($institutId,$deb,$fin)
{
    //set_time_limit(0); // TEMPS.* D'EXECUTION SANS LIMITE
    return $this->select('*')
                ->join('type_diplome', 'type_diplome.id_type_diplome = diplomes.type_diplome')
                ->where('type_diplome.institut_id', $institutId)
              //  ->where('id_diplome' . ' BETWEEN', [$deb, $fin])
                ->where('diplomes.type_diplome', $type)
                ->get()
              ->getResultObject();
                //->findAll();
}
    //generer le qrcode d'un diplome 
    public function generate_qrcode($idDiplome)
    {
        $diplome = $this->join('type_diplome', 'type_diplome.id_type_diplome = diplomes.type_diplome')
                        ->where('diplomes.id_diplome', $idDiplome)
                        ->first();
                       // echo var_dump( $diplome["intitule_fr"]);
    // die;

        if (!$diplome) {
            echo "Diplome non trouvé";
        }

        // Encodage des informations du diplôme
        $informationsDiplome = [
            'institut' => 'FACULTE DES SCIENCES ECONOMIQUES ET GESTION APPLIQUEES DE DOUALA - CAMEROUN ',
            'type_diplome' => $diplome["intitule_fr"],
            'nom_prenom' => $diplome["nom_prenom"],
            'matricule' => $diplome["matricule"],
            'date_naiss' =>$diplome["date_naiss"],
            'lieu_naiss' => $diplome["lieu_naiss"],
            'annee_obtention' => $diplome["annee_obtention"],
            'mention' => $diplome["mention"],
            'specialite' => $diplome["specialite"],
        ];

        $chaineEncodage = implode('|', $informationsDiplome);

        $writer = new PngWriter();

        // Creation du QR code
        $qrCode = QrCode::create($chaineEncodage)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
       
       

            $result = $writer->write($qrCode);

            // Validate the result
           // $writer->validateResult(false);
           // $writer->validateResult($result, $chaineEncodage);
        // Enregistrement de l'image du QR code
        $qrCodeFileName = 'qrcode' . $diplome["id_diplome"] . '.png';
        $result->saveToFile(FCPATH . 'uploads/' . $qrCodeFileName);

        // Mise à jour du nom du fichier QR code dans la base de données
        $this->update($diplome["id_diplome"], ['file_qr' => $qrCodeFileName]);

        echo $chaineEncodage;
    }
}
