<?php
require 'inc/bootstrap.php';

$db = App::getDatabase();
App::getAuth()->restrict();

$user_id = $_SESSION['auth']->id;

$user = $db->query('SELECT * FROM users WHERE id = :id', ['id' => $user_id])->fetch();
$genre = $db->query('SELECT * FROM sexe WHERE id_sexe = :sexe', ['sexe' => $user->id_sexe])->fetch();

$sql = 'SELECT villes.nom AS ville , villes.code_postal, villes.id_departement,
departements.id_departement, departements.nom AS departement, departements.id_region, 
regions.id_region, regions.nom AS region, users.id, users.code_postal FROM users 
LEFT JOIN villes ON users.code_postal = villes.code_postal 
LEFT JOIN departements ON villes.id_departement = departements.id_departement 
LEFT JOIN regions ON departements.id_region = regions.id_region 
WHERE users.id = :id';

$live = $db->query(''.$sql.'', ['id' => $user_id])->fetch();
$validator = new Validator($_POST);

if(isset($_POST['user_modif'])) {

    $username = htmlspecialchars(ucfirst($_POST['username']));

    $validator->isAlpha('username', "Votre pseudo n'est pas valide (alphanumérique)");
    if($validator->isValid()) {
        $validator->isUniq('username', $db, 'users', 'Ce pseudo est déjà pris');
    }
    if($validator->isValid()) {
        $db->query('UPDATE users SET username = ? WHERE id = ?', [$username, $user_id]);
        $user->username = $username;
    }
    else {
        $errors = $validator->getErrors();
    }
}

if(isset($_POST['code_modif'])) {
    if(!empty($_POST['code_postal'])) {

        $code_postal = htmlspecialchars($_POST['code_postal']);
        $validator->isCodep('code_postal', $db, 'villes', "Code postal invalide");

        if($validator->isValid()) {
            $db->query('UPDATE users SET code_postal = ? WHERE id = ?', [$code_postal, $user_id]);
            $user->code_postal = $code_postal;
            $live = $db->query(''.$sql.'', ['id' => $user_id])->fetch();
        }
        else {
            $errors = $validator->getErrors();
        }
    }
}

if(isset($_POST['email_modif'])) {
    if(!empty($_POST['pass']) && !empty($_POST['email'])) {
        $pass = $_POST['pass']; 
        $email = htmlspecialchars($_POST['email']);

        if(password_verify($pass, $user->password)) { 

            $validator->isEmail('email', "Votre email n'est pas valide");
            if($validator->isValid()) {
                $validator->isUniq('email', $db, 'users', 'Cet email est déjà utilisé pour un autre compte');
            } 
            if($validator->isValid()) { 
                $db->query('UPDATE users SET email = ? WHERE id = ?', [$email, $user_id]);
                $_SESSION['flash']['success'] = "Votre email a bien été mis à jour";
                $user->email = $email;
            } else {
                $errors = $validator->getErrors();
            }
        } else {
           $_SESSION['flash']['danger'] = "Erreur dans le mot de passs actuel";
       }
   } else {
    $_SESSION['flash']['danger'] = "Veuillez remplir tous les champs";
}
}

if(isset($_POST['pass_modif'])) {
    if(!empty($_POST['pass1'])) {
        $pass = $_POST['pass1']; 

        if(password_verify($pass, $user->password)) { 

            $validator->isConfirmed('password', 'Les deux mots de passe ne coincident pas');
            if($validator->isValid()) { 
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $db->query('UPDATE users SET password = ? WHERE id = ?', [$password, $user_id]);
                $_SESSION['flash']['success'] = "Votre mot de passe a bien été mis à jour";
            } else {
                $errors = $validator->getErrors();
            }
        } else {
            $_SESSION['flash']['danger'] = "Erreur dans le mot de passs actuel";
        }  
    } else {
        $_SESSION['flash']['danger'] = "Veuillez remplir tous les champs";
    }  
}

