<?php 
namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Ingredient;

class IngredientModel extends Model
{
    protected $table = 'ingredient';
    protected $returnType = Ingredient::class;

    protected $allowedFields = [
        'nom',
        'quantite',
    ];
}