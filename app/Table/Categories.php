<?php
namespace App\Table;

/**
 * Classe pour gérer les catégories de recettes
 */
class Categories extends Table
{
    protected static $table = "categories";
   
    /**
     * Génère l'URL d'accès à la catégorie
     * @return string URL complète de la catégorie
     */
    public function getUrl()
    {
        return "index.php?p=categorie&id=" . (int)$this->id;
    }
    
    /**
     * Récupère toutes les catégories triées par nom
     * @return array Liste des catégories
     */
    public function getAll()
    {
        return App\App::getDb()->query("
            SELECT * 
            FROM {$this->table} 
            ORDER BY title ASC",
            __CLASS__);
    }
}