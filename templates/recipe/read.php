<?php
$recipe = App\Table\Recipes::find($id);
if(empty($recipe)) {
    App\App::notFound();
}
$category = App\Table\Categories::find($recipe->category_id);
App\App::setTitle($recipe->title);

?>

<div class="container">
    <h1><?= htmlspecialchars($recipe->title) ?></h1>
    <p class='card-text mb-4'><strong>Auteur: </strong><?= htmlspecialchars($recipe->author) ?></p>
    <span class="badge bg-secondary mb-2"><?= htmlspecialchars($category->title) ?></span>
    
    <div class="card">
        <div class='card-body'>
            <p class="card-text mb-10"><strong>Recette</strong></p>
            <p class="card-text"><?= nl2br(htmlentities(strip_tags($recipe->recipe))) ?></p>    
        </div>
    </div>
    
    <?php require("templates/comments.php"); ?>
</div>

<?php require "partials/footer.php"; ?>