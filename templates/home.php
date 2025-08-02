<div class="container-fluid mt-4">
  <div class="row">
    <!-- Colonne Catégories (2/12) - À GAUCHE -->
    <div class="col-lg-2 col-md-12">
      <div class="categories-sidebar p-3 bg-light rounded sticky-top" style="top: 70px;">
        <h5 class="mb-3"><i class="bi bi-tags"></i> Catégories</h5>
        <ul class="list-unstyled">
          <li class="mb-2">
            <a href="index.php?p=categorie&id=all" class="text-decoration-none d-block p-2 rounded <?= (!isset($_GET['category'])) ? 'bg-primary text-white' : '' ?>">
              Toutes les recettes
            </a>
          </li>
          <?php foreach($categories as $category): ?>
            <li class="mb-2">
              <a href="index.php?p=categorie&id=<?= $category->id ?>" 
                 class="text-decoration-none d-block p-2 rounded <?= (isset($_GET['id']) && $_GET['id'] == $category->id) ? 'bg-primary text-white' : '' ?>">
                <?= htmlspecialchars($category->title) ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Colonne Recettes (10/12) - À DROITE -->
    <div class="col-lg-10 col-md-12">
      <div class="container">
        <h1 class="mb-4">Nos recettes</h1>
        
        <?php foreach($recipes as $recipe) : ?>
          <div class="card-body">
            <a href="<?= htmlspecialchars($recipe->url) ?>" class="text-decoration-none">
              <h3 class="card-title"><?= htmlspecialchars($recipe->title) ?></h3>
            </a>
            
            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($recipe->category ?? 'Non catégorisé') ?></span>
            
            <?= $recipe->content ?>
            
            <p class="text-muted">
              <em><?= htmlspecialchars($recipe->author) ?></em>
            </p>
            
            <?php if (isset($_SESSION['auth']) && $recipe->author === $_SESSION['auth']->email): ?>
              <div class="btn-group mt-2">
                <a href="?p=update&id=<?= $recipe->id ?>" class="btn btn-sm btn-outline-warning">
                  <i class="bi bi-pencil"></i> Éditer
                </a>
                <a href="?p=delete&id=<?= $recipe->id ?>" class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i> Supprimer
                </a>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>