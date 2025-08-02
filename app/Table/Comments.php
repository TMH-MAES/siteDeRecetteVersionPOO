<?php
namespace App\Table;
class Comments extends Table{
    protected static $table = "comments";
   
    // Fonction helper pour formater la date en français
    public static function formatDateToFrench($dateString) {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $date = new \DateTime($dateString);
        return strftime('%d %B %Y à %H:%M', $date->getTimestamp());
    }
 
    public static function getCommentsByRecipeId($recipeId, $page = 1, $perPage = 5) {
        $offset = ($page - 1) * $perPage;
        
        return [
            'comments' => \App\App::getDb()->prepare("
                SELECT c.comment, c.created_at as date, u.full_name as name
                FROM comments c
                LEFT JOIN users u ON u.id = c.user_id
                WHERE c.recipe_id = ?
                ORDER BY c.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}
            ", [$recipeId], __CLASS__),
            'total' => \App\App::getDb()->prepare("
                SELECT COUNT(*) as count 
                FROM comments 
                WHERE recipe_id = ?
            ", [$recipeId], __CLASS__, true)->count
        ];
    }
    public static function createComment($user_id, $recipe_id, $comment){
        return \App\App::getDb()->prepare("INSERT INTO comments(user_id, recipe_id, comment, created_at) VALUES (?, ?, ?, NOW())", [$user_id, $recipe_id, $comment], __CLASS__);
    }
        
}