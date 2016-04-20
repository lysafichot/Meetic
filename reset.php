<?php
require 'inc/bootstrap.php';

if(isset($_GET['id']) && isset($_GET['token'])) {
    $auth = App::getAuth();
    $db = App::getDatabase();
    $user = $auth->checkResetToken($db, $_GET['id'], $_GET['token']);
    if($user) {
        if(!empty($_POST['valid_reset'])) {

            $validator = new Validator($_POST);
            $validator->isConfirmed('password', 'Vous devez rentrer un mot de passe valide');
            if($validator->isValid()) { 
                $password = $auth->hashPassword($_POST['password']);

                $db->query('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL WHERE id = ?', [$password, $_GET['id']]);
                $auth->connect($user);
                Session::getInstance()->setFlash('success','Votre mot de passe a bien été modifié');
                App::redirect('home.php');
            } else {
                $errors = $validator->getErrors();
            }
        }
    } else {
        Session::getInstance()->setFlash('danger',"Ce token n'est pas valide");
        App::redirect('login.php');
    }
} else {
    App::redirect('login.php');
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/style.css" />    
    <title>My meetic</title>
</head>
<body>
    <header>
        <div id='logo'>
            <h1>Lorem ipsum</h1>
        </div>
        <div id='log_in'>
            <span>Already a member ? </span>
            <a href="login.php">Log In</a>
        </div>
    </header>

    <div class='empty'></div>
    <div id='signlog_outer'> 
        <div id="reset_container">

            <h2>Réinitialiser mon mot de passe</h2>
            <?php include 'include/flash.php' ?>

            <form action="#" name="reset" method="POST">
                <div class="button"><label for="password">Mot de passe</label><input type="password" id="password" name="password"/></div>
                <div class="button"><label for="password_confirm">Confirmation du mot de passe</label><input type="password" id="password_confirm" name="password_confirm"/></div>
                <input type="submit" name="valid_reset" id="valid_reset" value="Réinitialiser votre mot de passe">
            </form>
        </div>
    </div>
    <div class='empty'></div>
    <footer></footer>
</body>
</html>