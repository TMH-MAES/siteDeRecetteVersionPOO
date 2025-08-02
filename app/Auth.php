<?php
namespace App;

class Auth {
    // L'outil pour parler avec la table des utilisateurs en base de données
    private $user;

    // Le constructeur : se lance automatiquement quand on crée un objet Auth
    public function __construct() {
        $this->user = new Table\User(); // On initialise l'accès aux utilisateur
        session_start();
    }

    /**
     * Affiche un message stylisé avec Bootstrap
     * @param mixed $sessionData Le message à afficher (texte ou tableau)
     * @param string $type Le type de message (success, error, warning, info)
     */
    public function printSession($sessionData, string $type = 'success'): void {
        // Si pas de message, on ne fait rien
        if (empty($sessionData)) {
            return;
        }

        // On associe chaque type à une couleur Bootstrap
        $alertClasses = [
            'success' => 'alert-success', // Vert
            'error'   => 'alert-danger',  // Rouge
            'warning' => 'alert-warning', // Orange
            'info'    => 'alert-info'     // Bleu
        ];

        // On prend la classe correspondante ou bleu par défaut
        $class = $alertClasses[$type] ?? 'alert-primary';

        // Si on a reçu un texte simple, on le met dans un tableau
        $messages = is_array($sessionData) ? $sessionData : [$sessionData];

        // On crée la belle alerte Bootstrap
        echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
        
        // On affiche chaque ligne du message
        foreach ($messages as $message) {
            echo '<div>' . htmlspecialchars($message) . '</div>';
       }
        
        // On ajoute le bouton pour fermer
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }

    /**
     * Connecte un utilisateur
     * @param string $email L'email de l'utilisateur
     * @param string $password Le mot de passe
     * @return bool True si connexion réussie, false sinon
     */
    public function login($email, $password) {
        // On cherche l'utilisateur par son email
        $user = $this->user->findByEmail($email);
         
        // Si trouvé ET mot de passe correct
        if ($user && $password === $user->p) {
            $_SESSION['auth'] = $user; // On stocke en session
            $this->addFlashMessage("Bonjour {$user->full_name} ! Bienvenue !", "success");
            $user->p = ""; // On efface le mot de passe par sécurité
            return true; // Connexion réussie
        }
        
        return false; // Échec de la connexion
    }

    /**
     * Affiche automatiquement les messages en session
     */
    public function displaySessionMessages() {
        // S'il y a des messages flash en attente
        if (!empty($_SESSION['flash'])) {
            // On affiche chaque message
            foreach ($_SESSION['flash'] as $message) {
                $this->printSession($message['text'], $message['type']);
            }
            unset($_SESSION['flash']); // On nettoie après affichage
        }
    }
        /**
     * Vérifie si les champs requis dans $_POST sont tous remplis
     * @param array $fields Les champs à vérifier (ex: ["title", "recipe"])
     * @return bool True si tous les champs sont remplis, false sinon
     */
    public function checkRequiredFields(array $fields): bool
    {
        foreach ($fields as $field) {
            // Vérifie si le champ existe ET n'est pas vide (après suppression des espaces)
            if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
                return false;
            }
        }
        return !empty($_POST); // Retourne false si $_POST est vide
    }
    /**
     * Ajoute un message flash à afficher
     * @param string $text Le texte du message
     * @param string $type Le type de message (error par défaut)
     */
    public function addFlashMessage(string $text, string $type = 'error') {
        $_SESSION['flash'][] = [
            'text' => $text,
            'type' => $type
        ];
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function register($name, $email, $password) {
        return $this->user->create($name, $email, $password);
    }

    /**
     * Vérifie si un utilisateur est connecté
     * @return bool True si connecté, false sinon
     */
    public function isLogged() {
        return isset($_SESSION['auth']);
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout() {
        session_unset(); // Efface toutes les données de session
        session_destroy(); // Détruit la session
    }
    
    /**
     * Récupère l'utilisateur connecté
     * @return mixed L'utilisateur ou null si non connecté
     */
    public function getUser() {
        return $_SESSION['auth'] ?? null;
    }
}