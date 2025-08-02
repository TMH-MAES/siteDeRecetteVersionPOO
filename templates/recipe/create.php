<?php
if ($auth->isLogged()): 
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($auth->checkRequiredFields(["title", "recipe", "categorie"])) {
            $title = htmlspecialchars($_POST["title"]);
            $content = htmlspecialchars($_POST["recipe"]);
            $category = htmlspecialchars($_POST["categorie"]);
            $author = $_SESSION["auth"]->email;

            $result = App\Table\Recipes::create($title, $content, $author, $category);
            
            if (empty($result)) {
                $id = App\Table\Recipes::getByTitle($title)->id;
                $auth->addFlashMessage("Recette créée avec succès", "success");
                header("Location: ?p=recettes&id=$id");
                exit;
                
            } elseif ($result === false) {
                $auth->addFlashMessage("Une recette avec ce titre existe déjà", "warning");
            } else {
                $auth->addFlashMessage("Erreur technique lors de la création", "error");
            }
        } else {
            $auth->addFlashMessage("Tous les champs sont obligatoires", "warning");
        }
    }
?>


<!-- Partie HTML - Formulaire de création -->
<main class="container my-5">
    <h2>Création d'une nouvelle recette</h2>
    
    <!-- Formulaire -->
    <form action="?p=create" method="POST">
        <!-- Champ pour le titre -->
        <?= $form->input('text', 'title', 'Le nom de la recette', $_POST['title'] ?? '') ?>
        
        <!-- Zone de texte pour la recette -->
        <?= $form->textarea('recipe', 'Les étapes de la recette', $_POST['recipe'] ?? '') ?>

        <!-- Zone De séléction de categorie -->
        <?= $form->select('categorie', 'Selectionner une catégorie', $categories) ?>
        
        <!-- Bouton de soumission -->
        <?= $form->submit("Créer ma recette"); ?>
    </form>
</main>

<?php
else:
    $auth->addFlashMessage("Connectez-vous pour créer une recette", "error");
    header("Location: ?p=login");
    exit;
endif;
?>