<?php 
// On crée une librairie pour simplifier l'obtentiion des recettes et de leurs ingrédients

/*
Pour utiliser la pagination, le contrôleur a besoin d'accéder au modèle car on doit tuiliser l'instance du "Pager" qu'il a utilisé 
pour préparer la pagination. On modifie la librairie "MesRecettes" pour rendre les instances des modèles accessibles via des variables 
publiques.
*/
namespace App\Libraries;

use App\Models\RecetteModel;
use App\Models\IngredientModel;

class MesRecettes
{
    public $recetteModel;
    public $ingredientModel;
    private $erreurs;

    public function __construct()
    {
        $this->recetteModel = new RecetteModel();
        $this->ingredientModel = new IngredientModel();
        $this->erreurs = [];
    }

    // Définir les messages d'erreurs
    private function setErreurs($erreurs)
    {
        // Si on reçoit autre chose qu'un tableau, on le convertit en tableau 
        $this->erreurs = is_array($erreurs) ? $erreurs : (array) $erreurs;
    }

    // Retourner les messages d'erreurs 
    public function getErreurs(): array 
    {
        return $this->erreurs;
    }

    // Obtenir la liste de recettes   
    public function getListeRecettes ()
    {
        // Obtenir seulement les champs id, slug et titre
        $this->recetteModel->select('id, slug, titre');

        // Si on recherche par texte, chercher dans le titre et les instructions
        if ( ! empty($rech['texte']))
        {
            $this->recetteModel
                ->like('titre', $rech['texte'])
                ->orLike('instructions', $rech['texte']);
        }

        // Si on ne demande pas un nombre de page spécifique, prendre la valeur par défaut
        $nb_par_page = ! empty($rech['nb_par_page']) ? $rech['nb_par_page'] : null;

        // Ajouter le tri et la pagination, puis retourner les résultats
        $recettes = $this->recetteModel
            ->orderBy('id')
            // Remplace "findAll()" par la fonction paginate de la classe "Model", qui va lire 
            // la variable du numéro de page passée dans l'URL, configurer la classe "Pager" et 
            // va ajouter la clause "LIMIT" à la requête SQL, pour ensuite retourner le résultat 
            // final obtenu par "findAll()".
            ->paginate();

        return $recettes;
    }

    // Obtenir une recette par son id  
    public function getRecetteParId (int $id)
    {
        // Obtenir la recette par son id
        $recette = $this->recetteModel->find($id);

        if ($recette !== null)
        {
            $recette->ingredients = $this->ingredientModel
                ->where( ['id_recette' => $recette->id] )
                ->orderBy('id')
                ->findAll();
        }

        return $recette;
    }

    // Obtenir une recette par son 'slug'     
    public function getRecetteParSlug (string $slug)
    {
        // Obtenir la recette par son slug
        $recette = $this->recetteModel->where('slug', $slug)->first();

        if ($recette !== null)
        {
            $recette->ingredients = $this->ingredientModel
                ->where( ['id_recette' => $recette->id] )
                ->orderBy('id')
                ->findAll();
        }

        return $recette;
    }

    // Supprimer une recette et ses ingrédients
    public function supprimerRecette (int $id): bool    
    {
        // D'abord supprimer les ingrédients de la recette
        if ( ! $this->ingredientModel
                ->where( ['id_recette' => $id] )
                ->delete() )
        {
            $this->setErreurs($this->ingredientModel->errors());
            return false;
        }

        // Supprimer la recette 
        
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////

/*
    public function getToutesLesRecettes()
    {
        // Créer une instance des 2 modèles 
        $recetteModel = new RecetteModel();
        $ingredientModel = new IngredientModel();

        // Sélectionner les recettes triées par "id"
        $recettes = $recetteModel
            ->orderBy('id')
            ->findAll();

        // Pour chaque recette, sélectionner ses ingrédients 
        foreach($recettes as $recette)
        {
            $recette->ingredients = $ingredientModel    
                ->where(['id_recette' => $recette->id])
                ->orderBy('id')
                ->findAll();
        }

        unset($recette);

        return $recettes;
    }
    

    //  Obtenir la liste des recettes 
    public function getListeRecettes()
    {
        // $recetteModel = new RecetteModel();

        // Obtenir seulement le champ id, slug et titre 
        $recettes = $recetteModel 
            ->select('id, slug, titre')
            ->orderBy('id')
            ->findAll();

        return $recettes;
    }

    public function getRecetteParId (int $id)
    {
        $recetteModel = new RecetteModel();
        $ingredientModel = new IngredientModel();

        // Obtenir la recette par son id
        $recette = $recetteModel->find($id);

        if ($recette !== null)
        {
            $recette->ingredients = $ingredientModel
                ->where( ['id_recette' => $recette->id] )
                ->orderBy('id')
                ->findAll();
        }

        return $recette;
    }

    public function getRecetteParSlug (string $slug)
    {
        $recetteModel = new RecetteModel();
        $ingredientModel = new IngredientModel();

        // Obtenir la recette par son slug
        $recette = $recetteModel->where('slug', $slug)->first();

        if ($recette !== null)
        {
            $recette->ingredients = $ingredientModel
            ->where( ['id_recette' => $recette->id] )
            ->orderBy('id')
            ->findAll();
        }

        return $recette;
    }
   */