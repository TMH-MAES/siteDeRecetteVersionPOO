<div class="card border-danger">
    <div class="card-body">
        <?php if($auth->isLogged()): ?>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (App\Table\Recipes::delete($id)) {
                    $auth->addFlashMessage("Recette supprimée", "success");
                } else {
                    $auth->addFlashMessage("Erreur lors de la suppression", "error");
                }
                header("Location: ?p=home");
                exit;
            }
            ?>
            
            <div class="text-center">
                <h4 class="mb-3">Confirmation</h4>
                <p class="mb-4">Voulez-vous supprimer cette recette ?</p>
                
                <form method="POST" action="?p=delete&id=<?= $id ?>">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="submit" class="btn btn-danger btn-sm">
                            Oui
                        </button>
                        <a href="?p=home" class="btn btn-outline-secondary btn-sm">
                            Non
                        </a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <?php 
            $auth->addFlashMessage("Connectez-vous pour supprimer", "error"); 
            header('Location: ?p=login');
            exit;
            ?>
        <?php endif; ?>
    </div>
</div>