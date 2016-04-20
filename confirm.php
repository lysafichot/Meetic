<?php
require 'inc/bootstrap.php';

$db = App::getDatabase();

if(App::getAuth()->confirm($db, $_GET['id'], $_GET['token'], Session::getInstance())){
	$user_id = $_SESSION['auth']->id;
    Session::getInstance()->setFlash('success', "Votre compte a bien été validé");
    App::redirect("home.php");
} else {
    Session::getInstance()->setFlash('danger', "Ce token n'est plus valide");
    App::redirect('login.php');
}