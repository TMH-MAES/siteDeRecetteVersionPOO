<?php
// 1. On appelle notre autoloader , pour charger automatiquement nos classes
require 'app/Autoloader.php';
App\Autoloader::register();
// 2. on charge les classes avec les namespaces (pour avoir un code plus potable)
use App\Table\Categories;
use App\Table\Comments;
use App\Table\Recipes;
use App\Database;
// 4. Initialisation des services
$db = new Database('root', 'partage_de_recettes', '', 'localhost');
$auth = new App\Auth();

// Récupération des données communes
$recipes = Recipes::getLastRecipes();
$categories = Categories::all();
$form = App\BootstrapForm::getInstance();
$page = $_GET["p"] ?? 'home';
// 6. On affiche les messages flashs
require "partials/header.php";
$auth->displaySessionMessages();
// Déterminer le template à utiliser
switch ($page) {
    case "delete":
        $id = (int)$_GET["id"] ?? null;
        if($id===null){
            \App\App::notFound();
        }
        require_once "templates/recipe/delete.php";
        break;
    case "create":
       require_once "templates/recipe/create.php";
        break;
    case "logout":
       require_once "templates/user/logout.php";
        break;
    
    case 'home':
       require_once 'templates/home.php';
        break;
    case 'login':
       require_once 'templates/user/login.php';
        break;
    case "update":
        $id = $_GET["id"];
        require "templates/recipe/update.php";
        break;
    case 'recettes':
        $id = $_GET['id'] ?? null;
        require_once 'templates/recipe/read.php';
        break;
    case "categorie":
        $id = $_GET["id"] ?? null;
        require_once "templates/category.php";
        break;
    case "404":
        require_once "templates/404.php";
        break;
    default:
       App\App::notFound();
       break;
}
