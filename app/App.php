<?php
namespace App;

class App {
    // Constantes = valeurs fixes qu'on ne peut pas modifier
    const DB_NAME = "partage_de_recettes";  // Nom de la base de données
    const DB_USER = "root";                 // Nom d'utilisateur MySQL
    const DB_PASS = "";                     // Mot de passe MySQL
    const DB_HOST = "localhost";            // Où se trouve la base
    
    // Variable privée pour le titre du site
    private static $title = "Site De Recette v2";
    
    // Variable privée pour stocker la connexion à la base
    private static $database;

    /**
     * Donne accès à la base de données
     * @return DataBase L'objet pour parler à la base
     */
    public static function getDb() {
        // Si on n'a pas encore de connexion...
        if(self::$database == null) {
            // On crée une nouvelle connexion avec nos constantes
            self::$database = new DataBase(
                self::DB_USER, 
                self::DB_NAME, 
                self::DB_PASS, 
                self::DB_HOST
            );
        }
        // On retourne la connexion
        return self::$database;
    }

    /**
     * Donne le titre du site
     * @return string Le titre
     */
    public static function getTitle() {
        return self::$title;
    }

    /**
     * Change le titre du site
     * @param string $title Le texte à ajouter devant le titre
     */
    public static function setTitle($title) {
        // On ajoute le nouveau texte + le titre de base
        self::$title = $title . " - " . self::$title;
    }

    /**
     * Redirige vers la page 404
     */
    public static function notFound() {
        header("Location: index.php?p=404");  // Redirige vers la page 404
        exit();  // On arrête tout, sinon ça continue dans le vide
    }
}