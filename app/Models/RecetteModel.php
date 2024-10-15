<?php 
/*
La classe Model de CodeIgniter 4 offre beaucoup de fonctionnalités. Il suffit de définir le nom de la table, le type d'objet à retourner, et la liste 
des champs modifiables. On permet de modifier tous les champs, sauf l'id. 
*/
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Recette;

class RecetteModel extends Model
{
    // Le nom de la table MySQL
    protected $table = 'recette';

    // Le type d'objet à retourner
    protected $returnType = Recette::class;

    // Les champs modifiables
    protected $allowedFields = [
        'titre',
        'instructions',
        'slug',
    ];
}