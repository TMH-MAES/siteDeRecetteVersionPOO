<?php
namespace App;

class Autoloader
{
    /**
     * Enregistre l'autoloader personnalisé
     */
    public static function register(): void
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Charge automatiquement les classes selon les namespaces
     * @param string $class Le nom complet de la classe (avec namespace)
     */
    public static function autoload(string $class): void
    {
        // Remplace les \ par / et supprime le namespace de base
        $file = str_replace('App\\', '', $class);
        $file = str_replace('\\', '/', $file);
        
        // Chemin complet du fichier
        $filePath = __DIR__ . '/' . $file . '.php';
        
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    }
}