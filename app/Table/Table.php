<?php
namespace App\Table;

use App\App;

class Table {
    // Le nom de la table en base de données
    protected static $table;

    /**
     * Trouve un élément par son ID
     * @param int $id L'identifiant à chercher
     * @return mixed Un objet de la classe appelante
     */
    public static function find($id) {
        return App::getDb()->prepare(
            "SELECT * FROM ".static::$table." WHERE id=?", 
            [$id], 
            get_called_class(), 
            true
        );
    }

    /**
     * Devine le nom de la table si pas précisé
     * @return string Le nom de la table
     */
    public static function getTable() {
        if(static::$table === null) {
            // On récupère le nom de la classe (ex: "App\Table\Recette")
            $class_name = explode("\\", get_called_class());
            // On prend le dernier élément (ex: "Recette")
            static::$table = strtolower(end($class_name)); 
        }
        return static::$table;
    }

    /**
     * Fonction magique qui s'active quand on essaie d'accéder à une propriété invisible
     * @param string $key Le nom de la propriété demandée
     */
    public function __get($key) {
        // On crée le nom d'une méthode getter (ex: getTitre)
        $method = 'get' . ucfirst($key);
        
        // Si cette méthode existe
        if (method_exists($this, $method)) {
            // On l'appelle et on stocke le résultat
            $this->$key = $this->$method();
            return $this->$key;
        }
        
        // Sinon on crée une erreur
        throw new \Exception("La propriété $key n'existe pas.");
    }

    /**
     * Récupère tous les éléments de la table
     * @return array Un tableau d'objets
     */
    public static function all() {
        return App::getDb()->query(
            "SELECT * FROM ".static::getTable()."",
            get_called_class()
        );
    }
}