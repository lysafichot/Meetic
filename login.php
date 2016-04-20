<?php
require 'inc/bootstrap.php';

$auth = App::getAuth();
$db = App::getDatabase();
$auth->connectFromCookie($db);
$session = Session::getInstance(); 
if($auth->user()) {
    App::redirect("home.php");
}

if(!empty($_POST['valid_log']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
    $session = Session::getInstance();

    if($user) {
        $session->setFlash('success', 'Vous êtes maintenant connecté');
        App::redirect("home.php");
    } else {
        $session->setFlash('danger', 'Identifiant ou mot de passe incorrecte');
    }
}

if(!empty($_POST['valid_forget']) && !empty($_POST['email'])){
  
    if($auth->resetPassword($db, $_POST['email'])) {
        $session->setFlash('success', 'Les instructions du rappel de mot de passe vous ont été envoyées par emails');
    } else {
        $session->setFlash('danger', 'Aucun compte ne correspond à cet adresse');
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/style.css" />
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/toggle_forget.js"></script>
    <title>My meetic</title>
</head>
<body>
    <header>
        <div id='logo'><h1>Soul Mate</h1></div>
        <div id='log_in'><span>Not yet registered ?</span><a href="sign.php">Sign In</a></div>
    </header>

    <div class='empty'></div>

    <div id='signlog_outer'> 
       <div id='log_container'>
           
            <h2>Log in</h2>

            <?php include 'include/flash.php' ?>

            <form method='POST' action="#" name='log' id='log'>
                <div id="log_account">
                    <div class="button"><label for="username">Username : </label><input type="text" name="username" id="username" value=""><br></div>
                    <div class="button"><label for="password">Password : </label><input type="password" name="password" id="password" value=""><br></div>
                    <div id="remember">
                        <label><input type="checkbox" name="remember" value="1"/> Se souvenir de moi</label> 
                        <input id= "valid_log" type="submit" name="valid_log" value="Log in !">
                    </div>
                </div>
            </form>

            <div id='sign_in'><a href="forget.php">J'ai oublié mon mot de passe</a></div>

            <div id="forget">
                <form action="#" method="POST">
                    <div class="button"><label for="email">Email : </label><input type="email" id='email' name="email"/></div>
                    <input type="submit" name="valid_forget" id="valid_forget" value="New password">
                </form>
            </div>
        </div>
    </div>
    <div class='empty'></div>
    <footer></footer>
</body>
</html>