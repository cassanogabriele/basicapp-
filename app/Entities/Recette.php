<?php 
/*
CodeIgniter 4 introduit un nouvea concept : les entités. On définit une entité par table et au lieu de retourner un objet générique, le modèle retournera une instance de 
cette classe. La classe "Entity" offre des fonctionnalités intéressantes. 
On va seulement ajouter une variable qui contiendra la liste d'ingrédients de chaque recette
CodeIgniter va ajouter automatiquement les variable id, titre et instructions de la table "recette", on ajoute un tableau "$ingrdients" pour la liste 
d'ingrédients de cette recette.
*/
namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Recette extends Entity
{
    public $ingredients;

    public function __construct (array $data = null)
    {
        parent::__construct($data);

        // Initialiser la liste d'ingrédients avec un tableau vide.
        $this->ingredients = [];
    }
}