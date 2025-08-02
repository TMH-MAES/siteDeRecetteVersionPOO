<?php
namespace App\Table;

use App\App;

/**
 * Classe pour gérer tout ce qui concerne les recettes
 */
class Recipes extends Table
{
    // Le nom de la table dans la base de données
    protected static $table = "recipes";
    
    /**
     * Récupère les 6 dernières recettes
     * @return array La liste des recettes récentes
     */
    public static function getLastRecipes()
    {
    return App::getDb()->query("
        SELECT r.id, r.title, r.recipe, r.author, c.title as category 
        FROM recipes r
        LEFT JOIN categories c ON r.category_id = c.id
        ORDER BY r.id DESC  
        LIMIT 6",
        __CLASS__);
    }
    
    /**
     * Trouve les recettes d'une catégorie
     * @param int $categoryId Le numéro de la catégorie
     * @return array Les recettes de cette catégorie
     */
    public static function getRecipesByCategory($categoryId) 
    {
        return App::getDb()->prepare("
            SELECT r.id, r.title, r.recipe, r.author, c.title as category
            FROM recipes r
            LEFT JOIN categories c ON r.category_id = c.id
            WHERE r.category_id = ?", 
            [$categoryId],
            __CLASS__);
    }
   
    /**
     * Crée le lien vers la page de la recette
     * @return string L'URL pour voir la recette en détail
     */
    public function getUrl()
    {
        return "index.php?p=recettes&id=" . $this->id;
    }
    
    /**
     * Crée un résumé court de la recette
     * @return string Un texte court avec un lien "Voir plus"
     */
    public function getContent()
    {
        // On prend les 50 premiers caractères sans les balises HTML
        $extrait = substr(strip_tags($this->recipe), 0, 50) . "...";
        return '
            <p>' . htmlspecialchars($extrait) . '</p>
            <p class="card-text">
                <a href="' . $this->getUrl() . '">Voir plus</a>
            </p>';
    } 
    
        /**
     * Modifie une recette existante
     * @param int $id Le numéro de la recette
     * @param string $title Le nouveau titre
     * @param string $content La nouvelle recette
     * @param int $category_id L'ID de la catégorie
     * @return bool True si modification réussie
     */
    public static function update($id, $title, $content, $category_id)
    {
        $db = App::getDb();
        $r = $db->prepare(
            "UPDATE " . self::$table . " 
            SET title = ?, recipe = ?, category_id = ? 
            WHERE id = ?",
            [$title, $content, $category_id, $id], __CLASS__
        );
        if(empty($r)){return true;}
    }
        /**
     * Vérifie et upload un fichier image
     * @param array $file Le fichier ($_FILES['nom_du_champ'])
     * @param Auth $auth Instance pour les messages flash
     * @param string $uploadDir Dossier de destination (ex: "uploads/")
     * @return string|false Chemin du fichier ou false si échec
     */
    public function verifyFile(array $file, Auth $auth, string $uploadDir) {
        // 1. Vérifier si un fichier a été uploadé
        if ($file['error'] != 0) {
            $auth->addFlashMessage("Erreur lors de l'envoi du fichier", "error");
            return false;
        }

        // 2. Vérifier la taille (1 Mo max)
        if ($file['size'] > 1000000) {
            $auth->addFlashMessage("Le fichier est trop lourd (max 1Mo)", "error");
            return false;
        }

        // 3. Vérifier que c'est une image
        $allowedTypes = ['jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $auth->addFlashMessage("Seules les images JPG, PNG et GIF sont autorisées", "error");
            return false;
        }

        // 4. Préparer l'upload
        $extension = array_search($mimeType, $allowedTypes);
        $fileName = uniqid('img_') . '.' . $extension;
        $destination = rtrim($uploadDir, '/') . '/' . $fileName;

        // Créer le dossier si inexistant
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 5. Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $destination;
        } else {
            $auth->addFlashMessage("Échec de l'enregistrement du fichier", "error");
            return false;
        }
    }
    public static function getByTitle($title) {
        return App::getDb()->prepare(
            "SELECT * FROM recipes WHERE title = ?", 
            [$title], 
            __CLASS__,
            true
        );
    }
    
    public static function create($title, $content, $author, $id) {
    $db = App::getDb();
    
    // Vérifie si la recette existe déjà
    $existing = $db->prepare(
        "SELECT id FROM " . self::$table . " WHERE title = ? LIMIT 1", 
        [$title], __CLASS__
    );

    
    if ($existing) {
        return false; // La recette existe déjà
    }
    
    // Exécute l'insertion
    $success = $db->prepare(
        "INSERT INTO " . self::$table . " 
        (title, recipe, author, is_enabled, category_id) 
        VALUES (?, ?, ?, 1, ?)",
        [$title, $content, $author, $id],
        __CLASS__
    );
    
    return $success ;
}
 /**
 * Supprime une recette et ses données associées (commentaires, évaluations)
 * @param int $id Le numéro de la recette à supprimer
 * @return bool True si suppression réussie
 */
public static function delete(int $id): bool
{
    $db = App::getDb();
    
    try {
        // Commencer une transaction
        $db->getPDO()->beginTransaction();
        
        // 1. Supprimer les commentaires associés
        $db->prepare(
            "DELETE FROM comments WHERE recipe_id = ?", 
            [$id], __CLASS__
        );
        
        // 2. Supprimer les évaluations associées
        $db->prepare(
            "DELETE FROM reviews WHERE recipe_id = ?", 
            [$id], __CLASS__
        );
        
        // 3. Supprimer la recette
        $result = $db->prepare(
            "DELETE FROM " . self::$table . " WHERE id = ?", 
            [$id], __CLASS__
        );
        
        // Valider la transaction
        $db->getPDO()->commit();
        
        return $result !== false;
        
    } catch (PDOException $e) {
        // Annuler en cas d'erreur
        $db->rollBack();
        error_log("Erreur suppression recette #$id: " . $e->getMessage());
        return false;
    }
}
}