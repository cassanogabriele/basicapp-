<?php 
namespace App\Controllers;

use App\Libraries\MesRecettes;

class RecettesController extends BaseController
{
    // Liste des recettes
    public function index()
    {
        // Si un formulaire de recherche a été soumis 
        if ($this->request->getMethod() === 'post')
        {
            // Obtenir les critères de recherche du formulaire 
            $rech = [
                'texte' => $this->request->getPost('rech_texte'),
                'nb_par_page' => $this->request->getPost('rech_nb_par_page'),
            ];
        }
        // Sinon, si des critères de recherche ont été sauvegardés dans la session
        else if (session('rech_recette') !== null)
        {
            // Obtenir les critères de recherche de la session
            $rech = session('rech_recette');
        }        
        else
        {
            // Critères de recherche par défaut
            $rech = [
                'texte' => null,
                'nb_par_page' => null,
            ];
        }      

        if ($rech['nb_par_page'] !== null)
        {
            // Convertir la valeur en 'int' (nombre entier)
            $rech['nb_par_page'] = (int)$rech['nb_par_page'];

            // Si négatif ou 0, mettre null pour prendre la valeur définit dans
            // la configuration du "Pager"
            if ($rech['nb_par_page'] <= 0)
            {
                $rech['nb_par_page'] = null;
            }

            // Maximum de 100 recettes par page
            if ($rech['nb_par_page'] > 100)
            {
                $rech['nb_par_page'] = 100;
            }
        }

        // Sauver les critères de recherche dans la session
        session()->set('rech_recette', $rech);

        // Créer une instance de notre librairie
        $mesRecettes = new MesRecettes();

        // Rassembler toutes les données utilisées par la vue dans un tableau $data
        $data = [
            'titre_page' => "Mes recettes",
            'sous_titre_page' => "Je vous présente mes recettes favorites...",
            'recettes' => $mesRecettes->getListeRecettes(),
            // Passer les critères de recherche à la vue 
            'rech' => $rech,
            // Passer l'instance de la classe de pagination à la vue
            'pager' => $mesRecettes->recetteModel->pager,
        ];

        // Charger les fonctions utilitaires pour les formulaires 
        helper('form');

        /*
        Chacun des items du tableau $data sera accessible dans la vue
        par des variables portant le même nom que la clé :
        $titre_page, $sous_titre_page, $recettes, $rech et $pager
        */
        return view('liste_recettes', $data);
    }

    // Récupérer une recette par id
    public function recetteParId (int $id)
    {
        // Créer une instance de notre librairie
        $mesRecettes = new MesRecettes();

        $data = [];

        /*
        Obtenir la recette pour l'id reçu en paramètre.
        Si la recette n'existe pas, on emet une exception de page non trouvée (erreur 404)
        */
        if ( ! $data['recette'] = $mesRecettes->getRecetteParId($id))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('recette', $data);
    }

    // Récupérer une recette par slug
    public function recetteParSlug (string $slug)
    {
        // Créer une instance de notre librairie
        $mesRecettes = new MesRecettes();

        $data = [];

        /*
        Obtenir la recette pour l'id reçu en paramètre. Si la recette n'existe pas, on emet une
        exception de page non trouvée (erreur 404).
         */
        if ( ! $data['recette'] = $mesRecettes->getRecetteParSlug($slug))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('recette', $data);
    }

    public function creer()
    {
        // Charger les fonctions utilitaires pour les formulaires 
        helper('form');

        // Charger la configuration 
        $config = config('recette');

        $data = [
            'titre_page' => "Nouvelle recette",
            'max_ingredient' => $config->nb_ingredient,
        ];

        return view('form_recette', $data);
    }

