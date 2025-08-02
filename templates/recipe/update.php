<?php
if($auth->isLogged()){
    if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])){
        if($auth->checkRequiredFields(['title', "recipe", "category"])){
            $title = htmlspecialchars(trim($_POST["title"]));
            $content = htmlspecialchars(trim($_POST["recipe"]));
            $category = (int)$_POST["category"];
            
            if(App\Table\Recipes::update($id, $title, $content, $category)){
                $auth->addFlashMessage("Recette mise à jour avec succès", "success");
                header("Location: ?p=recettes&id=".$id);
                exit;
            }else{
                $auth->addFlashMessage("Erreur lors de la mise à jour", "error");
            }
        }else{
            $auth->addFlashMessage("Tous les champs sont obligatoires", "warning");
        }
    }
?>
<main class="container my-5">
<h2>Mise à jour de la recette</h2>
<form method="POST" action="?p=update&id=<?= $id ?>">
    <?= $form->input("text","title", "Le nouveau nom de la recette", htmlspecialchars($title ?? '')) ?>
    <?= $form->textarea("recipe", "Les nouvelles Etapes", htmlspecialchars($content ?? '')) ?>
    <?= $form->select("category", "Nouvelle catégorie", $categories) ?>
    <?= $form->submit("Mettre à jour"); ?>
</form>
</main>
<?php
}else{
    $auth->addFlashMessage("Vous devez être connecté pour modifier une recette", "error");
    header('Location: ?p=login');
    exit;
}