<?php
/**
 * @var string $titre_page                       Le titre de la page (créée automatiquement par CI via le tableau $data)
 * @var string $sous_titre_page                  Le sous-titre de la page (créée automatiquement par CI via le tableau $data)
 * @var array $recettes                          Liste des recettes (créée automatiquement par CI via le tableau $data)
 * @var App\Entities\Recette $recette            Une recette (créée par l'instruction foreach)
 * @var \CodeIgniter\Pager\PagerRenderer $pager  Instance de la classe de pagination
 */
?>
<!DOCTYPE html>
    <html lang="fr">
        <head>
            <title><?= esc($titre_page) ?></title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet"
                href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
                integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
                crossorigin="anonymous">

            <style type="text/css">
            .titre
            {
                padding: 3rem 1.5rem;
            }

            article
            {
                padding: 1.5rem 1.5rem;
            }
            </style>
        </head>

        <body>
            <main role="main" class="container">
                <div class="titre">
                    <h1>
                        <?= esc($titre_page) ?>
                        <small class="text-muted"><?= esc($sous_titre_page) ?></small>
                    </h1>
                </div>

                <div class="container">
                    <?php if(session('erreurs') != null) :?>
                        <div class="alert alert-danger">
                            <? implode('<br>', session('erreurs')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if(session('message') !=null):?>
                        <div class="alert alert-success text-center">
                            <?= session('message') ?>
                        </div>
                    <?php endif; ?>
                
                    <h3>Liste des recette</h3>

                    <div class="my-3">
                        <!--
                        Les fonctions "form_open()" et "form_close()" sont utilisée pour créer les tags HTML "<form>" et "</form>". Le premier paramètre est l'URI vers lequel 
                        soumettre le formulaire. Le deuxième paramètre est pour les attributs HTML du formulaire.
                        -->
                        <?= 
                        // Ouvrir le formulaire 
                        form_open('/', ['class' => 'form-inline']) 
                        ?>
                            <?= form_input('rech_texte',
                                        $rech['texte'] ?? '',
                                        ['class' => 'form-control my-1 mr-3', 'placeholder' => "Texte"]) ?>

                            <?= form_label("Nombre par page", 'rech_nb_par_page', ['class' => 'my-1 mr-2']) ?>

                            <?= form_input('rech_nb_par_page',
                                        $rech['nb_par_page'] ?? '',
                                        ['id' => 'rech_nb_par_page', 'class' => 'form-control my-1 mr-3', 'style' => 'width:70px']) ?>

                            <?= form_submit('rech_submit',
                                            "Recherche",
                                            ['class' => 'btn btn-outline-primary my-1 mr-3']) ?>
                        
                            <?= anchor('/creer',
                                            "Nouvellle recette",
                                            ['class' => 'btn btn-outline-succes my-1 mr-3']) ?>
                        <?= form_close() ?>
                    </div>
                    
                    <ul>
                        <?php foreach ($recettes as $recette): ?>
                                <li><?= anchor('recette/' . $recette->id, $recette->titre) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <?= $pager->links('default', 'bootstrap') ?>
                </div>
            </main>

            <footer>
                <p class="text-center">&copy; 2020 Mon site de recettes</p>
            </footer>
        </body>
    </html>