    public function modifier(int $id)
    {
        // Créer une instance de la librairie
        $mesRecettes = new MesRecettes();

        // Charger les fonctions utilitaires pour les formulaires 
        $config = config('Recette');

        $data = [
            'titre_page' => "Modifier une recette",
            'max_ingredient' => $config->nb_ingredient,
        ];

        /*
        Obtenir la recette pour l'id reçu en paramètre. Si la recette n'existe pas, on emet une exception 
        de page non trouvée (erreur 404)
        */
        if(! $data['recette'] = $mesRecettes->getRecetteParId($id))
        {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('form_recette', $data);        
    }

    public function supprimer(int $id)
    {
        log_message('debt', "Supprimer recette id $id");

        // Créer une instance de la librairie
        $mesRecettes = new MesRecettes();

        if ($mesRecettes->supprimerRecette($id))
        {
            return redirect()->to('/')->with('message', "La recette a été supprimée avec succès.");
        }
        else
        {
            return redirect()->to('/')->with('erreurs', $mesRecettes->getErreurs());
        }
    }

    public function sauvegarder(int $id)
    {
       log_message('debug', ($id === null) ? "Sauvegarder nouvelle recette" : "Sauvegarder recette id $id");
    
        // Charger la configuration de l'application 
        $config = config('Recette');

        // Définir les règles de validation du formulaire 
        $regles = [
            'titre' => [
                'label' => "Titre",
                'rules' => "required|max_length[100]|is_unique[recette.titre,id,{$id}]"
            ],
            'instructions' => [
                'label' => "Instructions",
                'rules' => "required|string"
            ],
        ];

        for($i = 0; $i < $config->nb_ingredient;$i++)
        {
            $no_ingredient = $i + 1;

            
            $regles["quantite_ingredient_{$i}"] = [
                'label' => "Quantité de l'ingrédient {$no_ingredient}",
                'rules' => "permit_empty|string|max_length[10]|required_with[nom_ingredient_{$i}]"
            ];

            $regles["nom_ingredient_{$i}"] = [
                'label' => "Nom de l'ingrédient {$no_ingredient}",
                'rules' => "permit_empty|string|max_length[50]|required_with[quantite_ingredient_{$i}]"
            ];
        }

        // Valider les données du formulaire 
        if ( ! $this->validate($regles))
        {
            return redirect()->back()->withInput()->with('erreurs', $this->validator->getErrors());
        }

        // Créer une instance de la librairie
        $mesRecettes = new MesRecettes();

        // Obtenir les données du formulaire 
        $form_data_ingredients = [];

        
        for ($i = 0; $i < $config->nb_ingredient; $i++)
        {
            if ( ! empty($this->request->getPost("quantite_ingredient_{$i}")) &&
                ! empty($this->request->getPost("nom_ingredient_{$i}")))
            {
                $form_data_ingredients[] = [
                    'quantite' => $this->request->getPost("quantite_ingredient_{$i}"),
                    'nom' => $this->request->getPost("nom_ingredient_{$i}"),
                ];
            }
        }

        // Obtenir les données du formulaire et les sauvegarder
        if ($mesRecettes->sauvegarderRecette($id, $form_data_recette, $form_data_ingredients))
        {
            return redirect()->to('/')->with('message', "Recette sauvegardée avec succès");
        }
        else
        {
            return redirect()->back()->withInput()->with('erreurs', $mesRecettes->getErreurs());
        }
    }


    /*
    Données bidon en attendant de créer le modèle et la base de données

    public function _donnees_bidon()
    {
        return [
            [
                'titre' => "Eau bouillante",
                'ingredients' => ["Eau fraîche"],
                'instructions' => "Mettre l'eau dans un chaudron et faire bouillir."
            ],
            [
                'titre' => "Thé",
                'ingredients' => ["Eau fraîche", "Poche de thé"],
                'instructions' => "Préparez la recette d'eau bouillante. Mettre l'eau dans une tasse, ajoutez la poche de thé et laissez infuser quelques minutes."
            ],
            [
                'titre' => "Verre d'eau",
                'ingredients' => ["Eau fraîche", "Glaçons", "Tranche de citron (facultatif)"],
                'instructions' => "Mettre des glaçons dans un grand verre et remplir d'eau. Ajoutez une tranche de citron si désiré."
            ],
        ];
    }*/
}

