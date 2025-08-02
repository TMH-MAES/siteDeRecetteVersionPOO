<?php
// Pagination
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

$perPage = 5; // Nombre de commentaires par page
$commentsData = App\Table\Comments::getCommentsByRecipeId($id, $currentPage, $perPage);
$comments = $commentsData['comments'];
$totalComments = $commentsData['total'];
$totalPages = ceil($totalComments / $perPage);

$hasComments = !empty($comments);
// VERIF COMMENTAIRES
if($auth->isLogged() && $auth->checkRequiredFields(["comment_value"])){
    $comment = htmlspecialchars($_POST["comment_value"]);
    if(empty(App\Table\Comments::createComment($_SESSION["auth"]->id, $id, $comment))){
       $auth->addFlashMessage("commentaire ajouté!", 'success');
    }else{
        $auth->addFlashMessage("Erreur Lors de l'ajout de votre commentaire", "warning");
    }
}
?>
<div class="comments-section mt-4">
    <h3>Commentaires</h3>
    <?php if($auth->isLogged()) : ?>
    <form action="" method="post">
        <?= $form->input("text","comment_value", "");?>
        <?= $form->submit("Commentez!"); ?>
    </form>
    <?php else:?>
      <p><a href="?p=login">Connectez-vous</a> Pour pouvoir commenter</p>
    <?php endif; ?>
    <?php if (!$hasComments): ?>
        <p>Pas de commentaire pour cette recette</p>
   
    <?php else: ?>
        <?php foreach($comments as $comment): ?>
            <div class="comment mb-3 p-3 border rounded">
                <b><?= htmlspecialchars($comment->name) ?> le <?= App\Table\Comments::formatDateToFrench($comment->date) ?></b>
                <p class="mt-2"><?= nl2br(htmlspecialchars($comment->comment)) ?></p>
            </div>
        <?php endforeach; ?>
        
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Pagination des commentaires">
                <ul class="pagination">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?p=recettes&id=<?= $id ?>&page=<?= $currentPage - 1 ?>" tabindex="-1">Précédent</a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?p=recettes&id=<?= $id ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?p=recettes&id=<?= $id ?>&page=<?= $currentPage + 1 ?>">Suivant</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>