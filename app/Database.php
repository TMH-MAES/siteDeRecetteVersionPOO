<?php
namespace App;

use \PDO;

class DataBase {
    // Les informations pour se connecter à la base de données
    private $db_user; // Nom d'utilisateur
    private $db_name; // Nom de la base
    private $db_pass; // Mot de passe
    private $db_host; // Adresse du serveur
    private $pdo;     // Notre connexion à la base

    // Le constructeur : on lui donne les infos de connexion
    public function __construct($db_user, $db_name, $db_pass, $db_host) {
        $this->db_user = $db_user;
        $this->db_name = $db_name;
        $this->db_pass = $db_pass;
        $this->db_host = $db_host;
    }

    /**
     * Crée et retourne la connexion à la base de données
     * @return PDO L'objet pour parler à la base
     */
    public function getPDO() {
        // Si on n'est pas déjà connecté...
        if($this->pdo === null) {
            // On se connecte avec les infos
            $pdo = new PDO(
                "mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8",
                $this->db_user,
                $this->db_pass
            );
            
            // On dit à PDO de nous avertir en cas d'erreur
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // On garde la connexion pour plus tard
            $this->pdo = $pdo;
        }
        
        // On retourne la connexion
        return $this->pdo;
    }

    /**
     * Fait une requête simple (sans paramètres)
     * @param string $statement La requête SQL
     * @param string $class_name Le nom de la classe pour créer les objets
     * @return array Les résultats sous forme d'objets
     */
    public function query($statement, $class_name) {
        // On exécute la requête
        $req = $this->getPDO()->query($statement);
        
        // On récupère les résultats sous forme d'objets
        $data = $req->fetchAll(PDO::FETCH_CLASS, $class_name);
        
        return $data;
    }

    /**
     * Fait une requête préparée (plus sécurisée)
     * @param string $statement La requête SQL avec des ?
     * @param array $options Les valeurs à mettre à la place des ?
     * @param string $class_name Le nom de la classe pour les objets
     * @param bool $one True si on veut un seul résultat
     * @return mixed Un objet ou un tableau d'objets
     */
    public function prepare($statement, $options, $class_name, $one = false) {
        // On prépare la requête
        $req = $this->getPDO()->prepare($statement);
        
        // On exécute avec nos valeurs
        $req->execute($options);
        
        // On dit comment formater les résultats
        $req->setFetchMode(PDO::FETCH_CLASS, $class_name);
        
        // Si on veut un seul résultat
        if($one) {
            $datas = $req->fetch();
        } 
        // Sinon on prend tout
        else {
            $datas = $req->fetchAll();
        }
        
        return $datas;
    }
}