if(isset($_POST['avatar_modif'])) {
    $uploaddir = 'css/img/avatar/';
    $validator->isSize('avatar', 'You picture is too oversized');
    $validator->isExtension('avatar', 'You must upload a type of picture with png, gif, jpg, jpeg, please.');
    if($validator->isValid()) { 
        $uploadfile = basename($_FILES['avatar']['name']);
        $uploadfile = strtr($uploadfile, 
            'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
            'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); 
        $avatar = $uploaddir.$uploadfile;

        $db->query('UPDATE users SET avatar = ? WHERE id = ?', [$avatar, $user_id]);
        $user->avatar = $avatar;
    } else {
        $errors = $validator->getErrors();
    }
}

if(isset($_POST['delete'])) {
    $activate = 0;
    $db->query('UPDATE users SET activate = 0 WHERE id = ?', [$user_id]);
    $user->activate = $activate;

    App::getAuth()->logout();
    Session::getInstance()->setFlash('danger', 'Votre compte a bien ete supprimer');
    App::redirect('login.php');
    session_destroy();
}
?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="description" content=""/>
    <link type= "text/css" rel="stylesheet" href="css/header.css" />
    <link type= "text/css" rel="stylesheet" href="css/account.css" />
    <script type="text/javascript" src="js/jquery-2.1.4.js"></script>
    <script type="text/javascript" src="js/toggle_modif.js"></script>
    <title>My meetic</title>
</head>

<body>
   <?php include 'include/menu.php' ?>
    <div id="container">

        <form action="#" method="post" enctype="multipart/form-data">

            <div id='info_general'>
                <fieldset id='picture' disabled>
                    <div id='avatar_account' class='modif'><img src='<?= $user->avatar; ?>' alt='avatar' /></div>
                    <div id="avatar_modif" class="champ">
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                        <input type="submit" name="avatar_modif" id='ok' value="OK"/>
                    </div>
                </fieldset>


                <div id="info_profil">
                    <div><span>Firstname : </span><strong><?= $user->firstname; ?></strong> </div>
                    <div><span>Lastname : </span><strong><?= $user->lastname; ?></strong></div>
                    <div><span>Age : </span><strong><?= App::getAuth()->age($db, $user_id); ?></strong></div>
                    <div><span>Birthday : </span><strong><?= $user->birthday; ?></strong> </div>
                    <div><span>Sexe : </span><strong><?= $genre->sexe; ?></strong></div>
                </div>
            </div>

            <?php include 'include/flash.php' ?>
               
            <div id="info_account">
                <fieldset disabled>
                    <div class='modif'><span>Username : </span><strong><?= $user->username; ?></strong></div>
                    <div id="user_modif" class="champ">
                        <input type="text" name="username" placeholder="New username"/>
                        <input class="button" type="submit" name="user_modif" value="OK"/>
                    </div>
                </fieldset>

                <fieldset disabled>
                    <div class='modif'>
                        <span>Code postal : </span><strong><?= $user->code_postal ?></strong><br>
                        <span>Ville : </span><?= $live->ville?><strong></strong><br>
                        <span>Departement : </span><?= $live->departement?><strong></strong><br>
                        <span>Region : </span><?= $live->region?><strong></strong><br>
                    </div>
                    <div id="code_modif" class="champ">
                        <input type="text" name="code_postal" placeholder="Code postal" maxlength="5" value="" />
                        <input class="button" type="submit" name="code_modif" value="OK"/>
                    </div>
                </fieldset>

                <fieldset disabled> 
                    <div class='modif'>
                        <span>Email : </span><strong><?= $user->email; ?></strong> 
                    </div>
                    <div id="email_modif" class="champ">
                        <input type="password" name="pass" placeholder="Password actuel"/>
                        <input type="email" name="email" placeholder="New email"/>
                        <input class="button" type="submit" name="email_modif" value="OK"/>
                    </div>
                </fieldset>

                <fieldset disabled>
                    <div class='modif'><span>Password : </span><strong></strong></div>
                    <div id="pass_modif" class="champ">
                        <input type="password" name="pass1" placeholder="Password actuel"/>
                        <input type="password" name="password" placeholder="New mot de passe"/>
                        <input type="password" name="password_confirm" placeholder="Confirm password"/> 
                        <input class="button" type="submit" name="pass_modif" value="OK"/>
                    </div>
                </fieldset>
                <fieldset disabled>
                    <div class='modif'><span>Supprimer mon compte : </span><strong></strong></div>
                    <div id="delete" class="champ">
                        <div id="confirm">
                            <span>Vous etes sur le point de supprimer votre compte. Etes vous sure de vouloir continuer ?</span>
                            <input class="button" type="submit" name="delete" value="Delete my account"/>
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</body>
</html>