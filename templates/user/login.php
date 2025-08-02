<?php
App\App::setTitle("Connexion");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie d'abord si les champs sont remplis
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        if ($auth->login($_POST['email'], $_POST['password'])) {
           
            header('Location: ?p=home');
            exit;
            ob_end_flush();
        } 
        // Si échec de connexion
        else {
            $error = "Identifiants incorrects";
            $auth->addFlashMessage($error, "error");
        }
    }else{
        $error = "Tous les champs sont obligatoires";
        $auth->addFlashMessage($error, "error");
    }

}
?>

<main class="container my-5">
<h2>Connexion au compte</h2>
<form action="" method="POST">
<?= $form->input('email', 'email','Adresse Email') ?>
<?= $form->input('password',"password", 'Mot de passe') ?>
<?= $form->submit("Me connecter");?>
</form>
</main>

                            
    
    