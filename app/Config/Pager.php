<?php
/*
Cette classe est une classe système de CodeIgniter qui permet de faire la pagination et entre autre de générer les liens vers les 
autres pages. Elle a besoin d'un fichier de configuration pour définir les templates disponibles et le nombre d'items par défaut à afficher 
par page. On ajoute un nouveau template qu'on va utilsier à la place des templates par défaut de CodeIgniter. Les templates par défaut sont très 
basiques et au lieu d'écrire du CSS, on va utiliser les classes CSS de Bootstrap.
*/
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Pager extends BaseConfig
{
    // Alias des templates pour la pagination
    public array $templates = [
        // Templates par défaut fournit avec CodeIgniter
        'default_full'   => 'CodeIgniter\Pager\Views\default_full',
        'default_simple' => 'CodeIgniter\Pager\Views\default_simple',
        // Template personnalisé pour utiliser Bootstrap
        'bootstrap'   => 'App\Views\pagination_bootstrap',
    ];

    /**
     * --------------------------------------------------------------------------
     * Items Per Page
     * --------------------------------------------------------------------------
     *
     * The default number of results shown in a single page.
     */
    public int $perPage = 25;
}
