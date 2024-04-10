<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeDiplomeModel extends Model
{
    protected $table = 'type_diplome';
    protected $primaryKey = 'id_type_diplome';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['intitule_fr', 'intitule_en', 'institut_id'];
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
    // Fonction pour récupérer tous les types de diplômes
    public function getAll()
    {
        return $this->findAll();
    }

    // Fonction pour récupérer un type de diplôme par son ID
    public function getById($id)
    {
        return $this->where('id_type_diplome', $id)->first();
    }

    // Fonction pour créer un nouveau type de diplôme
    public function create($data)
    {
        return $this->insert($data);
    }

    // Fonction pour modifier un type de diplôme
    public function update($id, $data)
    {
        return $this->update($id, $data);
    }

    // Fonction pour supprimer un type de diplôme
    public function delete($id)
    {
        return $this->delete($id);
    }
}
